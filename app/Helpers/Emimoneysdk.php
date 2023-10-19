<?php
/**
 * Created by PhpStorm.
 * User: stebio
 * Date: 20/07/17
 * Time: 14:16
 */

namespace App\Helpers;

use DB;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Session;


class Emimoneysdk{



    protected   $sandbox_mode,
        $client_id,
        $client_secret,
        $access_token;

    public  $URL_EMIMONEY = null;


    public function __construct($client_id = null, $client_secret =null, $sandbox_mode = true) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->setSandboxMode($sandbox_mode);
        $this->URL_EMIMONEY = "https://emimoney.com";

    }

    private function setSandboxMode($sandbox_mode) {
        if(!in_array($sandbox_mode, [0, 1]))
            throw new Exception('Le sandbox_mode doit etre egal a true ou false (0 ou 1)');

        $this->sandbox_mode = $sandbox_mode;
    }
    /*
     * on genere le token
     */
    public function getAccessToken()
    {
        $url = "https://www.emimoney.com/merchant/api/v1/oauth2/token";


        $postdata = ["client_id"=>$this->client_id ,
            "client_secret" => $this->client_secret,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);

        return json_decode($server_output);
    }


    /*
     * Creer le paiement depuis le formulaire
     */
    public function create($payment_data, $gtoken) {
        $url = "https://www.emimoney.com/merchant/api/v1/payments/payment";

        $amount = (isset($payment_data['amount'])) ? $payment_data['amount'] : null;
        $currency = (isset($payment_data['currency'])) ? $payment_data['currency'] : null;
        $notif_url = (isset($payment_data['notif_url'])) ? $payment_data['notif_url'] : null;
        $cancel_url = (isset($payment_data['cancel_url'])) ? $payment_data['cancel_url'] : null;
        $order_no = (isset($payment_data['order_no'])) ? $payment_data['order_no'] : null;
        $item_name = (isset($payment_data['item_name'])) ? $payment_data['item_name'] : null;




        $payment_data = ["amount"=> $amount ,
            "currency" => $currency,
            "return_url" => $notif_url,
            "cancel_url" => $cancel_url,
            'item_name' => $item_name,
            'order_no'  => $order_no
        ];


        $authorization = "Authorization: Bearer ".$gtoken;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        if ($this->sandbox_mode) {
            curl_setopt($ch, CURLOPT_URL, "$url");
        } else {
            curl_setopt($ch, CURLOPT_URL, "$url");
        }

        //  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' ,   'Authorization' => 'Bearer '.$this->getAccessToken() ));
        curl_setopt($ch, CURLOPT_POST, 1);
        //  $payment_data['access_token'] = $gtoken;
        // $payment_data = json_encode($payment_data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payment_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);



        $headers = array();
        $headers[] = "Accept: application/json";
        $headers[] = "Accept-Language: en_US";
        $headers[] = $authorization;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $server_output = curl_exec($ch);
        curl_close($ch);

        return json_decode($server_output);

    }

    /*
     * Verifier le status de trasaction
     */
    public function execute($order_no) {
        if ($this->sandbox_mode) {
            $url = "https://www.emimoney.com/merchant/api/v1/payments/execute" ;//"https://emimoney.com/v1/payments/execute";
        } else {
            $url = "https://www.emimoney.com/merchant/api/v1/payments/execute";
        }
        $data = ["client_id"=>$this->client_id , "client_secret" =>  $this->client_secret, "order_no"=> $order_no];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);

        curl_close($ch);
        return json_decode($server_output);
    }



    /*
   * Function pour remplacer les espaces
   *
   */
    public  function remove_accent($text){
        // replace non letter or digits by -

        $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð',
            'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã',
            'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ',
            'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ',
            'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę',
            'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī',
            'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ',
            'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ',
            'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť',
            'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ',
            'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ',
            'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');

        $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O',
            'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c',
            'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u',
            'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D',
            'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
            'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K',
            'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o',
            'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S',
            's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W',
            'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i',
            'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');

        $text = str_replace($a, $b, $text);

        return  $text;
    }

    function myslug($str){
        $myslug = mb_strtolower(preg_replace(array('/[^a-zA-Z0-9 \'-]/', '/[ -\']+/', '/^-|-$/'),
            array('', '-', ''), $this->remove_accent($str)));

        return $myslug;

    }

}