<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/';
    protected $loginPath = '/';
    protected $username = 'uid';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
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
            'uid' => 'required|regex:"^[a-zA-Z]{1}[1-2]{1}[0-9]{8}$"|unique:users',
            'name' => 'required|max:255',
            'password' => 'required|confirmed',
            'unit' => 'required',
            'rank' => 'required',
            'title' => 'required|max:255',
            'serviceType' => 'required',
        ])->setAttributeNames([
            'uid' => '帳號（身分證字號）',
            'name' => '中文姓名',
            'password' => '登入密碼',
            'unit' => '所屬單位',
            'rank' => '階級',
            'title' => '職稱',
            'serviceType' => '役別',
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
        return User::create([
            'uid' => $data['uid'],
            'name' => $data['name'],
            'unit' => $data['unit'],
            'rank' => $data['rank'],
            'title' => $data['title'],
            'serviceType' => $data['serviceType'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
