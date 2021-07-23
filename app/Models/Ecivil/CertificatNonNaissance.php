<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificatNonNaissance extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nom_complet_enfant','sexe','nom_complet_pere','nom_complet_mere','nom_complet_demandeur','contact_demandeur','adresse_demandeur','montant','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_demande_certificat'];
}
