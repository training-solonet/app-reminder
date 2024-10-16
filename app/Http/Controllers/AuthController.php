<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    function index(Request $request){
        $token =  $request->get('token');

        $response = Http::withToken($token)->get('http://127.0.0.1:8000/api/auth/user-profile');

        if($response['status'] == 'success'){

            $request->session()->put('id', $response['id']);
            $request->session()->put('name', $response['name']);
            $request->session()->put('role', $response['role']);

            $user = array(
                'user'      => $response['name'],
                'ip'        => $request->ip(),
                'browser'   => $request->header('User-Agent')
            );

            // Notif Discord
            NotificationDiscord('INFO LOGIN : ', json_encode($user));

            return redirect('/');

        }else{
            return redirect('http://127.0.0.1:8000/');
        }

    }

    function logout(Request $request){
        //delete all session
        $request->session()->flush();

        return redirect('http://127.0.0.1:8000/logout');

    }
}
