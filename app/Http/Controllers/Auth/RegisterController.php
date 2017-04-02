<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Tenant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'fname' => 'required|max:255',
            'lname' => 'required|max:255',
            'store_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

       $user =   User::create([
            'fname'      => $data['fname'],
            'lname'      => $data['lname'],
            'store_name' => $data['store_name'],
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
        ]);

         $tenant = Tenant::create([
            'user_id'    => $user->id,
            'subdomain'  => 'tenant'.$user->id,
            'dbname'     => 'tenant'.$user->id,
            'dbhost'     => 'localhost',
            'dbusername' => 'root',
            'dbpassword' => 'root',
            'dbport'     => '3306',
            'url'        => 'http://'.'tenant'.$user->id.'.bigbang.com',
            'status'     => 1,
        ]);

        return  $user;
    }
}
