<?php

namespace App\Http\Controllers\Parametre;

use App\Http\Controllers\Controller;
use App\Models\Parametre\Commune;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class CommuneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $menuPrincipal = "Paramètre";
       $titleControlleur = "Commune";
       $btnModalAjout = "FALSE";
       return view('parametre.commune.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeCommune()
    {
        $communes = DB::table('communes')
                ->select('communes.*')
                ->Where('deleted_at', NULL)
                ->orderBy('libelle_commune', 'ASC')
                ->get();
       $jsonData["rows"] = $communes->toArray();
       $jsonData["total"] = $communes->count();
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
        if ($request->isMethod('post') && $request->input('libelle_commune')) {

                $data = $request->all(); 

            try {

                $request->validate([
                    'libelle_commune' => 'required',
                ]);
                $Commune = Commune::where('libelle_commune', $data['libelle_commune'])->first();
                if($Commune!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $commune = new Commune;
                $commune->libelle_commune = $data['libelle_commune'];
                $commune->created_by = Auth::user()->id;
                $commune->save();
                $jsonData["data"] = json_decode($commune);
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
     * @param  \App\Commune  $commune
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Commune $commune)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($commune){
            try {

                $request->validate([
                    'libelle_commune' => 'required',
                ]);
                $Commune = Commune::where('libelle_commune', $request->get('libelle_commune'))->first();
                
                if($Commune!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $commune->update([
                    'libelle_commune' => $request->get('libelle_commune'),
                    'updated_by' => Auth::user()->id,
                ]);
                $jsonData["data"] = json_decode($commune);
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
     * @param  \App\Commune  $commune
     * @return \Illuminate\Http\Response
     */
    public function destroy(Commune $commune)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($commune){
                try {
               
                $commune->update(['deleted_by' => Auth::user()->id]);
                $commune->delete();
                $jsonData["data"] = json_decode($commune);
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
