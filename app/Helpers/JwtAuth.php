<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use DB;
use App\User;

/**
 *
 */
class JwtAuth
{

  public $key;

  public function __construct(){
    $this->key = 'pajaro-api9@';
  }

  public function signUp($email, $password, $getToken = null)
  {
    // code...
    $user = User::where(['email'=>$email,'password'=> $password])->first();

    $signUp = false;
    if (isset($user)) {
      // code...
      $signUp = true;
    }

    if ($signUp) {
      // code...
      $token = [
        'sub' => $user->id,
        'email' => $user->email,
        'name' => $user->name,
        'iat' => time(),
        'exp' => time() + (24*60*60)
      ];

      $jwt = JWT::encode($token, $this->key,'HS256');

      $decode = JWT::decode($jwt, $this->key,['HS256']);

      if (is_null($getToken)) {
        // code...
        return $jwt;
      }else {
        return $decode;
      }

    }else {
      // code...
      return ['status' => 'error','message'=>'login ha fallado'];
    }
  }

  public function checkToken($jwt, $getIdentity = false)
  {
    // code...
    $auth = false;

    try {
      $decode = JWT::decode($jwt,$this->key,['HS256']);

    } catch (\UnexpectedValueException $e) {
      $auth = false;
    } catch (\DomainException $e){
      $auth = false;
    }

    if (isset($decode) && is_object($decode) && isset($decode->sub)) {
      // code...
      $auth = true;
    }else {
      $auth = false;
    }

    if ($getIdentity) {
      // code...
      return $decode;
    }

    return $auth;
  }
}
