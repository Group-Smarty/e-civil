<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificatVie extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nom_complet_naissance', 'nom_complet_usage','contact_demandeur', 'adresse_demandeur','numero_piece_demandeur', 'numero_acte_naissance_demandeur', 'fonction_id', 'lieu_naissance', 'nom_complet_pere', 'nom_complet_mere', 'naissance_id','montant','etat_civil_naissance','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_demande_certificat','date_naissance'];
    
    public function fonction() {
        return $this->belongsTo('App\Models\Parametre\Fonction');
    }
}
