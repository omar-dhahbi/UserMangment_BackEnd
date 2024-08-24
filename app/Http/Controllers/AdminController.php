<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Mail\Users;
use Illuminate\Validation\Rule;

use App\Models\clients;
use App\Models\congé;
use App\Models\departements;
use App\Models\projets;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function userList()
    {
        $users = DB::table('users')
            ->join('departements', 'departements.id', '=', 'users.departement_id')
            ->select('users.*', 'departements.Titre')
            ->get();
        return response()->json($users);
    }

    public function getUserById($id)
    {
        $user = DB::table('users')
            ->join('departements', 'departements.id', '=', 'users.departement_id')
            ->select('users.*', 'departements.Titre')
            ->where('users.id', $id)
            ->first();

        if (is_null($user)) {
            return response()->json(['error' => "Utilisateur n'est pas utilisé"], 404);
        }

        return response()->json($user);
    }
    public function getIdUser($id)
    {
        $user = user::find($id);
        if (is_null($user)) {
            return response()->json(['error' => 'user Not Found.'], 404);
        }
        return response()->json(user::find($id));
    }
    public function user()
    {
        $user = User::count();
        return response()->json(['count' => $user]);
    }
    public function departements()
    {
        $departements = departements::count();
        return response()->json(['count' => $departements]);
    }
    public function projets()
    {
        $projets = projets::count();
        return response()->json(['count' => $projets]);
    }
    public function clients()
    {
        $clients = clients::count();
        return response()->json(['count' => $clients]);
    }
    public function updatePassword(Request $request, $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['error' => 'User not found'], 404);
        }
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Mot de passe actuel incorrect'], 404);
        }
        if ($request->new_password == $request->current_password) {
            return response()->json(['error' => 'Le nouveau mot de passe ne peut pas être le même que le mot de passe actuel'], 404);
        }
        if ($request->new_password != $request->password_confirm) {
            return response()->json(['error' => 'Le nouveau mot de passe et la confirmation du mot de passe ne correspondent pas'], 404);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json(['message' => 'Password updated successfully'], 200);
    }



    public function activeAccount($id)
    {
        $user = User::find($id);
        $user->status = true;
        $user->save();
        return response()->json(['message' => 'User banned successfully']);
    }
    public function AccounNotActive($id)
    {
        $user = User::find($id);
        $user->status = false;
        $user->save();
        return response()->json(['message' => 'User unbanned successfully']);
    }
    public function UpdateUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cin' => [
                'required',
                'size:8',
                Rule::unique('users')->ignore($id),

            ],
            'nom' => 'required',
            'prenom' => 'required',
            'tel' => 'required|size:8',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id),
            ],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->cin = $request->cin;
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->tel = $request->tel;
        // if ($user->email !== $request->input('email')) {
        //     $user->email = $request->input('email');
        //     $user->verif_email = 0;
        //     $details = [
        //         'title' => 'Vérification de votre Email',
        //         'body' => 'Pour vérifier votre Email il faut ',
        //         'id' => $user->id,
        //     ];
        //     Mail::to($request->input('email'))->send(new update($details));
        // }
        $user->email = $request->email;
        $user->departement_id = $request->departement_id;
        $user->adresse = $request->adresse;



        if ($request->hasFile('photo')) {
            $file      = $request->file('photo');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = date('His') . '-' . $filename;
            //move image to public/img folder
            $file->move(public_path('/public/images'), $picture);
            $user->photo  = "/images/" . $picture;
        }
        $user->save();
        return response()->json(['user' => $user], 200);
    }


    public function search(Request $request)
    {
        $query = $request->input('search');

        $users = User::where('cin', 'like', "%$query%")
            ->orWhere('nom', 'like', "%$query%")
            ->orWhere('prenom', 'like', "%$query%")
            ->orWhere('tel', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->orWhere('RaisonSociale', 'like', "%$query%")
            ->orWhere('role', 'like', "%$query%")
            ->get();
        return response()->json($users);
    }
        public function getcongéAccepted()
        {
            $congéAccepted = congé::where('congés.status', '=', 'accepter')
                ->join('users', 'users.id', '=', 'congés.user_id')
                ->select('congés.*', 'users.cin', 'users.nom', 'users.prenom')
                ->get();
            return response()->json($congéAccepted);
        }
        public function getcongéNotAccepted()
        {
            $congéNotAccepted = congé::where('congés.status', '=', 'refuser')
                ->join('users', 'users.id', '=', 'congés.user_id')
                ->select('congés.*', 'users.cin', 'users.nom', 'users.prenom')
                ->get();
            return response()->json($congéNotAccepted);
        }


        public function accepter($id)
        {
            $conge = congé::find($id);
            $conge->status = 'accepter';
            if ($conge->type == "simple") {
                $user = User::find($conge->user_id);
                $nbj = $user->NbJourConge -= $conge->NbrJour;
                if ($nbj < 0 || $nbj > 45) {
                    return response()->json(['error' => 'demande non effectuée, nombre de jours demandés > 45'], 404);
                }
                $user->update();
            }

            $conge->save();

            return response()->json($conge);
        }

        public function refuser($id)
        {
            $conge = congé::find($id);
            $conge->status = 'refuser';
            $conge->save();
            return response()->json($conge);
        }

    public function getcongéById($id)
    {
        $conge = congé::find($id);
        if (is_null($conge)) {
            return response()->json(['message' => 'congé Not Found.'], 404);
        }
        $conge = DB::table('congés')
            ->join('users', 'users.id', '=', 'congés.user_id')
            ->select('congés.*', 'users.cin', 'users.nom', 'users.prenom', 'users.photo', 'users.tel', 'users.email')
            ->where('congés.id', $id)
            ->first();
        return response()->json($conge);
    }
    public function congéList()
    {
        $congés = DB::table('congés')
            ->join('users', 'users.id', '=', 'congés.user_id')
            ->select('congés.*', 'users.cin', 'users.nom', 'users.prenom')
            ->get();
        return response()->json($congés);
    }

    public function getcongéAttente()
    {
        $congéAttente = congé::where('congés.status', '=', 'attente')
            ->join('users', 'users.id', '=', 'congés.user_id')
            ->select('congés.*', 'users.cin', 'users.nom', 'users.prenom')
            ->get();
        return response()->json($congéAttente);
    }
    public function searchConge(Request $request)
    {
        $query = $request->input('search');
        $projets = congé::where('user_id', 'like', "%$query%")
            ->orWhere('type', 'like', "%$query%")
            ->orWhere('DateDebut', 'like', "%$query%")
            ->orWhere('DateFin', 'like', "%$query%")
            ->orWhere('CauseConge', 'like', "%$query%")


            ->get();
        return response()->json($projets);
    }
}
