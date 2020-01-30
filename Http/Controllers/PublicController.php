<?php

namespace Modules\Icommercegooglepay\Http\Controllers;

use Mockery\CountValidator\Exception;

use Modules\Icommercegooglepay\Entities\Googlepayconfig;

use Modules\Core\Http\Controllers\BasePublicController;
use Route;
use Request;
use Log;
use Session;

use Modules\User\Contracts\Authentication;
use Modules\User\Repositories\UserRepository;
use Modules\Icommerce\Repositories\CurrencyRepository;
use Modules\Icommerce\Repositories\ProductRepository;
use Modules\Icommerce\Repositories\OrderRepository;
use Modules\Icommerce\Repositories\Order_ProductRepository;
use Modules\Setting\Contracts\Setting;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request as Requests;
use Illuminate\Support\Facades\Mail;


class PublicController extends BasePublicController
{
   
    private $order;
   
    private $user;
    protected $auth;

    private $currency;
    private $setting;

    public function __construct(Authentication $auth, UserRepository $user, CurrencyRepository $currency,Setting $setting, OrderRepository $order)
    {
        parent::__construct();

        $this->auth = $auth;
        $this->user = $user;
        $this->currency = $currency;
        $this->setting = $setting;
        $this->order = $order;
    }

    
    public function index(Requests $request)
    {       

      
        //$orderID = session('orderID');
        $orderID = 1; // Testing
        $order = $this->order->find($orderID);

        $config = new Googlepayconfig();
        $config = $config->getData();

        $tpl = 'icommercegooglepay::frontend.index';


        return view($tpl, compact('config','order'));

      /*
        if ($request->session()->exists('orderID')) {

          
          
            try{

                $email_from = $this->setting->get('icommerce::from-email');
                $email_to = explode(',',$this->setting->get('icommerce::form-emails'));
                $sender  = $this->setting->get('core::site-name');
              
                $orderID = session('orderID');
                $order = $this->order->find($orderID);

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

                $success_process = icommerce_executePostOrder($orderID,1,$request);

                $userEmail = $order->email;
                $userFirstname = "{$order->first_name} {$order->last_name}";

                $content=[
                    'order'=> $order,
                    'products' => $products,
                    'user' => $userFirstname
                ];

                $msjTheme = "icommerce::email.success_order";
                $msjSubject = trans('icommerce::common.emailSubject.complete').$order->id;
                $msjIntro = trans('icommerce::common.emailIntro.complete');

                
                $mailUser= icommerce_emailSend(['email_from'=>[$email_from],'theme' => $msjTheme,'email_to' => $userEmail,'subject' => $msjSubject, 'sender'=>$sender,'data' => array('title' => $msjSubject,'intro'=> $msjIntro,'content'=>$content)]);

                $mailAdmin = icommerce_emailSend(['email_from'=>[$email_from],'theme' => $msjTheme,'email_to' => $email_to,'subject' => $msjSubject, 'sender'=>$sender,'data' => array('title' => $msjSubject,'intro'=> $msjIntro,'content'=>$content)]);
                


            }catch (\PPConnectionException $ex) {
              \Log::info($e->getMessage());
              return redirect()->route('homepage')
                ->withError(trans('icommerce::common.order_error'));

            }


        }
        
        $user = $this->auth->user();
        if (isset($user) && !empty($user))
          if (!empty($order))
            return redirect()->route('icommerce.orders.show', [$order->id]);
          else
            return redirect()->route('homepage')
              ->withSuccess(trans('icommerce::common.order_success'));
        else
          if (!empty($order))
            return redirect()->route('icommerce.order.showorder', [$order->id, $order->key]);
          else
            return redirect()->route('homepage')
              ->withSuccess(trans('icommerce::common.order_success'));
      */
    }

}