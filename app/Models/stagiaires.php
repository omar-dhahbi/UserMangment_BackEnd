<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stagiaires extends Model
{
    use HasFactory;
    protected $table = "stagiaires";
    protected $fillable = ['Nom','Prenom','Telephone','Ecole','TypeStage','NiveauEtude','DateDebut','DateFin','sujet','created_at'];
    protected $hidden = ['updated_at'];
    protected $dates = ['deleted_at'];
}
