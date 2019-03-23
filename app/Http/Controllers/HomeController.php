<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Routing\Controller;

class HomeController extends Controller
{
    /**
     * @var string
     */
    public $prefix = 'home';

    /**
     * @param  Router  $router
     * @return void
     */
    public function routes(Router $router)
    {
        $router->get('/', [__CLASS__, 'index']);
    }

    /**
     * @param  Request  $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return view('welcome');
    }
}
