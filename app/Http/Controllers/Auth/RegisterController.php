<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendWelcomeEmail;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ];

        $message = [
            'password.regex' => 'The password must be alphanumeric with at least 8 characters.',
            'password.min' => 'The password must be alphanumeric with at least 8 characters.',
        ];

        return Validator::make($data, $rules, $message);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ];

        $user = User::create($userData);
        if ($user) {
            dispatch(new SendWelcomeEmail($userData));
        }

        return $user;
    }

    /**
     * [checkUserName description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function checkUserName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|unique:users',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'msg' => 'Sorry! This user name Not Available',
            ];
        } else {
            $response = [
                'success' => true,
                'msg' => 'Congratulation! This user name Available',
            ];
        }
        return response()->json($response, 200);
    }

    /**
     * [checkEmail description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            $response = [
                'success' => false,
                'msg' => 'Sorry! This email address Not Available',
            ];
        } else {
            $response = [
                'success' => true,
                'msg' => 'Congratulation! This email address Available',
            ];
        }
        return response()->json($response, 200);
    }
}
