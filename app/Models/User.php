<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\projets;

use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\departements;
use App\Models\congé;
use App\Models\contrats;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'cin',
        'nom',
        'prenom',
        'tel',
        'email',
        'password',
        'status',
        'photo',
        'RaisonSociale',
        'code',
        'role',
        'departement_id',
        'contrat_id',
        'adresse',
        'NbJourConge'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function projets()
    {
        $this->hasMany(projets::class);
    }
    public function contrats()
    {
        $this->hasMany(contrats::class);
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function departements()
    {
        return $this->belongsTo(departements::class);
    }
    public function congé()
    {
        $this->hasMany(congé::class);
    }
    public function contrat()
    {
        return $this->hasOne(contrats::class);
    }
}
