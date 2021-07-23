<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoitTransmi extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['mention','conjoint','concerne','nombre','commune_destination','numero_acte','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_deces','date_mariage','date_dresser','date_demande'];

}
