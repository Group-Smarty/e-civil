<?php

namespace App\Models\Courrier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Annuaire extends Model
{
     use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    protected $fillable = ['raison_sociale','adresse_siege','secteur_activite_id','type_societe_id','civilite_personne_contacter','full_name_personne_contacter','email','contact1','contact2','post_occupe', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at'];
    
    public function type_societe() {
        return $this->belongsTo('App\Models\Parametre\TypeSociete');
    }
    public function secteur() {
        return $this->belongsTo('App\Models\Parametre\Secteur');
    }
    
    public function courriers() {
        return $this->hasMany('App\Models\Courrier\Courrier');
    }
}
