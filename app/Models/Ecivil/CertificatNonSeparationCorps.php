<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificatNonSeparationCorps extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nom_complet_concerne','sexe','lieu_mariage','lieu_deces','nom_complet_conjoint','nom_complet_demandeur','contact_demandeur','adresse_demandeur','montant','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_mariage','date_deces'];

}
