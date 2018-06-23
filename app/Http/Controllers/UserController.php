<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;
use DB;

use App\Helpers\JwtAuth as Jwt;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
      // code...
      $json = $request->input('json',null);
      $params = json_decode($json);

      $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
      $name = (!is_null($json) && isset($params->name)) ? $params->name : null;
      $role = 'ROLE_USER';
      $password = (!is_null($json) && isset($params->password)) ? $params->password : null;

      if (!is_null($email) && !is_null($password) && !is_null($name)) {
        // code...
        $isset_user = User::where('email',$email)->first();
        if (!isset($isset_user)) {
          // code...
          $user = User::create([
            'name' => $name,
            'role' => $role,
            'email' => $email,
            'password' => hash('sha256',$password),
          ]);

          $data = ['status' => 'correct', 'code' => 200, 'message'=>'usuario registrado','user'=> $user->email];
        }else {
          // code...
          $data = ['status' => 'error', 'code' => 500, 'message'=>'usuario ya existe'];
        }



      }else {
        // code...
        $data = ['status' => 'error', 'code' => 400, 'message'=>'faltan parametros obligatorios'];
      }

      return response()->json($data,$data['code']);
    }

    public function login(Request $request)
    {
      // code...
      $jwt = new Jwt();

      $json = $request->input('json',null);

      $params = json_decode($json);

      $email = (!is_null($json) && isset($params->email)) ? $params->email : null;
      $password = (!is_null($json) && isset($params->password)) ? $params->password : null;
      $getToken = (!is_null($json) && isset($params->token)) ? $params->token : null;

      $pwd = hash('sha256',$password);
      //$pwd = $password;

      if (!is_null($email) && !is_null($pwd) && (is_null($getToken) || $getToken == 'false')) {
        // code...
        $signUp = $jwt->signUp($email,$pwd);

      }elseif (!is_null($getToken)) {
        $signUp = $jwt->signUp($email,$pwd,$getToken);

      }else{
        $signUp = ['status' => 'error','message' => 'envia tus datos por post'];
      }

      return response()->json($signUp,200);
    }
}
