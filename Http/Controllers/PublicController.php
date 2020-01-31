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

      //if ($request->session()->exists('orderID')) {

        //$orderID = session('orderID');
        $orderID = 1; // Testing
        $order = $this->order->find($orderID);

        $config = new Googlepayconfig();
        $config = $config->getData();

        $allowedCards = config('asgard.icommercegooglepay.config.allowedCards');
        $allowedCardsAuth = config('asgard.icommercegooglepay.config.allowedCardsAuth');

        $tpl = 'icommercegooglepay::frontend.index';

        return view($tpl, compact('config','order','allowedCards','allowedCardsAuth'));
      
      /*
      }else{
        return redirect()->route('homepage');
      }
      */

    }

}