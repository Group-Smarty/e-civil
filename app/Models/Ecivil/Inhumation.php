<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inhumation extends Model
{
   use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['nom_complet_demandeur', 'contact_demandeur','adresse_demandeur', 'numero_piece_demandeur','nom_complet_defunt', 'adresse_defunt', 'lieu_deces', 'lieu_inhumation', 'scanne_pv_ou_certificat_deces','deces_id','fonction_id','inhumer_chez_lui','numero_piece_defunt','numero_acte_naissance_defunt','montant', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_deces','date_inhumation','date_demande_permis','date_obseque'];
    
    public function fonction() {
        return $this->belongsTo('App\Models\Parametre\Fonction');
    }
}
