<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\projets;

class clients extends Model
{
    use HasFactory;
    protected $table = "clients";
    protected $fillable = ['RaisonSociale', 'photo', 'Telephone', 'Site', 'email'];
    protected $hidden = ['created_at', 'updated_at'];
    public function projet()
    {
        $this->hasMany(projets::class);
    }
}
