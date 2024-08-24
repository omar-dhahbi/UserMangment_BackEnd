<?php

namespace App\Http\Controllers;

use App\Models\contrats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class ContratController extends Controller
{
    public function index()
    {
        $contrats = DB::table('contrats')
            ->join('users', 'users.id', '=', 'contrats.user_id')
            ->select('contrats.*', 'users.nom', 'users.prenom',)
            ->get();
        return response()->json($contrats);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|unique:contrats,user_id',
            'NomContrat' => 'required',
            'DateDebut' => 'required|date',
            'DateFin' => 'required|date|after:DateDebut',
            'url' => 'required|file|mimes:pdf'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 404);
        }
        $contrat = new contrats();
        $contrat->user_id = $request->user_id;
        $contrat->NomContrat = $request->NomContrat;
        $contrat->DateDebut = $request->DateDebut;
        $contrat->DateFin = $request->DateFin;
        if ($request->hasFile('url')) {
            $file      = $request->file('url');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = date('His') . '-' . $filename;
            $file->move(public_path('/public/pdf'), $picture);
            $contrat->url  = "/pdf/" . $picture;
        }

        $contrat->save();
        return response()->json(['message' => 'contrat added'], 200);
    }
    public function getcontratsById($id)
    {
        $contrats = contrats::find($id);
        if (is_null($contrats)) {
            return response()->json(['error' => 'contrats Not Found.'], 404);
        }
        return response()->json(contrats::find($id));
    }
    public function destroy($id)
    {
        $contrats = contrats::find($id);
        if (is_null($contrats)) {
            return response()->json(['message' => 'contrat not found']);
        }
        $contrats->delete();
        return response()->json(['message' => 'contrat deleted']);
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => [
                'required',
                Rule::unique('contrats')->ignore($id),
            ],
            'NomContrat' => 'required',
            'DateDebut' => 'required|date',
            'DateFin' => 'required|date|after:DateDebut',
            'url' => 'file|mimes:pdf'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 404);
        }
        $contrat = contrats::find($id);
        if (is_null($contrat)) {
            return response()->json(['error' => 'contrat Not Found.'], 404);
        }
        $contrat->user_id = $request->user_id;
        $contrat->NomContrat = $request->NomContrat;
        $contrat->DateDebut = $request->DateDebut;
        $contrat->DateFin = $request->DateFin;

        if ($request->hasFile('url')) {
            $file      = $request->file('url');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = date('His') . '-' . $filename;
            $file->move(public_path('/public/pdf'), $picture);
            $contrat->url  = "/pdf/" . $picture;
        }
        $contrat->save();
        return response()->json($contrat);
    }
    public function search(Request $request)
    {
        $query = $request->input('search');
        $projets = contrats::where('user_id', 'like', "%$query%")
            ->orWhere('NomContrat', 'like', "%$query%")
            ->orWhere('DateDebut', 'like', "%$query%")
            ->orWhere('DateFin', 'like', "%$query%")

            ->get();
        return response()->json($projets);
    }
}

