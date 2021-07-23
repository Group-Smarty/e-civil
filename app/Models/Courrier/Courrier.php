<?php

namespace App\Models\Courrier;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courrier extends Model
{
     use SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    protected $fillable = ['objet','type_courrier_id','service_id', 'annuaire_id','traiter', 'document_scanner','full_nam_particulier','contact_particulier','commentaire','emmettre_recu','particulier', 'updated_by', 'deleted_by', 'created_by'];
    
    protected $dates = ['deleted_at','date_courrier'];
    
    public function type_courrier()
    {
        return $this->belongsTo('App\Models\Parametre\TypeCourrier');
    }
    public function annuaire() {
        return $this->belongsTo('App\Models\Courrier\Annuaire');
    }
    public function service() {
        return $this->belongsTo('App\Models\Parametre\Service');
    }
}
