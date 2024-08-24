<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\departements;
use App\Models\reunionDepartemen;


class reunions extends Model
{
    use HasFactory;
    protected $table = 'reunions';
    protected $fillable = ['Message', 'DescriptionReunion', 'DateMessageEnvoye', 'created_at'];
    protected $hidden = ['updated_at'];
    protected $dates = ['deleted_at'];
   
    public function departement()
    {
        return $this->hasManyThrough(departements::class, reunionDepartemen::class);
    }
}
