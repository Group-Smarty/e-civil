<?php

namespace App\Models\Taxes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayementTaxe extends Model
{
    use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = ['payement_effectuer_par', 'numero_ticket', 'declaration_activite_id', 'caisse_ouverte_id', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at', 'date_payement'];
    
    public function declaration_activite()
    {
        return $this->belongsTo('App\Models\Taxes\DeclarationActivite');
    }
}
