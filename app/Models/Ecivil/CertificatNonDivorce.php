<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificatNonDivorce extends Model
{
      use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nom_complet_homme','nom_complet_femme','profession_homme','profession_femme','numero_acte_mariage','etat_civil_mariage','pere_homme','mere_homme','pere_femme','mere_femme','numero_acte_naissance', 'nom_complet_demandeur','contact_demandeur','adresse_demandeur','montant','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_mariage','date_demande_certificat'];
    
}
