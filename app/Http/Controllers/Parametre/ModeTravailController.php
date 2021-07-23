<?php

namespace App\Http\Controllers\Parametre;

use App\Http\Controllers\Controller;
use App\Models\Parametre\ModeTravail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class ModeTravailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $menuPrincipal = "Paramètre";
       $titleControlleur = "Mode de travail";
       $btnModalAjout = "FALSE";
       return view('parametre.mode-travail.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeModeTravail()
    {
       $modeTravails = DB::table('mode_travails')
                ->select('mode_travails.*')
                ->Where('deleted_at', NULL)
                ->orderBy('libelle_mode_travail', 'ASC')
                ->get();
       $jsonData["rows"] = $modeTravails->toArray();
       $jsonData["total"] = $modeTravails->count();
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
        if ($request->isMethod('post') && $request->input('libelle_mode_travail')) {

                $data = $request->all(); 

            try {

                $request->validate([
                    'libelle_mode_travail' => 'required',
                ]);
                $ModeTravail = ModeTravail::where('libelle_mode_travail', $data['libelle_mode_travail'])->first();
                if($ModeTravail!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $modeTravail = new ModeTravail;
                $modeTravail->libelle_mode_travail = $data['libelle_mode_travail'];
                $modeTravail->created_by = Auth::user()->id;
                $modeTravail->save();
                $jsonData["data"] = json_decode($modeTravail);
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
     * @param  \App\ModeTravail  $modeTravail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ModeTravail $modeTravail)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($modeTravail){
            try {

                $request->validate([
                    'libelle_mode_travail' => 'required',
                ]);
                $ModeTravail = ModeTravail::where('libelle_mode_travail', $request->get('libelle_mode_travail'))->first();
                
                if($ModeTravail!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $modeTravail->update([
                    'libelle_mode_travail' => $request->get('libelle_mode_travail'),
                    'updated_by' => Auth::user()->id,
                ]);
                $jsonData["data"] = json_decode($modeTravail);
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
     * @param  \App\ModeTravail  $modeTravail
     * @return \Illuminate\Http\Response
     */
    public function destroy(ModeTravail $modeTravail)
    {
       $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($modeTravail){
                try {
               
                $modeTravail->update(['deleted_by' => Auth::user()->id]);
                $modeTravail->delete();
                $jsonData["data"] = json_decode($modeTravail);
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
