<?php

namespace App\Http\Controllers\Parametre;

use App\Http\Controllers\Controller;
use App\Models\Parametre\Secteur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class SecteurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $menuPrincipal = "Paramètre";
       $titleControlleur = "Secteurs d'activités";
       $btnModalAjout = "FALSE";
       return view('parametre.secteur.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeSecteur()
    {
        $secteurs = DB::table('secteurs')
                ->select('secteurs.*')
                ->Where('deleted_at', NULL)
                ->orderBy('libelle_secteur', 'ASC')
                ->get();
       $jsonData["rows"] = $secteurs->toArray();
       $jsonData["total"] = $secteurs->count();
       return response()->json($jsonData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if ($request->isMethod('post') && $request->input('libelle_secteur')) {

                $data = $request->all(); 

            try {

                $request->validate([
                    'libelle_secteur' => 'required',
                ]);
                $Secteur = Secteur::where('libelle_secteur', $data['libelle_secteur'])->first();
                if($Secteur!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $secteur = new Secteur;
                $secteur->libelle_secteur = $data['libelle_secteur'];
                $secteur->created_by = Auth::user()->id;
                $secteur->save();
                $jsonData["data"] = json_decode($secteur);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Secteur  $secteur
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Secteur $secteur)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($secteur){
            try {

                $request->validate([
                    'libelle_secteur' => 'required',
                ]);
                $Secteur = Secteur::where('libelle_secteur', $request->get('libelle_secteur'))->first();
                
                if($Secteur!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $secteur->update([
                    'libelle_secteur' => $request->get('libelle_secteur'),
                    'updated_by' => Auth::user()->id,
                ]);
                $jsonData["data"] = json_decode($secteur);
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
     * @param  \App\Secteur  $secteur
     * @return \Illuminate\Http\Response
     */
    public function destroy(Secteur $secteur)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($secteur){
                try {
               
                $secteur->update(['deleted_by' => Auth::user()->id]);
                $secteur->delete();
                $jsonData["data"] = json_decode($secteur);
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
