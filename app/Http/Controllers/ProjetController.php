<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\projets;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProjetController extends Controller
{
    public function index()
    {
        $projets = DB::table('projets')
        ->join('users', 'users.id', '=', 'projets.user_id')
        ->join('clients', 'clients.id', '=', 'projets.client_id')
        ->select('projets.*','users.Nom', 'users.Prenom',  'clients.RaisonSociale')
        ->get();
        return response()->json($projets);
    }
    public function getProjetById($id){
        $projets=projets::find($id);
        if(is_null($projets)){
          return response()->json(['message' => 'projet Not Found.'], 404);
        }
        return response()->json(projets::find($id));
    }
    public function store(Request $request)
    {
          $validator = Validator::make($request ->all(),[
            'user_id'=>'required',
            'NomProjets'=>'required',
            'client_id'=>'required',
        ]);
       if ($validator->fails()){
        $arr = array('status' =>'false', 'message'=>$validator->errors()->all());
       }else{
         $obj = new projets();
         $obj->user_id = $request->user_id;
         $obj->NomProjets = $request->NomProjets;
         $obj->client_id = $request->client_id;
         $obj->save();
         $arr = array('status' => true, 'message'=>' Query Successfully Send');
       }
       echo json_encode($arr);
    } public function update(Request $request, $id)
    {
        $projets = projets::find($id);
        if(is_null($projets)){
            return response()->json(['message'=>'projet not found']);
        }
        $projets->update($request->all());
        return response($projets);
    }
    public function destroy($id)
    {
        $projets = projets::find($id);
        if(is_null($projets)){
            return response()->json(['message'=>'projet not found']);
        }
        $projets->delete();
        return response()->json(['message'=>'projet deleted']);

    }
    public function trashed(){
        $projet = projets::onlyTrashed()->get();
        return response()->json($projet);

     }
     public function restore($id){
        $projets = projets::withTrashed()->where('id', $id)->first();
        $projets->restore();
        return response()->json(['message'=>'projet restored']);

     }
}
