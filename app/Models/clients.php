<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\projets;
class clients extends Model

{
    use HasFactory;
    protected $table = "clients";
    protected $fillable = ['RaisonSociale','photo','Telephone','Site','Email','created_at'];
    protected $hidden = ['updated_at'];
    protected $dates = ['deleted_at'];
    public function projets()
    {
        return $this->hasMany(projets::class);
    }
}
