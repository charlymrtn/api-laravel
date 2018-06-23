<?php

namespace App\Http\Controllers;

use App\Car;
use Illuminate\Http\Request;

use Validator;

use App\Helpers\JwtAuth as Jwt;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Car::all()->load('user');

        return response()->json(['cars'=>$cars,'status'=>'success'],200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $hash = $request->header('Authorization',null);

        $jwt = new Jwt();
        $check = $jwt->checkToken($hash);

        if ($check) {
          // code...
            $json = $request->input('json',null);
            $params = json_decode($json);
            $params_array = json_decode($json,true);

            $user = $jwt->checkToken($hash, true);

            $validator = Validator::make($params_array, [
              'title' => 'required|min:5',
              'description' => 'required',
              'price' => 'required|integer',
              'status' => 'required'
            ]);

            if ($validator->fails()) {
              // code...
              return response()->json($validator->errors(),400);
            }

            $car = Car::create([
              'user_id' => $user->sub,
              'title' => $params->title,
              'description' => $params->description,
              'price' => $params->price,
              'status' => $params->status,
            ]);
            $data = ['car'=>$car,'status'=> 'success','code'=>200];

        }else {
          // code...
          $data = ['message'=>'usuario no autentificado','status'=> 'erro','code'=>500];
        }

        return response()->json($data,$data['code']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $car = Car::find($id)->load('user');

        return response()->json(['status'=>'success','car'=> $car],200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $hash = $request->header('Authorization',null);

      $jwt = new Jwt();
      $check = $jwt->checkToken($hash);

      if ($check) {
        // code...
          $json = $request->input('json',null);
          $params = json_decode($json);
          $params_array = json_decode($json,true);

          $validator = Validator::make($params_array, [
            'title' => 'required|min:5',
            'description' => 'required',
            'price' => 'required|integer',
            'status' => 'required'
          ]);

          if ($validator->fails()) {
            // code...
            return response()->json($validator->errors(),400);
          }

          $car = Car::find($id)->update($params_array);
          $data = ['car'=>$params,'status'=> 'success','code'=>200];

      }else {
        // code...
        $data = ['message'=>'usuario no autentificado','status'=> 'erro','code'=>500];
      }

      return response()->json($data,$data['code']);
    }

    public function destroy(Request $request, $id)
    {
      $hash = $request->header('Authorization',null);

      $jwt = new Jwt();
      $check = $jwt->checkToken($hash);

      if ($check) {

        $car = Car::find($id);
        if (isset($car)) {
          // code...
          $car->delete();
          $data = ['car'=>$car,'status'=> 'success','code'=>200];
        }else {
          $data = ['message'=>'no existe el vehiculo','status'=> 'error','code'=>500];
        }

      }else {
        $data = ['message'=>'usuario no autentificado','status'=> 'error','code'=>500];
      }

      return response()->json($data,$data['code']);
    }


}
