<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificatCelibat extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['civilite','raison_disolution_mariage','conjoint','lieu_mariage','tribunal','type','concerne','lieu_naissance','numero_act_naissance','numero_requette','nom_pere','nom_mere','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_demande','date_naissance','date_dresser','date_requette','date_mariage'];

}
