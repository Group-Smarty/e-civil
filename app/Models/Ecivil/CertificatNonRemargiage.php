<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificatNonRemargiage extends Model
{
   use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sexe','contact_demandeur','adresse_demandeur','numero_piece_demandeur','date_demande_certificat','montant', 'nom_complet_temoin1', 'nom_complet_temoin2','interrese','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at'];
}
