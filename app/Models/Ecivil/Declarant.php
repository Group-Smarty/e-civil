<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Declarant extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['full_name_declarant','numero_piece_identite','contact_declarant','adresse_declarant','fonction_id','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_naissance_declarant'];
    
    public function declarations()
    {
        return $this->hasMany('App\Models\Ecivil\Declaration');
    }
}
