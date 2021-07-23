<?php

namespace App\Http\Controllers\Courrier;

use App\Http\Controllers\Controller;
use App\Models\Courrier\Annuaire;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnnuaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $secteurs = DB::table('secteurs')->Where('deleted_at', NULL)->orderBy('libelle_secteur', 'asc')->get();
       $typeSocietes = DB::table('type_societes')->Where('deleted_at', NULL)->orderBy('libelle_type_societe', 'asc')->get();
       $menuPrincipal = "Courrier";
       $titleControlleur = "Liste des contacts";
       $btnModalAjout = "TRUE";
       return view('courrier.annuaire.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur','secteurs','typeSocietes')); 

    }

   public function listeAnnuaire()
    {
        $annuaires = Annuaire::with('type_societe','secteur')
                ->select('annuaires.*')
                ->Where('deleted_at', NULL)
                ->orderBy('full_name_personne_contacter', 'ASC')
                ->get();
       $jsonData["rows"] = $annuaires->toArray();
       $jsonData["total"] = $annuaires->count();
       return response()->json($jsonData);
    }
    
    public function listeAnnuaireLast(){
        $annuaires = Annuaire::with('type_societe','secteur')
                ->select('annuaires.*')
                ->Where('deleted_at', NULL)
                ->orderBy('annuaires.id', 'DESC')
                ->take(1)->get();
       $jsonData["rows"] = $annuaires->toArray();
       $jsonData["total"] = $annuaires->count();
       return response()->json($jsonData);
    }

    public function findAnnuaireById($id)
    {
        $annuaires = Annuaire::with('type_societe','secteur')
                ->select('annuaires.*')
                ->Where([['deleted_at', NULL],['annuaires.id',$id]])
                ->get();
       $jsonData["rows"] = $annuaires->toArray();
       $jsonData["total"] = $annuaires->count();
       return response()->json($jsonData);
    }
    
    public function listeAnnuaireByName($name)
    {
        $annuaires = Annuaire::with('type_societe','secteur')
                ->select('annuaires.*')
                ->Where([['deleted_at', NULL],['annuaires.raison_sociale','like','%'.$name.'%']])
                ->orWhere([['deleted_at', NULL],['annuaires.full_name_personne_contacter','like','%'.$name.'%']])
                ->get();
       $jsonData["rows"] = $annuaires->toArray();
       $jsonData["total"] = $annuaires->count();
       return response()->json($jsonData);
    }
    
    public function listeAnnuaireBySecteur($secteur){
        $annuaires = Annuaire::with('type_societe','secteur')
                ->join('secteurs','secteurs.id','=','annuaires.secteur_id')
                ->select('annuaires.*')
                ->Where([['annuaires.deleted_at', NULL],['annuaires.secteur_id',$secteur]])
                ->get();
       $jsonData["rows"] = $annuaires->toArray();
       $jsonData["total"] = $annuaires->count();
       return response()->json($jsonData);
    }
    
    public function listeAnnuaireByTypeSociete($type){
        $annuaires = Annuaire::with('type_societe','secteur')
                ->join('type_societes','type_societes.id','=','annuaires.type_societe_id')
                ->select('annuaires.*')
                ->Where([['annuaires.deleted_at', NULL],['annuaires.type_societe_id',$type]])
                ->get();
       $jsonData["rows"] = $annuaires->toArray();
       $jsonData["total"] = $annuaires->count();
       return response()->json($jsonData);
    }
    
    public function listeAnnuaireByContact($contact){
        $annuaires = Annuaire::with('type_societe','secteur')
                ->select('annuaires.*')
                ->Where([['deleted_at', NULL],['annuaires.contact1','like','%'.$contact.'%']])
                ->orWhere([['deleted_at', NULL],['annuaires.contact2','like','%'.$contact.'%']])
                ->get();
       $jsonData["rows"] = $annuaires->toArray();
       $jsonData["total"] = $annuaires->count();
       return response()->json($jsonData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if ($request->isMethod('post') && $request->input('full_name_personne_contacter')) {

                $data = $request->all(); 

            try {
                
                $Annuaire = Annuaire::where('email', $data['email'])->first();
                if($Annuaire!=null){
                    return response()->json(["code" => 0, "msg" => "Ce contact est déjà enregistré, vérifier l'adresse email", "data" => NULL]);
                }
                
                $annuaire = new Annuaire;
                $annuaire->raison_sociale = $data['raison_sociale'];
                $annuaire->adresse_siege = $data['adresse_siege'];
                $annuaire->secteur_id = $data['secteur_id'];
                $annuaire->type_societe_id = $data['type_societe_id'];
                $annuaire->civilite_personne_contacter = $data['civilite_personne_contacter'];
                $annuaire->full_name_personne_contacter = $data['full_name_personne_contacter'];
                $annuaire->email = $data['email'];
                $annuaire->contact1 = $data['contact1'];
                $annuaire->post_occupe = $data['post_occupe'];
                $annuaire->contact2 = isset($data['contact2']) && !empty($data['contact2']) ? $data['contact2']: Null;
                $annuaire->created_by = Auth::user()->id;
                $annuaire->save();
                $jsonData["data"] = json_decode($annuaire);
                return response()->json($jsonData);

            } catch (Exception $exc) {
               $jsonData["code"] = -1;
               $jsonData["data"] = NULL;
               $jsonData["msg"] = $exc->getMessage();
               return response()->json($jsonData); 
            }
        }
        return response()->json(["code" => 0, "msg" => "Saisie invalide", "data" => NULL]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\Annuaire  $annuaire
     * @return Response
     */
    public function update(Request $request, Annuaire $annuaire)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($annuaire){
            try {
                
                $data = $request->all();
              
               $annuaire->raison_sociale = $data['raison_sociale'];
                $annuaire->adresse_siege = $data['adresse_siege'];
                $annuaire->secteur_id = $data['secteur_id'];
                $annuaire->type_societe_id = $data['type_societe_id'];
                $annuaire->civilite_personne_contacter = $data['civilite_personne_contacter'];
                $annuaire->full_name_personne_contacter = $data['full_name_personne_contacter'];
                $annuaire->email = $data['email'];
                $annuaire->contact1 = $data['contact1'];
                $annuaire->post_occupe = $data['post_occupe'];
                $annuaire->contact2 = isset($data['contact2']) && !empty($data['contact2']) ? $data['contact2']: Null;
                $annuaire->updated_by = Auth::user()->id;
                $annuaire->save();
            $jsonData["data"] = json_decode($annuaire);
            return response()->json($jsonData);
            } catch (Exception $exc) {
               $jsonData["code"] = -1;
               $jsonData["data"] = NULL;
               $jsonData["msg"] = $exc->getMessage();
               return response()->json($jsonData); 
            }
        }
        return response()->json(["code" => 0, "msg" => "Echec de modification", "data" => NULL]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Annuaire  $annuaire
     * @return Response
     */
    public function destroy(Annuaire $annuaire)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($annuaire){
                try {
               
                $annuaire->update(['deleted_by' => Auth::user()->id]);
                $annuaire->delete();
                
                $jsonData["data"] = json_decode($annuaire);
                return response()->json($jsonData);

                } catch (Exception $exc) {
                   $jsonData["code"] = -1;
                   $jsonData["data"] = NULL;
                   $jsonData["msg"] = $exc->getMessage();
                   return response()->json($jsonData); 
                }
            }
        return response()->json(["code" => 0, "msg" => "Echec de suppression", "data" => NULL]);
    }
}
