<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Arr;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
    }

    // protected function validator(array $data)
    // {
    //     return Validator::make($data, [
    //         'username' => ['unique:users'],
    //         'first_name' => ['required', 'string', 'max:255'],
    //         'middle_name' => ['required', 'string', 'max:255'],
    //         'last_name' => ['required', 'string', 'max:255'],
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ]);
    // }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->validator($request->all())->validate();
        }

        $this->create($request->all());

        if(Arr::exists($request, 'role')){
            return redirect(route('users')); // Change this route to your needs
        }
        else{
            return redirect(route('client')); // Change this route to your needs
        }
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        // dd($data);
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255','unique:users'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        if(Arr::exists($data, 'role')){
            return User::create([
                'username' => $data['username'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'suffix' => $data['suffix'],
                'role' => $data['role'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        }
        else{
            return User::create([
                'username' => $data['username'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'suffix' => $data['suffix'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
        }
        
    }
}
