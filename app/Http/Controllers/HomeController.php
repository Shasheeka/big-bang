<?php

namespace App\Http\Controllers;
use App\User;
use App\Tenant;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('id', 1)->with('tenants')->first();
       //return view::make('greeting', array('name' => 'Taylor'));
         return view('home', array('user' => $user));
    }
}
