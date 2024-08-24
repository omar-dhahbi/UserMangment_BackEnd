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
            ->select('projets.*', 'users.Nom', 'users.Prenom',  'clients.RaisonSociale')
            ->get();
        return response()->json($projets);
    }

    public function getProjetById($id)
    {
        $projets = projets::find($id);
        if (is_null($projets)) {
            return response()->json(['error' => 'projet Not Found.'], 404);
        }
        return response()->json(projets::find($id));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'NomProjets' => 'required|min:6',
            'client_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        } else {
            $obj = new projets();
            $obj->user_id = $request->user_id;
            $obj->NomProjets = $request->NomProjets;
            $obj->client_id = $request->client_id;
            $obj->save();
            $arr = array('status' => true, 'message' => ' Query Successfully Send');
        }
        echo json_encode($arr);
    }
    public function update(Request $request, $id)
    {
        $projets = projets::find($id);
        if (is_null($projets)) {
            return response()->json(['error' => 'projet not found']);
        }
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'NomProjets' => 'required|min:6',
            'client_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }
        $projets->user_id = $request->user_id;
        $projets->NomProjets = $request->NomProjets;
        $projets->client_id = $request->client_id;
        $projets->save();
        return response($projets);
    }
    public function destroy($id)
    {
        $projets = projets::find($id);
        if (is_null($projets)) {
            return response()->json(['error' => 'projet not found']);
        }
        $projets->Delete();
        return response()->json(['message' => 'projet deleted']);
    }

    public function search(Request $request)
    {
        $query = $request->input('search');

        $projets = projets::where('user_id', 'like', "%$query%")
            ->orWhere('NomProjets', 'like', "%$query%")
            ->orWhere('client_id', 'like', "%$query%")
            ->get();
        return response()->json($projets);
    }
}
