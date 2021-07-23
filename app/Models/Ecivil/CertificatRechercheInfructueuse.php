<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificatRechercheInfructueuse extends Model
{
     use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nom_complet_concerne','nom_complet_demandeur','numero_certificat_medical','lieu_certificat_medical','lieu_evenement','adresse_demandeur','contact_demandeur','numero_piece_demandeur','montant','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_demande_certificat','date_evenement','date_certificat_medical'];
}
