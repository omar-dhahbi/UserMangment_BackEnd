<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\reunion;
class departements extends Model
{
    use HasFactory;
    protected $table = "departements";
    protected $fillable = ['Titre','Description','created_at'];
    protected $hidden = ['updated_at'];
    protected $dates = ['deleted_at'];
    public $timestamps=true;

    public function reunion()
    {
        return $this->hasMany(reunion::class);
    }
}
