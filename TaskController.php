<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function register(Request $request){
       $validator = Validator::make($request->all(),[
          'name' => 'required',
          'email' => 'required',
          'password' => 'required'

       ]);
       if($validator->fails()){
        $response = [
            'success' => false,
            'message' => $validator->errors()
        ];
        return response()->json($response, 400);
       }
       $input = $request->all();
       $input['password'] = bcrypt($input['password']);
       $user = User::create($input);

       $success['token'] = $user->createToken('MyApp')->plainTextToken;
       $success['name'] = $user->name;

       $response = [
         'success' => true,
         'data' => $success,
         'message' => 'User register successfully'
       ];
       
       return response()->json($response,200);
    }

    public function login(Request $request){
    if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
       $user = Auth::user();

       $success['token'] = $user->createToken('MyApp')->plainTextToken;
       $success['name'] = $user->name;

       $response = [
         'success' => true,
         'data' => $success,
         'message' => 'User login successfully'
       ];
       return response()->json($response,200);
    }else{
        $response = [
            'success' => false,
            'message' => 'Unauthorised'
        ];
        return response()->json($response);
    }
}
}
