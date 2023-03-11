<?php

namespace App\Http\Controllers;

use App\Models\departements;
use Illuminate\Http\Request;
use App\Models\reunions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class reunionController extends Controller
{
    public function index()
    {
        $reunions = DB::table('reunions')
            ->join('departements', 'reunions.departement_id', '=', 'departements.id')
            ->select('reunions.*', 'departements.Titre')
            ->get();
        return response()->json($reunions);
    }
    public function getsReunionById($id)
    {
        $reunions = reunions::find($id);
        if (is_null($reunions)) {
            return response()->json(['message' => 'reunions Not Found.'], 404);
        }
        return response()->json(reunions::find($id));
    }
    public function getIdDepartmentByName($name){
        $departements = departements::where('Titre',$name)->first();
        if($departements){
            return $departements->id;
        }
        return null;
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Message' => 'required',
            'departement_id' => 'required',
            'DateMessageEnvoye' => 'required|date',
        ]);
        if ($validator->fails()) {
            $arr = array('status' => 'false', 'message' => $validator->errors()->all());
        } else {
            $obj = new reunions();
            $obj->Message = $request->Message;
            $obj->departement_id = $this->getIdDepartmentByName($request->departement_id);
            $obj->DateMessageEnvoye = $request->DateMessageEnvoye;
            $obj->save();
            $arr = array('status' => true, 'message' => ' Query Successfully Send');
        }
        echo json_encode($arr);
    }
    public function update(Request $request, $id)
    {
        $reunions = reunions::find($id);
        if (is_null($reunions)) {
            return response()->json(['message' => 'reunions not found']);
        }
        $reunions->update($request->all());
        return response($reunions);
    }
    public function destroy($id)
    {
        $reunions = reunions::find($id);
        if (is_null($reunions)) {
            return response()->json(['message' => 'reunions not found']);
        }
        $reunions->delete();
        return response()->json(['message' => 'reunions deleted']);
    }
    public function trashed()
    {
        $reunions = reunions::onlyTrashed()->get();
        return response()->json($reunions);
    }
    public function restore($id)
    {
        $reunions = reunions::withTrashed()->where('id', $id)->first();
        $reunions->restore();
        return response()->json(['message' => 'reunions restored']);
    }

}
