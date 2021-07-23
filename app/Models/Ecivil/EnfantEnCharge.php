<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnfantEnCharge extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nom_complet_enfant','numero_extrait_enfant', 'lieu_naissance_enfant', 'certificat_vie_entretien_id','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_naissance'];
    
    public function certificat_vie_entretien() {
        return $this->belongsTo('App\Models\Ecivil\CertificatVieEntretien');
    }
}
