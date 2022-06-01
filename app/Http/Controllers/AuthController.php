<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){

        $credenciais = $request->all(['email','password']);

        //autenticação (email e senha)

        $token = auth('api')->attempt($credenciais);

        if($token){
            //usuario autenticado com sucesso

            return response()->json(['token' => $token]);

        } else{//erro de usuario e senha

            return response()->json(['erro' => 'Usuario ou senha inválidos'], 403);

        }

    }

    public function logout(){

        auth('api')->logout();
        return response()->json(['msg' => 'logout realizado com sucesso']);
    }

    public function refresh(){

        $token = auth('api')->refresh();

        return response()->json(['token' => $token]);
    }

    public function me(){

        return response()->json(auth()->user());

    }

}
