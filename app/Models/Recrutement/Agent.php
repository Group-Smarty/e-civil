<?php

namespace App\Models\Recrutement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
     use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    protected $fillable = ['full_name_agent','numero_piece_identite','situation_matrimoniale','sexe','lieu_naissance','numero_securite','phone1','phone2','email','adresse','service_id','fonction_id','commune_id','type_piece_id', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at', 'date_naissance'];
    
    public function service()
    {
        return $this->belongsTo('App\Models\Parametre\Service');
    }
    
    public function fonction()
    {
        return $this->belongsTo('App\Models\Parametre\Fonction');
    }
    public function commune()
    {
        return $this->belongsTo('App\Models\Parametre\Commune');
    }
    public function type_piece()
    {
        return $this->belongsTo('App\Models\Parametre\TypePiece');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function contrats()
    {
        return $this->hasMany('App\Models\Recrutement\Contrat');
    }
}
