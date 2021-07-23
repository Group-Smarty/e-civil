<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Homme extends Model
{
     use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['naissance_id','profession_id','domicile', 'lieu_naissance_homme', 'numero_piece_identite_homme', 'numero_acte_naissance_homme', 'nom_complet_homme','deceder','marier', 'pere_id','mere_id','homme_marier_chez_lui', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_naissance_homme'];
    
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
