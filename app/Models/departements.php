<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\reunions;
use App\Models\reunionDepartemen;



class departements extends Model
{
    use HasFactory;
    protected $table = "departements";
    protected $fillable = ['Titre', 'Description'];
    protected $hidden = ['created_at', 'updated_at'];

    public function reunions()
    {
        return $this->belongsToMany(reunions::class)->using(reunionDepartemen::class);
    }

    public function reunion()
    {
        $this->hasMany(reunions::class);
    }
    public function User()
    {
        return $this->hasMany(User::class);
    }
}
