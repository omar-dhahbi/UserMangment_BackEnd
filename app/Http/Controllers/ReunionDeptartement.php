<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\departements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\reunionDepartemen;
use App\Models\reunions;

class ReunionDeptartement extends Controller
{

    public function getReunionByDepartement()
    {
        $reunions = DB::table('reunions')
            ->join('reunion_departemens', 'reunions.id', '=', 'reunion_departemens.reunion_id')
            ->join('departements', 'departements.id', '=', 'reunion_departemens.departement_id')
            ->select('reunions.id', 'reunions.title', 'reunions.DescriptionReunion', 'reunions.date', 'departements.Titre')
            ->orderBy('reunions.id')
            ->get();

        $result = [];
        $currentReunion = null;

        foreach ($reunions as $reunion) {
            if ($currentReunion === null || $currentReunion['id'] !== $reunion->id) {
                if ($currentReunion !== null) {
                    $result[] = $currentReunion;
                }
                $currentReunion = [
                    "id" => $reunion->id,
                    "title" => $reunion->title,
                    "DescriptionReunion" => $reunion->DescriptionReunion,
                    "date" => $reunion->date,
                    "departements" => [$reunion->Titre],
                ];
            }
        else {
                $currentReunion["departements"][] = $reunion->Titre;
            }
        }

        if ($currentReunion !== null) {
            $result[] = $currentReunion;
        }

        return $result;
    }
}
