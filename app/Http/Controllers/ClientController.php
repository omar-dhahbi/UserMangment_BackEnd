<?php

namespace App\Http\Controllers;

use App\Models\clients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;


class ClientController extends Controller
{
    public function index()
    {
        $clients = clients::get();
        return response()->json($clients);
    }
    public function getclientsById($id)
    {
        $clients = clients::find($id);
        if (is_null($clients)) {
            return response()->json(['message' => 'client Not Found.'], 404);
        }
        return response()->json(clients::find($id));
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'RaisonSociale' => 'required|unique:clients',
            'photo' => 'required|image|mimes:jpeg,png,jpg',
            'Telephone' => 'required',
            'Site' => 'required',
            'email' => 'required|email|unique:clients,email',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        } else {
            $obj = new clients();
            $obj->RaisonSociale = $request->RaisonSociale;
            if ($request->hasFile('photo')) {
                $file      = $request->file('photo');
                $filename  = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $picture   = date('His') . '-' . $filename;
                //move image to public/img folder
                $file->move(public_path('/public/images'), $picture);
                $obj->photo  = "/images/" . $picture;
            }
            $obj->Telephone = $request->Telephone;
            $obj->Site = $request->Site;
            $obj->email = $request->email;
            $obj->save();
            return response()->json(['message' => 'client added']);
        }
    }
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'RaisonSociale' => [
                'required',
                'max:255', Rule::unique('clients')->ignore($id),
            ],
            'Telephone' => 'required',
            'Site' => 'required',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('clients')->ignore($id),
            ],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }
        $clients = clients::find($id);
        if (is_null($clients)) {
            return response()->json(['message' => 'client Not Found.'], 404);
        }

        $clients->RaisonSociale = $request->RaisonSociale;

        if ($request->hasFile('photo')) {
            $file      = $request->file('photo');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = date('His') . '-' . $filename;
            $file->move(public_path('/public/images'), $picture);
            $clients->photo  = "/images/" . $picture;
        }
        $clients->Telephone = $request->Telephone;
        $clients->Site = $request->Site;
        $clients->email = $request->email;

        $clients->update();

        return response()->json($clients);
    }

    public function destroy($id)
    {
        $clients = clients::find($id);
        if (is_null($clients)) {
            return response()->json(['message' => 'client not found']);
        }
        $clients->delete();
        return response()->json(['message' => 'client deleted']);
    }
    public function search(Request $request)
    {
        $query = $request->input('search');

        $clients = clients::where('RaisonSociale', 'like', "%$query%")
            ->orWhere('Telephone', 'like', "%$query%")
            ->orWhere('Site', 'like', "%$query%")
            ->orWhere('Email', 'like', "%$query%")
            ->get();
        return response()->json($clients);
    }
}
