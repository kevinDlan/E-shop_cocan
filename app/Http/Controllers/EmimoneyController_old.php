<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmimoneyController extends Controller
{
    
    private $_apiContext;
    protected  $url = 'https://www.emimoney.com/payment/form';
    protected $_EMIMONEY_CLIENT_ID =  null,
              $_EMIMONEY_CLIENT_SECRET =    null;

    public function __construct()
    {
        $this->_EMIMONEY_CLIENT_ID =     env('EMIMONEY_CLIENT_ID');
        $this->_EMIMONEY_CLIENT_SECRET =    env('EMIMONEY_CLIENT_SECRET');
    }

    public function getCheckout( )
    {
         $bufferdata = [];
         $bufferdata['amount'] = 0;
         $bufferdata['currency'] = 'XOF';
         $bufferdata['notif_url'] = url('emimoney/payment/done');
         $bufferdata['cancel_url'] = url('emimoney/payment/cancel');
         $bufferdata['item_name'] = "";
         $bufferdata['order_no'] = "";

         $order = Order::findOrFail(Session::get('order_id'));

        if(Session::has('payment_type')){
            if(Session::get('payment_type') == 'cart_payment'){
                $bufferdata['amount'] =  $order->grand_total;

                $bufferdata['item_name'] = "Commande #".$order->code ." chez  Agogo.ci";
                $bufferdata['order_no'] = $order->code;

            }
            elseif(Session::get('payment_type') == 'souscrire_payment'){
                $bufferdata['amount'] = Session::get('payment_data')['amount'];


                $bufferdata['item_name'] = "Abonnement #".$bufferdata['order_no'] ."chez  Agogo.ci ".Session::get('payment_data')['libelle'];
                $bufferdata['order_no'] = 'scrp'.date('YmHis');
                Session::put('order_formule', $bufferdata['order_no']);

            }
            elseif (Session::get('payment_type') == 'seller_payment' || Session::get('payment_type') == 'wallet_payment') {

               $bufferdata['amount'] = Session::get('payment_data')['amount'];

                $description = 'Payment to seller';
            }
        }
    	// This is the simple way,
    	// you can alternatively describe everything in  the order separately;
    	// Reference the PayPal PHP REST SDK for details.

        $SDK  = new Emimoneysdk( $this->_EMIMONEY_CLIENT_ID, $this->_EMIMONEY_CLIENT_SECRET);

        //Genrerer le token
        $datatoken = $SDK->getAccessToken();
        if(!empty($datatoken) && $datatoken->status==="success"){
            // on cree le paiement
          $datacreate =  $SDK->create($bufferdata, $datatoken->data->access_token);
             if($datacreate->status === "success"){

                $messageSeller = "Bonjour, l'un de vos produits vient de faire l'objet d'une commande, rdv sur  Agogo.ci pour plus d'information ! merci";
                $messageCustomer = "Votre commande sur  Agogo.ci, a bien été pri en compte.Nous vous donnerons suite dans les plus brefs délais! merci et à bientôt pour de nouveau achat sur  Agogo.ci";

                foreach($order->orderDetails->groupBy('seller_id') as $key =>$detail){
                    //dd($key,$detail);
                    $phoneSeller= User::find($key)->phone;
                    \isSmsapi('225'.$phoneSeller,config('app.SENDERID'),$messageSeller);
                }
                $phoneCustomer = json_decode($order->shipping_address)->phone;
                \isSmsapi('225'.$phoneCustomer,config('app.SENDERID'),$messageCustomer);

              return redirect()->to($datacreate->data->urlpaiement);
          }else{

                 flash('Une erreur s\'est produite avec la transaction EMIMONEY')->warning();
                 return back();

             }

        }else{
            flash('Une erreur s\'est produite avec la transaction EMIMONEY')->warning();
            return back();
        }


    }


    public function getCancel(Request $request)
    {
        // Curse and humiliate the user for cancelling this most sacred payment (yours)
        $request->session()->forget('order_id');
        $request->session()->forget('payment_data');
        flash(__('Payment cancelled'))->success();
        return redirect()->route('formulesrequest.index');
    }

    public function getDone(Request $request)
    {
        $SDK = new Emimoneysdk($this->_EMIMONEY_CLIENT_ID, $this->_EMIMONEY_CLIENT_SECRET);
        if (Session::get('payment_type') == 'cart_payment'){
            $order = Order::findOrFail(Session::get('order_id'));

           $dataexec = $SDK->execute($order->code);
           }else if (Session::get('payment_type') == 'souscrire_payment'){
            $order =  Session::get('order_formule');

            $dataexec = $SDK->execute($order);
        }
        if($dataexec->status !="success"){
            return $this->getCancel($request);
        }
        $payment = $dataexec->message;

        if($request->session()->has('payment_type')){
            if($request->session()->get('payment_type') == 'cart_payment'){
                $checkoutController = new CheckoutController;
                return $checkoutController->checkout_done($request->session()->get('order_id'), $payment);
            }
            elseif ($request->session()->get('payment_type') == 'seller_payment') {
                $commissionController = new CommissionController;
                return $commissionController->seller_payment_done($request->session()->get('payment_data'), $payment);
            }
            elseif ($request->session()->get('payment_type') == 'wallet_payment') {
                $walletController = new WalletController;
                return $walletController->wallet_payment_done($request->session()->get('payment_data'), $payment);
            }
            elseif ($request->session()->get('payment_type') == 'souscrire_payment') {
                $formuleController = new FormuleRequestController;
                return $formuleController->souscrire_payment_done($request->session()->get('payment_data'), $payment);
            }
        }
    }

}
