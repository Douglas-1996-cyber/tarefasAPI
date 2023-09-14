<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

 
    public function login(Request $request){
        $credenciais = $request->all(['email','password']);

        $token = auth('api')->attempt($credenciais);
        if($token){
           return response()->json(['token'=>$token]);
        }else{
            return response()->json(['erro'=>'Usuário ou senha inválido!',403]);
        }
    }
    public function logout(){
        auth('api')->logout();
        return response()->json(['msg'=>'Logout realizado com sucesso']);
    }
    public function refresh(){
        $token = auth('api')->refresh();
        return response()->json(['token'=>$token]);
    }
    public function me(){
        $user = User::with('tarefas')->find(auth()->user()->id);
        return response()->json($user);
    }
    public function alterarSenha(Request $request){
       $hashedPassword =  auth()->user()->password;
       $id_usuario = auth()->user()->id; 
        if (Hash::check($request->senha_antiga, $hashedPassword)) {
           
            $user = User::find($id_usuario);
            $user->update(
            [
                'password' => Hash::make($request->senha_nova),
            ]
            ); 
            $this->logout();
         return response()->json(['msg'=>'Senha alterada com sucesso, realize uma nova sessão!']); 
        }else{
            return response()->json(['msg'=>'Senha incorreta',400]);
        }
      
   
    }
}
