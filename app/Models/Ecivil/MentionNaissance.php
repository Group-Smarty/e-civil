<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MentionNaissance extends Model
{
     use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['titre','mention','id_naissance','updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at'];
}
