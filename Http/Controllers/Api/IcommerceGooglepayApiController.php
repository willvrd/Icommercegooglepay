<?php

namespace Modules\Icommercegooglepay\Http\Controllers\Api;

// Requests & Response
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Base Api
use Modules\Ihelpers\Http\Controllers\Api\BaseApiController;

// Repositories
use Modules\Icommerce\Repositories\OrderRepository;
use Modules\Setting\Contracts\Setting;

class IcommerceGooglepayApiController extends BaseApiController
{
    
    private $order;
    private $setting;

    public function __construct(
        OrderRepository $order,
        Setting $setting
    ){
        $this->order = $order;
        $this->setting = $setting; 
    }
    
    /**
     * Response Api Method
     * @param Requests request
     * @return route 
     */
    public function response(Request $request){

        try {

            \Log::info('Module Icommercegooglepay: Response - '.time());

            $response = $request->response;
            $orderID = $request->orderId;


            if($response['statusCode']=="CANCELED"){
                $newstatusOrder = 2;
                $msjTheme = "icommerce::email.error_order";
                $msjSubject = trans('icommerce::common.emailSubject.history')."- Order:".$orderID;
                $msjIntro = trans('icommerce::common.emailIntro.history');
            }

            $success_process = icommerce_executePostOrder($orderID,$newstatusOrder,$response);


            $order = $this->order->find($orderID);

            $email_from = $this->setting->get('icommerce::from-email');
            $email_to = explode(',',$this->setting->get('icommerce::form-emails'));
            $sender  = $this->setting->get('core::site-name');

            $userEmail = $order->email;
            $userFirstname = "{$order->first_name} {$order->last_name}";

            $products=[];
                
            foreach ($order->products as $product) {
                array_push($products,[
                    "title" => $product->title,
                    "sku" => $product->sku,
                    "quantity" => $product->pivot->quantity,
                    "price" => $product->pivot->price,
                     "total" => $product->pivot->total,
                ]);
            }

            $content=[
                'order'=>$order,
                'products' => $products,
                'user' => $userFirstname
            ];

            //icommerce_emailSend(['email_from'=>[$email_from],'theme' => $msjTheme,'email_to' => 'wavutes@gmail.com','subject' => $msjSubject, 'sender'=>$sender,'data' => array('title' => $msjSubject,'intro'=> $msjIntro,'content'=>$content)]);     
            //icommerce_emailSend(['email_from'=>[$email_from],'theme' => $msjTheme,'email_to' => $order->email,'subject' => $msjSubject, 'sender'=>$sender,'data' => array('title' => $msjSubject,'intro'=> $msjIntro,'content'=>$content)]);     
            //icommerce_emailSend(['email_from'=>[$email_from],'theme' => $msjTheme,'email_to' => $email_to,'subject' => $msjSubject, 'sender'=>$sender,'data' => array('title' => $msjSubject,'intro'=> $msjIntro,'content'=>$content)]);
            
            // Check order
            if (!empty($order))
                $redirectRoute = route('icommerce.order.showorder', [$order->id, $order->key]);
            else
                $redirectRoute = route('homepage');

            // Response
            $response = [ 'data' => [
                "redirectRoute" => $redirectRoute
            ]];

        } catch (\Exception $e) {

            //Message Error
            $status = 500;
            $response = [
              'errors' => $e->getMessage(),
              'code' => $e->getCode()
            ];
            //Log Error
            \Log::error('Module Icommercegooglepay: Message: '.$e->getMessage());
            \Log::error('Module Icommercegooglepay: Code: '.$e->getCode());
        
        }

        return response()->json($response, $status ?? 200);

    }

}