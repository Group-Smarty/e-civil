<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Femme extends Model
{
     use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['naissance_id','profession_id','domicile', 'lieu_naissance_femme', 'numero_piece_identite_femme', 'numero_acte_naissance_femme', 'nom_complet_femme','deceder','marier', 'pere_id','mere_id','femme_marier_chez_lui', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_naissance_femme'];
    
    public function pere() {
        return $this->belongsTo('App\Models\Ecivil\Pere');
    }

    public function mere() {
        return $this->belongsTo('App\Models\Ecivil\Mere');
    }
    
    public function naissance(){
        return $this->belongsTo('App\Models\Ecivil\Naissance');
    }
    
    public function mariages() {
        return $this->hasMany('App\Models\Ecivil\Mariage');
    }
}
