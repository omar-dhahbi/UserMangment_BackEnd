<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\stagiaires;
use Illuminate\Support\Facades\Validator;

class stagiaireController extends Controller
{
    public function index()
    {
        $stagiaires = stagiaires::get();
        return response()->json($stagiaires);
    }
    public function getstagiairesById($id)
    {
        $stagiaires = stagiaires::find($id);
        if (is_null($stagiaires)) {
            return response()->json(['message' => 'stagiaires Not Found.'], 404);
        }
        return response()->json(stagiaires::find($id));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Cin' => 'required|size:8',
            'Nom' => 'required|min:3|alpha',
            'Prenom' => 'required|min:3|alpha',
            'telephone' => 'required',
            'Email' => 'required|email',
            'Ecole' => 'required|min:3',
            'TypeStage' => 'required',
            'NiveauEtude' => 'required',
            'class' => 'required',
            'DateDebut' => 'required|date',
            'DateFin' => 'required|date|after:DateDebut',
            'sujet'=>'required|min:8',
            'DescriptionSujet' => 'required|min:12',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        } else {
            $obj = new stagiaires();
            $obj->Cin = $request->Cin;
            $obj->Nom = $request->Nom;
            $obj->Prenom = $request->Prenom;
            $obj->telephone = $request->telephone;
            $obj->Email = $request->Email;
            $obj->Ecole = $request->Ecole;
            $obj->TypeStage = $request->TypeStage;
            $obj->NiveauEtude = $request->NiveauEtude;
            $obj->class = $request->class;
            $obj->DateDebut = $request->DateDebut;
            $obj->DateFin = $request->DateFin;
            $obj->sujet = $request->sujet;
            $obj->DescriptionSujet = $request->DescriptionSujet;
            $obj->save();
            return response()->json(['message' => 'stagiaires added']);
        }
    }
    public function update(Request $request, $id)
    {

        $stagiaires = stagiaires::find($id);
        if (is_null($stagiaires)) {
            return response()->json(['message' => 'stagiaires Not Found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'Cin' => 'required|size:8',
            'Nom' => 'required|min:3|string',
            'Prenom' => 'required|min:3|string',
            'telephone' => 'required',
            'Email' => 'required|email',
            'Ecole' => 'required|min:3',
            'DateDebut' => 'required|date',
            'DateFin' => 'required|date|after:DateDebut',
            'sujet' => 'required|min:8',
            'DescriptionSujet' => 'required|min:12',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }
        $stagiaires->Cin = $request->Cin;
        $stagiaires->Nom = $request->Nom;
        $stagiaires->Prenom = $request->Prenom;
        $stagiaires->telephone = $request->telephone;
        $stagiaires->Email = $request->Email;
        $stagiaires->Ecole = $request->Ecole;
        $stagiaires->TypeStage = $request->TypeStage;
        $stagiaires->NiveauEtude = $request->NiveauEtude;
        $stagiaires->class = $request->class;
        $stagiaires->DateDebut = $request->DateDebut;
        $stagiaires->DateFin = $request->DateFin;
        $stagiaires->sujet = $request->sujet;
        $stagiaires->DescriptionSujet = $request->DescriptionSujet;
        $stagiaires->update();
        return response()->json($stagiaires);
    }
    public function destroy($id)
    {
        $stagiaires = stagiaires::find($id);
        if (is_null($stagiaires)) {
            return response()->json(['message' => 'stagiaires not found']);
        }
        $stagiaires->delete();
        return response()->json(['message' => 'stagiaires deleted']);
    }
    public function search(Request $request)
    {
        $query = $request->input('search');

        $departements = stagiaires::where('Nom', 'like', "%$query%")
            ->orWhere('Prenom', 'like', "%$query%")
            ->orWhere('Ecole', 'like', "%$query%")
            ->orWhere('TypeStage', 'like', "%$query%")
            ->orWhere('NiveauEtude', 'like', "%$query%")
            ->orWhere('sujet', 'like', "%$query%")
            ->get();
        return response()->json($departements);
    }
}
