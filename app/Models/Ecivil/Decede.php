<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Decede extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['nom_complet_decede','numero_acte_deces','sexe','heure_deces','registre','nationalite','motif_deces','numero_acte_naissance_decede','fonction_id','lieu_naissance_decede','lieu_deces','adresse_decede','nom_complet_pere','nom_complet_mere','adresse_mere','adresse_pere','nom_complet_declarant','date_declaration','date_retrait','contact_declarant','adresse_declarant','date_naissance_declarant','fonction_declarant','nombre_copie','montant_declaration','langue_reception','traducteur','dressant','scanne_pv','numero_jugement_supletif','tribunale', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_deces','date_naissance_decede','date_dresser'];
    
    public function fonction_declarant() {
        return $this->belongsTo('App\Models\Parametre\Fonction','fonction_declarant');
    }
    
    public function fonction() {
        return $this->belongsTo('App\Models\Parametre\Fonction');
    }
    
    public function nationalite() {
        return $this->belongsTo('App\Models\Parametre\Nnumero_piece_identite_decedeation','nationalite');
    }
 
}
