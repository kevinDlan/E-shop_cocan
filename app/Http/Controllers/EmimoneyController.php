<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Utility\SmsUtility;
use App\Helpers\Emimoneysdk;
use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use Illuminate\Support\Facades\Session;

class EmimoneyController extends Controller
{
    
    public function getCheckout()
    {
        // Creating an environment
        $clientId = env('EMIMONEY_CLIENT_ID');
        $clientSecret = env('EMIMONEY_CLIENT_SECRET');

        $order = Order::where('combined_order_id',Session::get('combined_order_id'))->first();
        
        $code_order ='';
        $items ='';
        if(Session::has('payment_type')){
            if(Session::get('payment_type') == 'cart_payment'){
                $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
                $amount = $combined_order->grand_total;
                $items ="Commande #00".$order->code." chez  Agogo.ci";
            }
            elseif (Session::get('payment_type') == 'wallet_payment') {
                $amount = Session::get('payment_data')['amount'];
                $items ="Achat wallet chez  Agogo.ci";
            }
            elseif (Session::get('payment_type') == 'customer_package_payment') {
                $customer_package = CustomerPackage::findOrFail(Session::get('payment_data')['customer_package_id']);
                $amount = $customer_package->amount;
                $items ="Achat package client chez  Agogo.ci";

            }
            elseif (Session::get('payment_type') == 'seller_package_payment') {
                $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
                $amount = $seller_package->amount;
                $items ="Achat package vendeur chez  Agogo.ci";

            }
        }

        $bufferdata = [];
        if(empty($code_order)){
            $code_order = rand(000000,999999);

        }else{
            $code_order = $order->code;
        }
        $bufferdata['amount'] =  $amount;
        $bufferdata['currency'] = 'XOF';
        $bufferdata['notif_url'] = url('emimoney/payment/done');
        $bufferdata['cancel_url'] = url('emimoney/payment/cancel');
        $bufferdata['item_name'] = $items;
        $bufferdata['order_no'] = $code_order;
        $SDK  = new Emimoneysdk($clientId, $clientSecret);
        //Genrerer le token
        $datatoken = $SDK->getAccessToken();
        
        try {
            if(!empty($datatoken) && $datatoken->status==="success"){
                // on cree le paiement
              $datacreate =  $SDK->create($bufferdata, $datatoken->data->access_token);
                 if($datacreate->status === "success"){
    
                    $messageSeller = "Bonjour, l'un de vos produits vient de faire l'objet d'une commande, rdv sur  Agogo.ci pour plus d'information ! merci";
                    $messageCustomer = "Votre commande sur  Agogo.ci, a bien été pri en compte.Nous vous donnerons suite dans les plus brefs délais! merci et à bientôt pour de nouveau achat sur  Agogo.ci";
                    foreach($order->orderDetails->groupBy('seller_id') as $key =>$detail){
                        //dd($key,$detail);
                        $phoneSeller= User::find($key)->phone;
                        SmsUtility::send_msg_to_seller_for_order($phoneSeller);
                    }
                    $phoneCustomer = json_decode($order->shipping_address)->phone;
                    SmsUtility::send_msg_to_customer_for_order($phoneCustomer);
    
                  return redirect()->to($datacreate->data->urlpaiement);
              }else{
    
                     flash('Une erreur s\'est produite avec la transaction EMIMONEY')->warning();
                     return back();
    
                 }
    
            }else{
                flash('Une erreur s\'est produite avec la transaction EMIMONEY')->warning();
                return back();
            }
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            //return Redirect::to($response->result->links[1]->href);
        }catch (HttpException $ex) {

        }
    }


    public function getCancel(Request $request)
    {
        // Curse and humiliate the user for cancelling this most sacred payment (yours)
        $request->session()->forget('order_id');
        $request->session()->forget('payment_data');
        flash(translate('Payment cancelled'))->success();
    	return redirect()->route('home');
    }

    public function getDone(Request $request)
    {
        //dd($request->all());
        // Creating an environment
        $clientId = env('EMIMONEY_CLIENT_ID');
        $clientSecret = env('EMIMONEY_CLIENT_SECRET');

        // $response->result->id gives the orderId of the order created above
        $SDK  = new Emimoneysdk($clientId, $clientSecret);
        //Genrerer le token
        $datatoken = $SDK->getAccessToken();
        //$ordersCaptureRequest->prefer('return=representation');
        try {
            $order = Order::findOrFail(Session::get('order_id'));
            // Call API with your client and get a response for your call
            $response = $datatoken->execute($order->code);
            if($response->status !="success"){
                return $this->getCancel($request);
            }
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            if($request->session()->has('payment_type')){
                if($request->session()->get('payment_type') == 'cart_payment'){
                    $checkoutController = new CheckoutController;
                    return $checkoutController->checkout_done($request->session()->get('combined_order_id'), json_encode($response));
                }
                elseif ($request->session()->get('payment_type') == 'wallet_payment') {
                    $walletController = new WalletController;
                    return $walletController->wallet_payment_done($request->session()->get('payment_data'), json_encode($response));
                }
                elseif ($request->session()->get('payment_type') == 'customer_package_payment') {$customer_package_controller = new CustomerPackageController;
                    return $customer_package_controller->purchase_payment_done($request->session()->get('payment_data'), json_encode($response));
                }
                elseif ($request->session()->get('payment_type') == 'seller_package_payment') {$seller_package_controller = new SellerPackageController;
                    return $seller_package_controller->purchase_payment_done($request->session()->get('payment_data'), json_encode($response));
                }
            }
        }catch (HttpException $ex) {

        }
    }

}
