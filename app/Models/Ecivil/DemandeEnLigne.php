<?php

namespace App\Models\Ecivil;;

use Illuminate\Database\Eloquent\Model;

class DemandeEnLigne extends Model
{
    protected $fillable = ['numero_demande','nom_demandeur','numero_acte','nombre_copie','type_demande','contact_demandeur','copie_integrale','etat_demande','motif_rejet'];

    protected $dates = ['date_demande'];
}
