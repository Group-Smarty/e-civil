<?php

namespace App\Models\Taxes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeclarationActivite extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['nom_activite', 'nom_structure', 'numero_cc', 'montant_taxe', 'numero_registre', 'contact', 'situation_geographique', 'contribuable_id', 'type_societe_id', 'secteur_id', 'type_taxe_id', 'localite_id', 'longitude', 'latitude', 'adresse_postale', 'email', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at', 'date_naissance', 'date_declaration'];
    
    public function localite()
    {
        return $this->belongsTo('App\Models\Taxes\Localite');
    }
    
    public function type_taxe()
    {
        return $this->belongsTo('App\Models\Taxes\TypeTaxe');
    }
    
    public function secteur()
    {
        return $this->belongsTo('App\Models\Parametre\Secteur');
    }
    
    public function type_societe()
    {
        return $this->belongsTo('App\Models\Parametre\TypeSociete');
    }
    
    public function contribuable()
    {
        return $this->belongsTo('App\Models\Taxes\Contribuable');
    }
}
