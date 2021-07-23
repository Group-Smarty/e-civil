<?php

namespace App\Models\Recrutement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contrat extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    protected $fillable = ['employe_id','type_contrat_id','mode_travail_id','salaire','scan_contrat', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at', 'date_debut'];
    
    public function agent() {
        return $this->belongsTo('App\Models\Recrutement\Agent','employe_id');
    }

    public function type_contrat() {
        return $this->belongsTo('App\Models\Parametre\TypeContrat');
    }

    public function mode_travail() {
        return $this->belongsTo('App\Models\Parametre\ModeTravail');
    }

}
