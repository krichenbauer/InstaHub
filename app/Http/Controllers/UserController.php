<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Input;
use Config;
use Auth;
use Schema;

use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function destroy($username)
    {
        $user = User::where('username', $username)->first();
        $user->delete();
        flash('User deleted')->success();
        return redirect('home');
    }

    public function getPassword()
    {
        return view('auth.passwords.set');
    }

    public function postPassword(\Illuminate\Http\Request $request)
    {
        $this->validate(request(), [
            'password' => 'required|min:5',
            'password_confirmation' => 'same:password',
        ]);

        if (Hash::check($request->input('old_password'), Auth::user()->getAuthPassword())) {
            if (!strcmp($request->input('password'), $request->input('password_confirmation'))) {
                if (strlen($request->input('password'))>4) {
                    Auth::user()->password = bcrypt($request->input('password'));
                    Auth::user()->save();
                    flash('Password changed', 'success');
                } else {
                    flash('Password minlength is 5 characters', 'warning');
                }
                
            } else {
                flash('New password does not match with confirmed password', 'danger');
            }

        }
        else {
            flash('Wrong password', 'danger');
        }
        
        return redirect('/home');
    }

    public function getNewPassword($id)
    {
        $this->middleware('role:dba');

        //generate id
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $pw = ''; $i < 6; $i++) {
            $index = rand(0, $count - 1);
            $pw .= mb_substr($chars, $index, 1);
        }

        $user = User::find($id);

        $user->password = bcrypt($pw);
        $user->save();

        return response()->json([
            'password' => $pw
        ]);

    }

}