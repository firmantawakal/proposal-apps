<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Position;
use Session;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('home');
        }else{
            return view('login');
        }
    }

    public function actionlogin(Request $request)
    {
        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        $authenticated = Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'status' => function ($query) {
                $query->where('status', 1);
            }
        ]);

        if ($authenticated) {
            $user = Auth::User();
            Session::put('user', $user);

            $positions = Position::with('department')->where('id',$user->position_id)->first();
            Session::put('position', $positions);

            $dt_lvl=array();

            foreach ($positions->level as $levl) {
                $dt_lvl[] = $levl->id;
            }
            Session::put('level',$dt_lvl);

            return redirect('home');
        }else{
            Session::flash('error', 'Email atau Password Salah');
            return redirect('/');
        }
    }

    public function actionlogout()
    {
        Auth::logout();
        return redirect('/');
    }
}
