<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class projets extends Model
{
    use HasFactory;
    protected $table = 'projets';
    protected $fillable = ['user_id', 'NomProjets', 'client_id', 'created_at'];
    protected $hidden = ['updated_at'];
    protected $dates = ['deleted_at'];
    public function clients()
    {
        $this->belongsTo(clients::class);
    }
    public function User()
    {
        $this->belongsTo(User::class);
    }
}
