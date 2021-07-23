<?php

namespace App\Http\Controllers\Recrutement;

use App\Models\Recrutement\Contrat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Exception;

class ContratController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $agents = DB::table('agents')->Where('deleted_at', NULL)->orderBy('full_name_agent', 'asc')->get();
       $modeTravails = DB::table('mode_travails')->Where('deleted_at', NULL)->orderBy('libelle_mode_travail', 'asc')->get();
       $typeContrats = DB::table('type_contrats')->Where('deleted_at', NULL)->orderBy('libelle_type_contrat', 'asc')->get();
       $menuPrincipal = "Recrutement";
       $titleControlleur = "Les contrats de travail";
       $btnModalAjout = Auth::user()->role == 'Administrateur' ? "TRUE" : "FALSE";
       return view('recrutement.contrat.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur','typeContrats', 'modeTravails', 'agents')); 
    }

    
    public function listeContrat()
    {
        $contrats = Contrat::with('agent','mode_travail','type_contrat')
                ->select('contrats.*',DB::raw('DATE_FORMAT(contrats.date_debut, "%d-%m-%Y") as date_debuts'))
                ->Where('deleted_at', NULL)
                ->orderBy('contrats.created_at', 'DESC')
                ->get();
       $jsonData["rows"] = $contrats->toArray();
       $jsonData["total"] = $contrats->count();
       return response()->json($jsonData);
    }
    
    public function listeContratsByName($name){
        $contrats = Contrat::with('agent','mode_travail','type_contrat')
                ->join('agents','agents.id','=','contrats.employe_id')
                ->join('services','services.id','=','agents.service_id')
                ->join('fonctions','fonctions.id','=','agents.fonction_id')
                ->Where([['contrats.deleted_at', NULL],['agents.full_name_agent','like','%'.$name.'%']])
                ->select('contrats.*',DB::raw('DATE_FORMAT(contrats.date_debut, "%d-%m-%Y") as date_debuts'))
                ->orderBy('contrats.created_at', 'DESC')
                ->get();
        $jsonData["rows"] = $contrats->toArray();
        $jsonData["total"] = $contrats->count();
        return response()->json($jsonData);
    }
    
    public function listeContratsByType($type){
         $contrats = Contrat::with('agent','mode_travail','type_contrat')
                ->select('contrats.*',DB::raw('DATE_FORMAT(contrats.date_debut, "%d-%m-%Y") as date_debuts'))
                ->Where([['contrats.deleted_at', NULL],['contrats.type_contrat_id',$type]])
                ->orderBy('contrats.created_at', 'DESC')
                ->get();
       $jsonData["rows"] = $contrats->toArray();
       $jsonData["total"] = $contrats->count();
       return response()->json($jsonData);
    }
    
    public function listeContratsByModeTravail($mode){
         $contrats = Contrat::with('agent','mode_travail','type_contrat')
                ->select('contrats.*',DB::raw('DATE_FORMAT(contrats.date_debut, "%d-%m-%Y") as date_debuts'))
                ->Where([['contrats.deleted_at', NULL],['contrats.mode_travail_id',$mode]])
                ->orderBy('contrats.created_at', 'DESC')
                ->get();
       $jsonData["rows"] = $contrats->toArray();
       $jsonData["total"] = $contrats->count();
       return response()->json($jsonData); 
    }
    
    public function listeContratsBySexe($sexe){
        $contrats = Contrat::with('agent','mode_travail','type_contrat')
                ->join('agents','agents.id','=','contrats.employe_id')
                ->Where([['contrats.deleted_at', NULL],['agents.sexe',$sexe]])
                ->select('contrats.*',DB::raw('DATE_FORMAT(contrats.date_debut, "%d-%m-%Y") as date_debuts'))
                ->orderBy('contrats.created_at', 'DESC')
                ->get();
        $jsonData["rows"] = $contrats->toArray();
        $jsonData["total"] = $contrats->count();
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
        if ($request->isMethod('post') && $request->input('date_debut')) {

                $data = $request->all(); 

            try {
               if(empty($data['scan_contrat'])){
                    return response()->json(["code" => 0, "msg" => "Veillez joindre le fichier scanner du contrat", "data" => NULL]);
                }
                $contrat = new Contrat();
                $contrat->employe_id = $data['employe_id'];
                $contrat->type_contrat_id = $data['type_contrat_id'];
                $contrat->mode_travail_id = $data['mode_travail_id'];
                $contrat->date_debut = Carbon::createFromFormat('d-m-Y', $data['date_debut']);
                $contrat->salaire = $data['salaire'];
                //Insertion du document scanner de contrat 
                if(isset($data['scan_contrat']) && !empty($data['scan_contrat'])){
                    
                    $scan_contrat = request()->file('scan_contrat');
                    $file_name = str_replace(' ', '_', strtolower(time().'.'.$scan_contrat->getClientOriginalName()));
                    //Vérification du format de fichier
//                    $extensions = array('.png','.jpg', '.jpeg');
//                    $extension = strrchr($file_name, '.');
//                    //Début des vérifications de sécurité...
//                    if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
//                    {
//                        return response()->json(["code" => 0, "msg" => "Vous devez uploader un fichier de type jpeg png, jpg", "data" => NULL]);
//                    }
                    $path = public_path().'/documents/contrats/';
                    $scan_contrat->move($path,$file_name);
                    $contrat->scan_contrat = 'documents/contrats/'.$file_name;
                }
                $contrat->created_by = Auth::user()->id;
                $contrat->save();
                $jsonData["data"] = json_decode($contrat);
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
     * @param  \App\Contrat  $contrat
     * @return \Illuminate\Http\Response
     */
    public function updateContrat(Request $request)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        $contrat = Contrat::find($request->get('idContrat'));
        if($contrat){
            try {
                $data = $request->all();
              
                if(empty($data['scan_contrat']) && $contrat->scan_contrat==null){
                    return response()->json(["code" => 0, "msg" => "Veillez joindre le fichier scanner du contrat", "data" => NULL]);
                }
                
                $contrat->employe_id = $data['employe_id'];
                $contrat->type_contrat_id = $data['type_contrat_id'];
                $contrat->mode_travail_id = $data['mode_travail_id'];
                $contrat->date_debut = Carbon::createFromFormat('d-m-Y', $data['date_debut']);
                $contrat->salaire = $data['salaire'];
                //Insertion du document scanner de contrat 
                if(isset($data['scan_contrat']) && !empty($data['scan_contrat'])){
                    
                    $scan_contrat = request()->file('scan_contrat');
                    $file_name = str_replace(' ', '_', strtolower(time().'.'.$scan_contrat->getClientOriginalName()));
//                    //Vérification du format de fichier
//                    $extensions = array('.png','.jpg', '.jpeg');
//                    $extension = strrchr($file_name, '.');
//                    //Début des vérifications de sécurité...
//                    if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
//                    {
//                        return response()->json(["code" => 0, "msg" => "Vous devez uploader un fichier de type jpeg png, jpg", "data" => NULL]);
//                    }
                    $path = public_path().'/documents/contrats/';
                    $scan_contrat->move($path,$file_name);
                    $contrat->scan_contrat = 'documents/contrats/'.$file_name;
                }
                $contrat->updated_by = Auth::user()->id;
                $contrat->save();
                
            $jsonData["data"] = json_decode($contrat);
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
     * @param  \App\Contrat  $contrat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contrat $contrat)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($contrat){
                try {
                $contrat->update(['deleted_by' => Auth::user()->id]);
                $contrat->delete();
                
                $jsonData["data"] = json_decode($contrat);
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
