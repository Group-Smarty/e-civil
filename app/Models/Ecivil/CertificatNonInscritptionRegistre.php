<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificatNonInscritptionRegistre extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['contact_demandeur','adresse_demandeur','numero_piece_demandeur','montant', 'nom_complet_decede', 'nom_complet_pere','nom_complet_mere', 'lieu_deces','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_deces','date_demande_certificat'];
    
}
