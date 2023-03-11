<?php

namespace App\Http\Controllers;

use App\Mail\Restarpasword;
use App\Mail\SignupEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;




class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Email Or password Not Found!!!!!'], 401);
        }
        return $this->createNewToken($token);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cin' => 'required|unique:users,cin',
            'nom' => 'required',
            'prenom' => 'required',
            'tel' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed|min:6',
            'photo' => 'required|image',
            'RaisonSociale' => 'required',
        ]);

        if ($validator->fails()) {
            $arr = array('status' => false, 'message' => $validator->errors()->all());
        } else {
            $obj = new User();
            $obj->cin = $request->cin;
            $obj->nom = $request->nom;
            $obj->prenom = $request->prenom;
            $obj->tel = $request->tel;
            $obj->email = $request->email;
            $obj->password = Hash::make($request->password);
            if ($request->hasFile('photo')) {
                $file      = $request->file('photo');
                $filename  = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $picture   = date('His') . '-' . $filename;
                //move image to public/img folder
                $file->move(public_path('images'), $picture);
                $obj->photo  = "/images/" . $picture;
            }
            $obj->RaisonSociale = $request->RaisonSociale;
            $obj->save();

            // $data = User::find($obj->id);
            // $details = [
            //     'title' => 'Vérification de votre compte',
            //     'body' => 'Pour vérifier votre compte il faut ',
            //     'id' => $data->id,
            // ];
            // Mail::to($request->email)->send(new SignupEmail($details));

            $arr = array('status' => true, 'message' => ' Query Successfully Send');
        }

        echo json_encode($arr);
        // return response()->json([
        //     'message' => 'User successfully registered',
        //     'user' => $obj
        // ], 201);

    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }
    
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }


}
