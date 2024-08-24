<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\departements;
use App\Models\reunions;



class reunionDepartemen extends Model
{

    use HasFactory;
    protected $table = 'reunion_departemens';
    protected $fillable = ['reunion_id', 'departement_id',  'created_at'];
    protected $hidden = ['updated_at'];
    protected $dates = ['deleted_at'];
    public function reunions()
    {
        return $this->belongsTo(reunions::class);
    }
    public function département()
    {
        return $this->belongsTo(departements::class);
    }
}
