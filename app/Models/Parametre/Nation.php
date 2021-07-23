<?php

namespace App\Models\Parametre;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Nation extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    protected $fillable = ['libelle_nation', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at'];
    
    public function peres() {
        return $this->hasMany('App\Models\Ecivil\Pere');
    }

    public function meres() {
        return $this->hasMany('App\Models\Ecivil\Mere');
    }

}
