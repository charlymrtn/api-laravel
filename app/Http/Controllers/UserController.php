<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\User;
use DB;

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
            'password' => Hash::make($password),
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
      echo 'accion login'; die();
    }
}
