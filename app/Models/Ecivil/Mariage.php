<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mariage extends Model
{
     use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['numero_acte_mariage','registre','regime_id','nom_complet_homme','adresse_domicile_homme','fonction_homme','numero_acte_naissance_homme','lieu_naissance_homme','nom_complet_femme','adresse_domicile_femme','fonction_femme','numero_acte_naissance_femme','lieu_naissance_femme','nom_complet_pere_homme','nom_complet_mere_homme','adresse_mere_homme','adresse_pere_homme','nom_complet_pere_femme','nom_complet_mere_femme','adresse_mere_femme','adresse_pere_femme','decret_autorisation_femme','decret_autorisation_homme','nom_complet_declarant','contact_declarant','adresse_declarant','fonction_declarant','nombre_copie','montant_declaration','dressant','langue_reception','traducteur','nom_complet_temoin_1','nom_complet_temoin_2','adresse_temoin_1','adresse_temoin_2','fonction_temoin_1','fonction_temoin_2','signataire','mention_1','mention_2','mention_3','mention_4','mention_5','mention_6','mention_7','mention_8','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_mariage','date_dresser','date_naissance_homme','date_naissance_femme','date_naissance_declarant','date_retrait','date_declaration'];
    
    public function regime() {
        return $this->belongsTo('App\Models\Parametre\Regime');
    }
    public function fonction_homme() {
        return $this->belongsTo('App\Models\Parametre\Fonction','fonction_homme');
    }
    public function fonction_femme() {
        return $this->belongsTo('App\Models\Parametre\Fonction','fonction_femme');
    }
    public function fonction_declarant() {
        return $this->belongsTo('App\Models\Parametre\Fonction','fonction_declarant');
    }
    public function fonction_temoin_1() {
        return $this->belongsTo('App\Models\Parametre\Fonction','fonction_temoin_1');
    }
    public function fonction_temoin_2() {
        return $this->belongsTo('App\Models\Parametre\Fonction','fonction_temoin_2');
    }
}
