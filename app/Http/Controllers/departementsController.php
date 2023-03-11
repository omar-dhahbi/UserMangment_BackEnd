<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\departements;
use Illuminate\Support\Facades\Validator;

class departementsController extends Controller
{
    public function index()
    {
        $departements = departements::get();
        return response()->json($departements);
    }
    public function getdepartementsById($id)
    {
        $departements = departements::find($id);
        if (is_null($departements)) {
            return response()->json(['message' => 'Departement Not Found.'], 404);
        }
        return response()->json(departements::find($id));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'Titre' => 'required|unique:departements',// choftha
            'Description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Departements exist , try another one'], 404);
        } else {
            $obj = new departements();
            $obj->Titre = $request->Titre;
            $obj->Description = $request->Description;
            $obj->save();
            return response()->json(['message' => 'Departement added']);
        }
    }
    public function update(Request $request, $id)
    {

        $departements = departements::find($id);
        if (is_null($departements)) {
            return response()->json(['message' => 'Departement Not Found.'], 404);
        }
        $departements->update($request->all());
        return response()->json($departements);
    }
    public function destroy($id)
    {
        $departements = departements::find($id);
        if (is_null($departements)) {
            return response()->json(['message' => 'departement not found']);
        }
        $departements->delete();
        return response()->json(['message' => 'departement deleted']);
    }
    public function search(Request $request)
    {
        $query = $request->input('search');

        $departements = departements::where('Titre', 'like', "%$query%")
            ->orWhere('Description', 'like', "%$query%")

            ->get();



        return response()->json($departements);
    }
}
