<?php

namespace App\Models\Taxes;

use Illuminate\Database\Eloquent\Model;

class TypeTaxe extends Model
{
    protected $fillable = ['libelle_type_taxe', 'montant'];

}
