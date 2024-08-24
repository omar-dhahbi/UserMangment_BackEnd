<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\congé;
use App\Models\reunions;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;



class EmployééController extends Controller
{


    public function updatePassword(Request $request, $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['error' => 'User not found'], 404);
        }
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['error' => 'Incorrect current password'], 404);
        }
        if ($request->new_password == $request->current_password) {
            return response()->json(['error' => 'New password cannot be the same as the current password'], 404);
        }
        if ($request->new_password != $request->password_confirm) {
            return response()->json(['error' => 'New password and password confirm do not match'], 404);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return response()->json(['message' => 'Password updated successfully'], 200);
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
    public function demandeConge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required',
            'DateDebut' => 'required|date',
            'DateFin' => 'required|date|after:DateDebut',
            'NbrJour' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $lastRequest = congé::where('user_id', $request->user_id)
            ->where('created_at', '>=', now()->subDay())
            ->first();
        if (!empty($lastRequest)) {
            return response()->json(['error' => 'Vous avez déjà soumis une demande de congé au cours des dernières 24 heures.'], 404);
        }


        if ($request->type === "simple") {
            $user = User::find($request->user_id);
            $nbJourConge = $user->NbJourConge;
            $nbrjour = $nbJourConge - $request->NbrJour;

            if ($nbrjour < 0 || $nbrjour > 45) {
                return response()->json(['error' => 'Demande non effectuée, nombre de jours demandés > 45'], 404);
            }
            // $user->save();
        }
        $conge = new congé();
        $conge->user_id = $request->user_id;
        $conge->type = $request->type;
        $conge->DateDebut = $request->DateDebut;
        $conge->DateFin = $request->DateFin;
        $conge->NbrJour = $request->NbrJour;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture = date('His') . '-' . $filename;
            $file->move(public_path('/public/images'), $picture);
            $conge->photo = "/images/" . $picture;
        }
        $conge->CauseConge = $request->CauseConge;
        $conge->status = 'attente';
        $conge->save();

        return response()->json(['message' => 'Demande de congé effectuée avec succès.'], 201);
    }

    public function getContratByUserId($userId)
{
    $contrat = DB::table('contrats')
        ->join('users', 'users.id', '=', 'contrats.user_id')
        ->select('contrats.*', 'users.nom', 'users.prenom')
        ->where('contrats.user_id', $userId)
        ->first();

    if (is_null($contrat)) {
        return response()->json(['error' => "L'utilisateur n'existe pas"], 404);
    }

    return response()->json($contrat);
}

    public function getResultByUserId($user_id)
    {
        $conges = congé::where('user_id', $user_id)->get();
        if (is_null($conges)) {
            return response()->json(['error' => "Utilisateur n'est pas utulisé"], 404);
        } else {
            return response()->json(["conges"=>$conges], 200);
        }
    }
    public function getReunionByUser($id)
    {
        $reunions = reunions::join('reunion_departemens', 'reunions.id', '=', 'reunion_departemens.reunion_id')
            ->join('departements', 'reunion_departemens.departement_id', '=', 'departements.id')
            ->join('users', 'departements.id', '=', 'users.departement_id')
            ->select('reunions.*', 'departements.Titre')
            ->where('users.id', '=', $id)
            ->get();

        return $reunions;
    }
    public function searchConge(Request $request){
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
