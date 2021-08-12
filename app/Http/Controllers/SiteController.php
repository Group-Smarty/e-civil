<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ecivil\DemandeEnLigne;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     
      return view('site.index'); 
    }

   public function demandeExtraitNaissance(){
      return view('site.demande-extrait-naissance'); 
   }

   public function demandeExtraiDeces(){
      return view('site.demande-extrait-deces'); 
   }

   public function demandeExtraitMariage(){
      return view('site.demande-extrait-mariage'); 
   }

   public function storeDemandeEnLigne(Request $request)
   {

      $this->validate($request,[
         'nom_demandeur'=>'required',
         'numero_acte'=>'required',
         'nombre_copie'=>'required|integer'
      ]);

      $data = $request->all();

      $maxIdDemande = DB::table('demande_en_lignes')->max('id');
      $numero_demande = sprintf("%04d", ($maxIdDemande + 1));
      $annee = date("Y");

      $demande = new DemandeEnLigne;
      $demande->numero_demande = $maxIdDemande.$numero_demande.$annee;
      $demande->nom_demandeur = $data['nom_demandeur'];
      $demande->numero_acte = $data['numero_acte'];
      $demande->nombre_copie =$data['nombre_copie'];
      $demande->type_demande = $data['type_demande'];
      $demande->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur'] : null;
      $demande->copie_integrale = isset($data['copie_integrale']) && !empty($data['copie_integrale']) ? $data['copie_integrale'] : null;
      $demande->etat_demande = 1;
      $demande->date_demande = now();
      $demande->save() ;
      return redirect()->back()->with('numero_demande',$demande->numero_demande);
   }

   public function etatDemandeEnLigne(Request $request)
   {
      
    
   }
}
