<?php

namespace App\Http\Controllers;

use App\Models\clients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
            'photo' => 'required|image',
            'Telephone' => 'required',
            'Site' => 'required',
            'Email' => 'required|email|unique:clients',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'error'], 404);
        } else {
            $obj = new clients();
            $obj->RaisonSociale = $request->RaisonSociale;
            if ($request->hasFile('photo')) {
                $file      = $request->file('photo');
                $filename  = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $picture   = date('His') . '-' . $filename;
                //move image to public/img folder
                $file->move(public_path('images'), $picture);
                $obj->photo  = "/images/" . $picture;
            }
            $obj->Telephone = $request->Telephone;
            $obj->Site = $request->Site;
            $obj->Email = $request->Email;
            $obj->save();
            return response()->json(['message' => 'client added']);
        }
    }
    public function update(Request $request, $id)
    {

        $clients = clients::find($id);
        if (is_null($clients)) {
            return response()->json(['message' => 'client Not Found.'], 404);
        }
        $clients->update($request->all());
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

}
