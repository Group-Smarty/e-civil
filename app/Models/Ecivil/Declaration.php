<?php

namespace App\Models\Ecivil;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Declaration extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['numero_declaration','traducteur', 'langue_reception','type_declaration','nombre_copie','montant','declarant_id', 'acte_id', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_declaration','date_retrait_declaration'];
    
    public function declarant()
    {
        return $this->belongsTo('App\Models\Ecivil\Declarant');
    }
    public function naissance()
    {
        return $this->belongsTo('App\Models\Ecivil\Naissance', 'acte_id');
    }
    public function demandes() {
        return $this->hasMany('App\Models\Ecivil\Demande');
    }
    public function mariage()
    {
        return $this->belongsTo('App\Models\Ecivil\Mariage', 'acte_id');
    }
    public function decede()
    {
        return $this->belongsTo('App\Models\Ecivil\Decede', 'acte_id');
    }
}
