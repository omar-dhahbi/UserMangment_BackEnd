<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class contrats extends Model
{
    use HasFactory;
    protected $table = 'contrats';
    protected $fillable = ['user_id', 'NomContrat', 'DateDebut', 'DateFin', 'url', 'created_at'];
    protected $hidden = ['updated_at'];
    protected $dates = ['deleted_at'];
    public function User()
    {
        return $this->belongsTo(User::class);
    }

}
