<?php

namespace App\Http\Controllers\Parametre;

use App\Http\Controllers\Controller;
use App\Models\Parametre\Fonction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class FonctionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $menuPrincipal = "Paramètre";
       $titleControlleur = "Fonction";
       $btnModalAjout = "FALSE";
       return view('parametre.fonction.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeFonction()
    {
        $fonctions = DB::table('fonctions')
                ->select('fonctions.*')
                ->Where('deleted_at', NULL)
                ->orderBy('libelle_fonction', 'ASC')
                ->get();
       $jsonData["rows"] = $fonctions->toArray();
       $jsonData["total"] = $fonctions->count();
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
        if ($request->isMethod('post') && $request->input('libelle_fonction')) {

                $data = $request->all(); 

            try {

                $request->validate([
                    'libelle_fonction' => 'required',
                ]);
                $Fonction = Fonction::where('libelle_fonction', $data['libelle_fonction'])->first();
                if($Fonction!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $fonction = new Fonction;
                $fonction->libelle_fonction = $data['libelle_fonction'];
                $fonction->created_by = Auth::user()->id;
                $fonction->save();
                $jsonData["data"] = json_decode($fonction);
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
     * @param  \App\Fonction  $fonction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Fonction $fonction)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($fonction){
            try {

                $request->validate([
                    'libelle_fonction' => 'required',
                ]);
                $Fonction = Fonction::where('libelle_fonction', $request->get('libelle_fonction'))->first();
                
                if($Fonction!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $fonction->update([
                    'libelle_fonction' => $request->get('libelle_fonction'),
                    'updated_by' => Auth::user()->id,
                ]);
                $jsonData["data"] = json_decode($fonction);
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
     * @param  \App\Fonction  $fonction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Fonction $fonction)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($fonction){
                try {
               
                $fonction->update(['deleted_by' => Auth::user()->id]);
                $fonction->delete();
                $jsonData["data"] = json_decode($fonction);
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
