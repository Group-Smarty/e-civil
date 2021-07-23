<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Demande extends Model
{
     use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['numero_demande','nom_demandeur','contact_demandeur','naissance_id','mariage_id','decede_id','nombre_copie','montant','copie_integrale','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_demande','date_retrait_demande'];
    
    public function naissance()
    {
        return $this->belongsTo('App\Models\Ecivil\Naissance');
    }
    public function mariage()
    {
        return $this->belongsTo('App\Models\Ecivil\Mariage');
    }
    public function decede()
    {
        return $this->belongsTo('App\Models\Ecivil\Decede');
    }
}
