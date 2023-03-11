<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\clients;
use Illuminate\Database\Eloquent\SoftDeletes;
class projets extends Model
{
    use SoftDeletes;
    protected $table = "projets";
    protected $fillable = ['user_id','NomProjets','client_id','created_at'];
    protected $hidden = ['updated_at'];
    protected $dates = ['deleted_at'];


    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function clients()
    {
        return $this->belongsTo(clients::class);
    }
}
