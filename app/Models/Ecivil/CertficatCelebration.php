<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertficatCelebration extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['numero_acte','nom_epoux','nom_epouse','nom_epouse','fonction_epouse','fonction_epoux','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_dresser','date_mariage','date_demande'];
    
    public function fonction_epoux() {
        return $this->belongsTo('App\Models\Parametre\Fonction','fonction_epoux');
    }
    
    public function fonction_epouse() {
        return $this->belongsTo('App\Models\Parametre\Fonction','fonction_epouse');
    }
}
