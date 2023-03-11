<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\departements;
class reunions extends Model
{
    use SoftDeletes;
    protected $table = "reunions";
    protected $fillable=['Message','departement_id','DateMessageEnvoye','created_at'];
    protected $hidden = ['updated_at'];
    protected $dates = ['deleted_at'];
    public function departements()
    {
       return $this->belongsTo(departements::class);
    }
}
