<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\stagiaires;
use Illuminate\Support\Facades\Validator;
class stagiaireController extends Controller
{
    public function index(){
        $stagiaires = stagiaires::get();
        return response()->json($stagiaires);
    }
    public function getstagiairesById($id){
      $stagiaires=stagiaires::find($id);
      if(is_null($stagiaires)){
        return response()->json(['message' => 'stagiaires Not Found.'], 404);
      }
      return response()->json(stagiaires::find($id));
    }
    public function store(Request $request){
        $validator = Validator::make($request ->all(),[
            'Nom'=> 'required',
            'Prenom' => 'required',
            'Ecole' => 'required',
            'TypeStage'=> 'required',
            'NiveauEtude' => 'required',
            'DateDebut' => 'required|date',
            'DateFin' => 'required|date|after:DateDebut',
            'sujet' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json(['message' => 'error'], 404);
           }
           else{
            $obj = new stagiaires();
            $obj->Nom = $request->Nom;
            $obj->Prenom = $request->Prenom;
            $obj->Ecole = $request->Ecole;
            $obj->TypeStage = $request->TypeStage;
            $obj->NiveauEtude = $request->NiveauEtude;
            $obj->DateDebut = $request->DateDebut;
            $obj->DateFin = $request->DateFin;
            $obj->sujet = $request->sujet;
            $obj->save();
            return response()->json(['message' => 'stagiaires added']);
           }
         }
    public function update(Request $request,$id ){

        $stagiaires = stagiaires::find($id);
        if(is_null($stagiaires)){
            return response()->json(['message' => 'stagiaires Not Found.'], 404);
        }
      $stagiaires->update($request->all());
      return response()->json($stagiaires);
    }
    public function destroy($id){
        $stagiaires = stagiaires::find($id);
        if(is_null($stagiaires)){
            return response()->json(['message'=>'stagiaires not found']);
        }
        $stagiaires->delete();
        return response()->json(['message'=>'stagiaires deleted']);
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
