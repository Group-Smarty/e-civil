<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pere extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['full_name_pere','numero_piece_identite_pere','lieu_naissance_pere','adresse_pere','nation_id','fonction_id', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_naissance_pere'];
    
    public function naissances()
    {
        return $this->hasMany('App\Models\Ecivil\Naissance');
    }
    public function fonction() {
        return $this->belongsTo('App\Models\Parametre\Fonction');
    }
    public function nation() {
        return $this->belongsTo('App\Models\Parametre\Nation');
    }
    public function hommes()
    {
        return $this->hasMany('App\Models\Ecivil\Homme');
    }
    public function femmes()
    {
        return $this->hasMany('App\Models\Ecivil\Femme');
    }
}
