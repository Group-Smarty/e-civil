<?php

namespace App\Models\Taxes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contribuable extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['numero_identifiant', 'nom_complet', 'sexe', 'contact', 'numero_piece', 'situation_matrimoniale', 'commune_id', 'type_piece_id', 'nation_id', 'fonction_id', 'contact2', 'adresse', 'email', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at', 'date_naissance'];
    
    public function commune()
    {
        return $this->belongsTo('App\Models\Parametre\Commune');
    }
    
    public function type_piece()
    {
        return $this->belongsTo('App\Models\Parametre\TypePiece');
    }
    
    public function nation()
    {
        return $this->belongsTo('App\Models\Parametre\Nation');
    }
    
    public function fonction()
    {
        return $this->belongsTo('App\Models\Parametre\Fonction');
    }
    
}
