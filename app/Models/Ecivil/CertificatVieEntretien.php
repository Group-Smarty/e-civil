<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificatVieEntretien extends Model
{
     use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nom_complet_personne', 'numero_piece_personne','contact_personne', 'adresse_personne','numero_acte_naissance_personne', 'lieu_naissance', 'fonction_id', 'naissance_id', 'montant', 'etat_civil_naissance','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_naissance','date_demande_certificat'];
    
    public function fonction() {
        return $this->belongsTo('App\Models\Parametre\Fonction');
    }
    public function enfant_en_charges() {
        return $this->hasMany('App\Models\Ecivil\EnfantEnCharge');
    }
}
