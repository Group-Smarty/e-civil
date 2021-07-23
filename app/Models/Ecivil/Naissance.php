<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Naissance extends Model
{
   use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['prenom_enfant','nom_enfant','numero_acte_naissance','sexe','lieu_naissance_enfant','registre','numero_requisition','heure_naissance_enfant','nom_complet_pere','nom_complet_mere','numero_piece_identite_pere','numero_piece_identite_mere','adresse_pere','adresse_mere','lieu_naissance_pere','lieu_naissance_mere','nationalite_mere','nationalite_pere','fonction_pere','fonction_mere','situation_parents','nom_complet_declarant','contact_declarant','adresse_declarant','fonction_declarant','nombre_copie','montant_declaration','loi','numero_jugement_supletif','mention_date_divorce','tribunale','mention_lieu_mariage','mention_lieu_deces','mention_conjoint','signataire','traducteur','langue_reception','nom_temoin_1','nom_temoin_2','fonction_temoin_1','fonction_temoin_2','adresse_temoins_1','adresse_temoins_2','dressant','mention_1','mention_2','mention_3','mention_4','mention_5','mention_6','mention_7','mention_8','updated_by', 'deleted_by', 'created_by'];
    protected $dates = ['date_naissance_enfant','date_dresser','date_requisition','date_naissance_pere','date_naissance_mere','date_retrait','date_naissance_declarant','date_declaration','mention_date_deces','mention_date_mariage','date_naissance_temoin_1','date_naissance_temoin_2'];

    public function nationalite_mere() {
        return $this->belongsTo('App\Models\Parametre\Nation','nationalite_mere');
    }

    public function nationalite_pere() {
        return $this->belongsTo('App\Models\Parametre\Nation','nationalite_pere');
    }

    public function fonction_pere() {
        return $this->belongsTo('App\Models\Parametre\Fonction','fonction_pere');
    }
    
    public function fonction_mere() {
        return $this->belongsTo('App\Models\Parametre\Fonction','fonction_mere');
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
