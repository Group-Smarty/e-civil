<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificatConcubinage extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nom_complet_homme','nom_complet_femme','profession_homme','profession_femme','adresse_homme','adresse_femme','lieu_mariage_coutumier','adresse_commune','nom_complet_temoins_1','nom_complet_temoins_2','profession_temoins_1','profession_temoins_2','adresse_temoins_1','adresse_temoins_2','numero_piece_temoins_1','numero_piece_temoins_2','lieu_etablisssement_piece_temoins_1','lieu_etablisssement_piece_temoins_2','piece_temoins_1_delivre_par','piece_temoins_2_delivre_par','nom_complet_demandeur','contact_demandeur','adresse_demandeur','montant','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_naissance_homme','date_naissance_femme','date_mariage_coutumier','date_demande_certificat','date_etablisssement_piece_temoins_1','date_etablisssement_piece_temoins_2'];
    
}
