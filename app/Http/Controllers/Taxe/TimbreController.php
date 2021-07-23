<?php

namespace App\Http\Controllers\Taxe;

use App\Http\Controllers\Controller;
use App\Models\Taxes\Timbre;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use function response;

class TimbreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $menuPrincipal = "Taxe";
       $titleControlleur = "Timbre";
       $btnModalAjout = "FALSE";
       return view('taxe.timbre.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeTimbre()
    {
        $timbres = DB::table('timbres')
                ->select('timbres.*')
                ->orderBy('libelle_timbre', 'ASC')
                ->get();
       $jsonData["rows"] = $timbres->toArray();
       $jsonData["total"] = $timbres->count();
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
        if ($request->isMethod('post') && $request->input('libelle_timbre')) {

                $data = $request->all(); 

            try {

                $Timbre = Timbre::where('libelle_timbre', $data['libelle_timbre'])->first();
                if($Timbre!=null){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $timbre = new Timbre;
                $timbre->libelle_timbre = $data['libelle_timbre'];
                $timbre->montant = $data['montant'];
                $timbre->save();
                $jsonData["data"] = json_decode($timbre);
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
     * @param  \App\Timbre  $timbre
     * @return Response
     */
    public function update(Request $request, $id)
    {
       $timbre = Timbre::find($id);
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        if($timbre){
            try {

                $timbre->update([
                    'libelle_timbre' => $request->get('libelle_timbre'),
                    'montant' => $request->get('montant'),
                ]);
                $jsonData["data"] = json_decode($timbre);
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
     * @param  \App\Timbre  $timbre
     * @return Response
     */
    public function destroy($id)
    {
        $timbre = Timbre::find($id);
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($timbre){
                try {
               
                $timbre->delete();
                $jsonData["data"] = json_decode($timbre);
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
