<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stagiaires extends Model

{
    use HasFactory;
    protected $table = "stagiaires";
    protected $fillable = ['Cin', 'Nom', 'Prenom', 'telephone', 'Email', 'Ecole', 'TypeStage', 'NiveauEtude', 'class', 'DateDebut', 'DateFin', 'sujet', 'DescriptionSujet'];
    protected $hidden = ['created_at', 'updated_at'];
}
