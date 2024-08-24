<?php

namespace App\Http\Controllers;

use App\Models\departements;
use Illuminate\Http\Request;
use App\Models\reunionDepartemen;

use App\Models\reunions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class reunionController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            // 'departements' => 'required|array',
            'DescriptionReunion' => 'required|min:12',
            'date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        } else {
            $obj = new reunions();
            $obj->title = $request->title;
            $obj->DescriptionReunion = $request->DescriptionReunion;
            $obj->date = $request->date;
            $obj->save();
            foreach ($request->departement_id as $departement) {
                $obj2 = new reunionDepartemen();
                $obj2->reunion_id = $obj->id;
                $obj2->departement_id = $departement;
                $obj2->save();
            }
            return response()->json(['message' => ' Query Successfully Send']);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'departements' => 'required|array',
            'DescriptionReunion' => 'required|min:12',
            'date' => 'required|date',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        } else {
            $obj = reunions::find($id);
            $obj->title = $request->title;
            $obj->DescriptionReunion = $request->DescriptionReunion;
            $obj->date = $request->date;
            $obj->save();
            $record = DB::table('reunion_departemens')
                ->select('id')
                ->where('reunion_id', $id);
            DB::table('reunion_departemens')
                ->whereIn('id', $record)
                ->delete();

            foreach ($request->departements as $departement) {
                $obj2 = new reunionDepartemen();
                $obj2->reunion_id = $obj->id;
                $obj2->departement_id = $departement;
                $obj2->save();
            }
            return response()->json(['message' => ' Query Successfully Send']);
        }
    }

    public function getDataByInd($id)
    {
        $reunions = DB::table('reunions')
            ->join('reunion_departemens', 'reunions.id', '=', 'reunion_departemens.reunion_id')
            ->join('departements', 'departements.id', '=', 'reunion_departemens.departement_id')
            ->select('reunions.id', 'reunions.title', 'reunions.DescriptionReunion', 'reunions.date', 'departements.id as departements')
            ->where('reunions.id', $id)
            ->orderBy('reunions.id')
            ->get();

        $currentReunion = null;

        foreach ($reunions as $reunion) {
            if ($currentReunion === null || $currentReunion->id !== $reunion->id) {
                $currentReunion = (object)[
                    "id" => $reunion->id,
                    "title" => $reunion->title,
                    "DescriptionReunion" => $reunion->DescriptionReunion,
                    "date" => $reunion->date,
                    "departements" => [$reunion->departements],
                ];
            } else {
                $currentReunion->departements[] = $reunion->departements;
            }
        }

        return $currentReunion;
    }


    public function destroy($id)
    {
        $reunion = reunions::find($id);

        if (is_null($reunion)) {
            return response()->json(['error' => 'Reunion not found'], 404);
        }

        ReunionDepartemen::where('reunion_id', $id)->delete();
        $reunion->delete();

        return response()->json(['message' => 'Reunion supprimer successfully']);
    }
      public function getDataById($id)
    {
        $reunions = DB::table('reunions')
            ->join('reunion_departemens', 'reunions.id', '=', 'reunion_departemens.reunion_id')
            ->join('departements', 'departements.id', '=', 'reunion_departemens.departement_id')
            ->select('reunions.id', 'reunions.title', 'reunions.DescriptionReunion', 'reunions.date', 'departements.Titre as departements')
            ->where('reunions.id', $id)
            ->orderBy('reunions.id')
            ->get();

        $currentReunion = null;

        foreach ($reunions as $reunion) {
            if ($currentReunion === null || $currentReunion->id !== $reunion->id) {
                $currentReunion = (object)[
                    "id" => $reunion->id,
                    "title" => $reunion->title,
                    "DescriptionReunion" => $reunion->DescriptionReunion,
                    "date" => $reunion->date,
                    "departements" => [$reunion->departements],
                ];
            } else {
                $currentReunion->departements[] = $reunion->departements;
            }
        }

        return $currentReunion;
    }

}
