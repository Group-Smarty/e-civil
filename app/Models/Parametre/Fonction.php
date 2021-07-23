<?php

namespace App\Models\Parametre;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fonction extends Model
{
     use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    protected $fillable = ['libelle_fonction', 'updated_by', 'deleted_by', 'created_by'];
    protected $dates = ['deleted_at'];

    public function agents() {
        return $this->hasMany('App\Models\Recrutement\Agent');
    }

    public function peres() {
        return $this->hasMany('App\Models\Ecivil\Pere');
    }

    public function meres() {
        return $this->hasMany('App\Models\Ecivil\Mere');
    }
    
    public function decedes() {
        return $this->hasMany('App\Models\Ecivil\Decede');
    }
    
    public function inhumations() {
        return $this->hasMany('App\Models\Ecivil\Inhumation');
    }
    public function certificat_vies() {
        return $this->hasMany('App\Models\Ecivil\CertificatVie');
    }
    public function certificat_vie_entretiens() {
        return $this->hasMany('App\Models\Ecivil\CertificatVieEntretien');
    }
}
