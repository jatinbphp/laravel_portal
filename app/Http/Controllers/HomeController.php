<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use View;

class HomeController extends Controller
{
    /**
     * [__construct description]
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewPath = "home.";
    }

    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
        View::share("title", "Account Info");
        return view($this->viewPath . 'index');
    }

    /**
     * [getChangePassword description]
     * @return [type] [description]
     */
    public function getChangePassword()
    {
        View::share("title", "Change Password");
        return View($this->viewPath . 'password');
    }

    /**
     * [postChangePassword description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function postChangePassword(Request $request)
    {
        $id = $this->userId;
        $data = $request->all();

        Validator::extend('check_password', function ($attribute, $value, $parameters) {
            $user = User::find(Auth::user()->id);
            $old_password = \Hash::check($value, $user->password);
            if ($old_password == true) {
                return true;
            } else {
                return false;
            }
        });

        $rules = [
            'old_password' => 'required|check_password',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'password_confirmation' => 'required',
        ];

        $messages = [
            'password.confirmed' => 'password not match',
            'old_password.check_password' => 'The old password field is wrong',
            'password.regex' => 'The password must be alphanumeric with at least 8 characters.',
            'password.min' => 'The password must be alphanumeric with at least 8 characters.',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())
                ->with('message', 'Some thinks is going wrong.')
                ->with('message_type', 'danger');
        }

        $user = User::find($id);
        $user->Password = \hash::make($request->password);
        $user->save();

        return redirect()->back()->with('message', 'Password Change Successfully.')
            ->with('message_type', 'success');
    }

    /**
     * [profile description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function profile(Request $request)
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required'],
            'street_number' => ['required', 'string', 'max:255'],
            'street_name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'zipcode' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'user_photo' => ['mimes:jpeg,jpg,png', 'max:4096'],
        ];

        $messages = [

        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator)
                ->with('message_type', 'danger')
                ->with('message', 'There were some error try again');
        }

        $userPhoto = null;
        $user = User::find($this->userId);
        $userPhoto = $user->user_photo;

        if ($request->file('user_photo')) {
            $userPhoto = sha1(microtime()) . '.' . $request->file('user_photo')->getClientOriginalExtension();
            $request->file('user_photo')->move(public_path('user_photo'), $userPhoto);
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->street_number = $request->street_number;
        $user->street_name = $request->street_name;
        $user->city = $request->city;
        $user->zipcode = $request->zipcode;
        $user->country = $request->country;

        if (isset($userPhoto)) {
            $user->user_photo = $userPhoto;
        }

        $user->save();

        return back()->with('message_type', 'success')
            ->with('message', 'Profile update Successfully');
    }
}
