<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\departements;

use App\Mail\SignupEmail;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Mail\Restarpasword;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'E-mail ou mot de passe incorrect !!!!!'], 401);
        }
        $user = Auth::user();
        if ($user->verif_email == 0) {
            return response()->json(['error' => "Vous n'avez pas accès à la connexion"], 401);
        }
        if ($user->status == 0) {
            return response()->json(['error' => "Vous n'avez pas accès à la connexion"], 401);
        }
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token,
            'type' => 'bearer',
            'expired' => auth()->factory()->getTTL()*60,
            'role' => auth()->user()->role
        ]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cin' => 'required|unique:users,cin|size:8',
            'nom' => 'required|alpha',
            'prenom' => 'required|alpha',
            'tel' => 'required|size:8',
            'email' => 'required|unique:users|email',
            'password' => 'required|confirmed|min:6',
            //  'photo' => 'required|image|mimes:jpeg,png,jpg',
            // 'departement_id' => 'required',
            'adresse' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }

        $obj = new User();
        $obj->cin = $request->cin;
        $obj->nom = $request->nom;
        $obj->prenom = $request->prenom;
        $obj->tel = $request->tel;
        $obj->email = $request->email;
        $obj->password = Hash::make($request->password);
        $obj->status = true;
        $obj->verif_email = false;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture = date('His') . '-' . $filename;
            $file->move(public_path('/public/images'), $picture);
            $obj->photo = "/images/" . $picture;
        }

        $obj->role = $request->role;
        $obj->departement_id = $request->departement_id;
        $obj->adresse = $request->adresse;
        $obj->save();

        $data = User::find($obj->id);
        $details = [
            'title' => ' Activation de de compte dans la site Carthage Solution',
            'body' =>'Suite à votre candidature et entretient nous vous inviton à activer votre compte.
                       Votre Email: ' . $request->email . ' Votre password: ' . $request->password,
            'id' => $data->id,
        ];
        Mail::to($request->email)->send(new SignupEmail($details));
        return response()->json([
            'status' => true,
            'message' => 'Query Successfully Sent',
            'data' => $obj
        ]);
    }
    public function index()
    {
        $users = User::get();
        return response()->json($users);
    }

    public function logout()
    {
        try {

            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);

            return response()->json(
                [
                    'success' => true,
                    'message' => 'User logged out successfully'
                ],
                Response::HTTP_OK
            );
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 404);
        }
    }


    public function getUserById($id)
    {
        $user = user::find($id);
        if (is_null($user)) {
            return response()->json(['error' => 'user Not Found.'], 404);
        }
        return response()->json(user::find($id));
    }

    public function restarpassword($email)
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $random = Str::random(5);
        $out->writeln($random);

        $check_status = User::where('email', '=', $email)->first();
        if ($check_status) {
            $check_status->code = $random;
            $check_status->save();
            $details = [
                'title' => 'Trouver Votre Compte',
                'body' => 'Voter code : ' . $random . ' Pour vérifier votre password il faut ',
                'id' => 'updatepassword/' . $check_status->id,
            ];
            Mail::to($email)->send(new Restarpasword($details));
            return response()->json(['message' => 'nous avons envoyé un code à votre email']);
        } else {
            return response()->json(['error' => 'email Not Exist'], 401);
        }
    }
    public function updatepassword(Request $request, $id)
    {
        $user = User::find($request->id);
        if (is_null($user)) {
            return response()->json(['error' => 'User not found']);
        }
        if ($user->code == $request->code) {
            if ($user->password) {
                $request->replace($request->all());
                if ($request->new_password != null) {
                    if ($request->new_password != $request->password_confirm) {
                        return response()->json(['error' => 'Le nouveau mot de passe et la confirmation du mot de passe ne correspondent pas'], 400);
                    }
                    $user->password = Hash::make($request->new_password);
                    $user->code = NULL;
                }
            }

            $user->save();

            return response()->json(['message' => 'Updated successfully'], 200);
        } else {
            return response()->json(['error' => 'Code incorrect'], 404);
        }
    }

    public function verifMail($id)
    {
        $data = User::find($id);

        if ($data->verif_email == 0) {
            $data->verif_email = 1;
            $dt = new DateTime();
            $dt->format('Y-m-d H:i:s');
            $dt = new DateTime();
            $dt->format('Y-m-d H:i:s');
            $data->email_verified_at = $dt;
            $data->save();

            return response()->json(['message' => 'Account verified']);
        } else {
            return response()->json(['error' => 'Account NOT verified'], 404);
        }
    }


}
