<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\Decede;
use App\Models\Ecivil\Demande;
use App\Models\Ecivil\Mariage;
use App\Models\Ecivil\Naissance;
use App\Models\Ecivil\DemandeEnLigne;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DemandeController extends Controller
{
    public function vueDemandeCopieActeNaissance(){
       $naissances = DB::table('naissances')
                        ->select('naissances.id','naissances.numero_acte_naissance',DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                        ->orderBy('naissances.id', 'DESC')
                        ->get();
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Demande de copie d'extrait de naissance";
       $btnModalAjout = "TRUE";
       return view('ecivil.naissance.demande-copie',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur','naissances')); 
    }
    public function vueDemandeCopieActeMariage(){
       $mariages = DB::table('mariages')->Where('mariages.deleted_at', NULL)
                        ->select('mariages.id','mariages.numero_acte_mariage',DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                        ->orderBy('mariages.id', 'DESC')
                        ->get();
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Demande de copie d'extrait de mariage";
       $btnModalAjout = "TRUE";
       return view('ecivil.mariage.demande-copie',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur','mariages')); 
    }
    public function vueDemandeCopieActeDeces(){
        $decedes = DB::table('decedes')->Where('decedes.deleted_at', NULL)
                        ->select('decedes.id','decedes.numero_acte_deces',DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                        ->orderBy('decedes.id', 'DESC')
                        ->get();
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Demande de copie certificat de décès";
       $btnModalAjout = "TRUE";
       return view('ecivil.decede.demande-copie',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur','decedes')); 
    }

    public function demandeRecue(){
        $menuPrincipal = "Etat civil";
        $titleControlleur = "Liste des démandes réçues dépuis la page web";
        $btnModalAjout = "FALSE";
        return view('ecivil.demande-web.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeDemandeActeNaissance(){
        $demandes = Demande::with('naissance')
                    ->join('naissances','naissances.id','=','demandes.naissance_id')
                    ->Where([['demandes.deleted_at', NULL],['naissance_id','!=',null]]) 
                    ->select('demandes.*','naissances.prenom_enfant','naissances.nom_enfant',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance_enfants'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('demandes.id', 'DESC')
                    ->get();
   
        $jsonData["rows"] = $demandes->toArray();
        $jsonData["total"] = $demandes->count();
        return response()->json($jsonData);
    }
    
    public function listeDemandeActeMariage() {
             $demandes = Demande::with('mariage')
                                    ->join('mariages','mariages.id','=','demandes.mariage_id')
                                    ->Where([['demandes.deleted_at', NULL],['demandes.mariage_id','!=',null]]) 
                                    ->select('demandes.*',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y") as date_mariages'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                                    ->orderBy('demandes.id', 'DESC')
                                    ->get();
   
       $jsonData["rows"] = $demandes->toArray();
       $jsonData["total"] = $demandes->count();
       return response()->json($jsonData); 
    }
    
    public function listeDemandeActeDeces(){
        $demandes = Demande::with('decede')
                    ->join('decedes','decedes.id','=','demandes.decede_id')
                    ->Where([['demandes.deleted_at', NULL],['demandes.decede_id','!=',null]]) 
                    ->select('demandes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('demandes.id', 'DESC')
                    ->get();
   
       $jsonData["rows"] = $demandes->toArray();
       $jsonData["total"] = $demandes->count();
       return response()->json($jsonData);
    }
    
    public function listeDemandesByNumero($numero_demande, $ecran){ 
        if($ecran=="naissance"){
            $demandes = Demande::with('naissance')
                    ->join('naissances','naissances.id','=','demandes.naissance_id')
                    ->Where([['demandes.deleted_at', NULL],['demandes.naissance_id','!=',null],['demandes.numero_demande','like','%'.$numero_demande.'%']]) 
                    ->select('demandes.*','naissances.prenom_enfant','naissances.nom_enfant',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance_enfants'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('demandes.id', 'DESC')
                    ->get();
        }
        if($ecran=="mariage"){
            $demandes = Demande::with('mariage')
                    ->join('mariages','mariages.id','=','demandes.mariage_id')
                    ->Where([['demandes.deleted_at', NULL],['demandes.mariage_id','!=',null],['demandes.numero_demande','like','%'.$numero_demande.'%']]) 
                    ->select('demandes.*',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y") as date_mariages'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('demandes.id', 'DESC')
                    ->get();
        }
        if($ecran=="deces"){
            
            $demandes = Demande::with('decede')
                    ->join('decedes','decedes.id','=','demandes.decede_id')
                    ->Where([['demandes.deleted_at', NULL],['demandes.decede_id','!=',null],['demandes.numero_demande','like','%'.$numero_demande.'%']]) 
                    ->select('demandes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('demandes.id', 'DESC')
                    ->get();
        }
       $jsonData["rows"] = $demandes->toArray();
       $jsonData["total"] = $demandes->count();
       return response()->json($jsonData);
    }
    
    public function listeDemandesByNumeroActe($numero_acte, $ecran){
        if($ecran=="naissance"){
            $demandes = Demande::with('naissance')
                    ->join('naissances','naissances.id','=','demandes.naissance_id')
                    ->Where([['demandes.deleted_at', NULL],['demandes.naissance_id','!=',null],['naissances.numero_acte_naissance','like','%'.$numero_acte.'%']]) 
                    ->select('demandes.*','naissances.prenom_enfant','naissances.nom_enfant',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance_enfants'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('demandes.id', 'DESC')
                    ->get();
        }
        if($ecran=="mariage"){
            $demandes = Demande::with('mariage')
                                ->join('mariages','mariages.id','=','demandes.mariage_id')
                                ->Where([['demandes.deleted_at', NULL],['demandes.mariage_id','!=',null],['mariages.numero_acte_mariage','like','%'.$numero_acte.'%']]) 
                                ->select('demandes.*',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y") as date_mariages'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                                ->orderBy('demandes.id', 'DESC')
                                ->get();
        }
        if($ecran=="deces"){
            
            $demandes = Demande::with('decede')
                    ->join('decedes','decedes.id','=','demandes.decede_id')
                    ->Where([['demandes.deleted_at', NULL],['demandes.decede_id','!=',null],['decedes.numero_acte_deces','like','%'.$numero_acte.'%']]) 
                    ->select('demandes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('demandes.id', 'DESC')
                    ->get();
        }
       $jsonData["rows"] = $demandes->toArray();
       $jsonData["total"] = $demandes->count();
       return response()->json($jsonData);
    }
    
    public function listeDemandesByName($nom, $ecran){
        if($ecran=="naissance"){
            $demandes = Demande::with('naissance')
                    ->join('naissances','naissances.id','=','demandes.naissance_id')
                    ->Where([['demandes.deleted_at', NULL],['demandes.naissance_id','!=',null],['demandes.nom_demandeur','like','%'.$nom.'%']]) 
                    ->orWhere([['demandes.deleted_at', NULL],['demandes.naissance_id','!=',null],['naissances.nom_enfant','like','%'.$nom.'%']]) 
                    ->orWhere([['demandes.deleted_at', NULL],['demandes.naissance_id','!=',null],['naissances.prenom_enfant','like','%'.$nom.'%']]) 
                    ->select('demandes.*','naissances.prenom_enfant','naissances.nom_enfant',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance_enfants'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('demandes.id', 'DESC')
                    ->get();
        }
        if($ecran=="mariage"){
            $demandes = Demande::with('mariage')
                                    ->join('mariages','mariages.id','=','demandes.mariage_id')
                                    ->Where([['demandes.deleted_at', NULL],['demandes.mariage_id','!=',null],['demandes.nom_demandeur','like','%'.$nom.'%']]) 
                                    ->orWhere([['demandes.deleted_at', NULL],['demandes.mariage_id','!=',null],['mariages.nom_complet_femme','like','%'.$nom.'%']]) 
                                    ->orWhere([['demandes.deleted_at', NULL],['demandes.mariage_id','!=',null],['mariages.nom_complet_homme','like','%'.$nom.'%']]) 
                                    ->select('demandes.*',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y") as date_mariages'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                                    ->orderBy('demandes.id', 'DESC')
                                    ->get();
        }
        if($ecran=="deces"){
           
            $demandes = Demande::with('decede')
                    ->join('decedes','decedes.id','=','demandes.decede_id')
                    ->Where([['demandes.deleted_at', NULL],['demandes.decede_id','!=',null],['demandes.nom_demandeur','like','%'.$nom.'%']]) 
                    ->orWhere([['demandes.deleted_at', NULL],['demandes.decede_id','!=',null],['decedes.nom_complet_decede','like','%'.$nom.'%']]) 
                    ->select('demandes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('demandes.id', 'DESC')
                    ->get();
        }
       $jsonData["rows"] = $demandes->toArray();
       $jsonData["total"] = $demandes->count();
       return response()->json($jsonData);
    }
    
    public function listeDemandesByDate($dates, $ecran){
        $date = Carbon::createFromFormat('d-m-Y', $dates);
        if($ecran=="naissance"){
           $demandes = Demande::with('naissance')
                    ->join('naissances','naissances.id','=','demandes.naissance_id')
                    ->Where([['demandes.deleted_at', NULL],['demandes.naissance_id','!=',null]]) 
                    ->whereDate('demandes.date_demande','=', $date)
                    ->select('demandes.*','naissances.prenom_enfant','naissances.nom_enfant',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance_enfants'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('demandes.id', 'DESC')
                    ->get(); 
        }
        if($ecran=="mariage"){
            $demandes = Demande::with('mariage')
                                ->join('mariages','mariages.id','=','demandes.mariage_id')
                                ->Where([['demandes.deleted_at', NULL],['demandes.mariage_id','!=',null]]) 
                                ->whereDate('demandes.date_demande','=', $date)
                                ->select('demandes.*',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y") as date_mariages'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                                ->orderBy('demandes.id', 'DESC')
                                ->get();
        }
        if($ecran=="deces"){
            $demandes = Demande::with('decede')
                    ->join('decedes','decedes.id','=','demandes.decede_id')
                    ->Where([['demandes.deleted_at', NULL],['demandes.decede_id','!=',null]]) 
                    ->whereDate('demandes.date_demande','=', $date)
                    ->select('demandes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y") as date_retrait_demandes'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('demandes.id', 'DESC')
                    ->get();
        }
       $jsonData["rows"] = $demandes->toArray();
       $jsonData["total"] = $demandes->count();
       return response()->json($jsonData);
    }
    
    public function listeSansDemande(){
        $aujourdhui = date("Y-m-d");
        $data=[];
        $demandes = Demande::where('demandes.deleted_at',null)
                    ->where('demandes.naissance_id','!=',null)
                    ->get();
        foreach ($demandes as $demande) {
            $dateDemande = Carbon::createFromFormat('Y-m-d H:i:s', $demande->date_demande);
            $diffJour = date_diff(date_create($dateDemande), date_create($aujourdhui));
            $year = $diffJour->format('%y');
            if($year < 1){
                $data[] = $demande->declaration_id;
            }
        }
        $listes = Naissance::where('naissances.deleted_at',null)
                                ->whereNotIn('naissances.id', $data)
                                ->select('naissances.*')->get(); 
       return $listes;
    }

    public function listeDemandeRecue(){
        $demandes = DemandeEnLigne::select('demande_en_lignes.*',DB::raw('DATE_FORMAT(demande_en_lignes.date_demande, "%d-%m-%Y") as date_demandes'))
                    ->orderBy('demande_en_lignes.id', 'DESC')
                    ->get();

       $jsonData["rows"] = $demandes->toArray();
       $jsonData["total"] = $demandes->count();
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
         if ($request->isMethod('post') && $request->input('nom_demandeur')) {
             
             $data = $request->all(); 
             $maxIdDemande = DB::table('demandes')->max('id');
             $numero_demande = sprintf("%04d", ($maxIdDemande + 1));
             $annee = date("Y");
             
            try{
                $demande = new Demande;
                $demande->numero_demande = $numero_demande.$annee;
                $demande->nom_demandeur =  $data['nom_demandeur'];
                $demande->date_demande = now();
                $demande->date_retrait_demande = isset($data['date_retrait_demande']) && !empty($data['date_retrait_demande']) ? Carbon::createFromFormat('d-m-Y', $data['date_retrait_demande']) : null;
                $demande->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur']: Null;
                $demande->nombre_copie = $data['nombre_copie'];
                $demande->montant = isset($data['montant']) && !empty($data['montant']) ? $data['montant'] : 0;
                $demande->naissance_id = isset($data['naissance_id']) && !empty($data['naissance_id']) ? $data['naissance_id'] : null;
                $demande->mariage_id = isset($data['mariage_id']) && !empty($data['mariage_id']) ? $data['mariage_id'] : null;
                $demande->decede_id = isset($data['decede_id']) && !empty($data['decede_id']) ? $data['decede_id'] : null;
                $demande->copie_integrale = isset($data['copie_integrale']) ? TRUE:FALSE;
                $demande->created_by = Auth::user()->id;
                $demande->save();
                $jsonData["data"] = json_decode($demande);
                return response()->json($jsonData);
            }catch (Exception $exc) {
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
     * @param  \App\Demande  $demande
     * @return Response
     */
    public function update(Request $request, Demande $demande)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
         if($demande){
            try {
                $data = $request->all();
                
                $demande->nom_demandeur =  $data['nom_demandeur'];
                $demande->date_retrait_demande = isset($data['date_retrait_demande']) && !empty($data['date_retrait_demande']) ? Carbon::createFromFormat('d-m-Y', $data['date_retrait_demande']) : null;
                $demande->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur']: Null;
                $demande->nombre_copie = $data['nombre_copie'];
                $demande->montant = isset($data['montant']) && !empty($data['montant']) ? $data['montant'] : 0;
                $demande->naissance_id = isset($data['naissance_id']) && !empty($data['naissance_id']) ? $data['naissance_id'] : null;
                $demande->mariage_id = isset($data['mariage_id']) && !empty($data['mariage_id']) ? $data['mariage_id'] : null;
                $demande->decede_id = isset($data['decede_id']) && !empty($data['decede_id']) ? $data['decede_id'] : null;
                $demande->copie_integrale = isset($data['copie_integrale']) ? TRUE:FALSE;
                $demande->updated_by = Auth::user()->id;
                $demande->save();
                
            $jsonData["data"] = json_decode($demande);
            return response()->json($jsonData);
            }catch(Exception $exc) {
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
     * @param  \App\Demande  $demande
     * @return Response
     */
    public function destroy(Demande $demande)
    {
       $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($demande){
                try {
               
                $demande->update(['deleted_by' => Auth::user()->id]);
                $demande->delete();
                
                $jsonData["data"] = json_decode($demande);
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
    
     //Fonction pour recuperer les infos de Helpers
    public function infosConfig(){
        $get_configuration_infos = \App\Helpers\ConfigurationHelper\Configuration::get_configuration_infos(1);
        return $get_configuration_infos;
    }
    
    public function premierLetre(){
        $n=substr($this->infosConfig()->commune,0,1); 
        if($n=='A' || $n=='E' || $n=='I' || $n=='O' || $n=='U' || $n=='Y'){ 
            $d = "d'";
            
        }else{
            $d = "de ";
        }
        return $d;
    }
    
    //Etats reçu de demande copie de naissance
    public function recuDemandeCopieNaissancePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->recuDemandeCopieNaissance($id));
        return $pdf->stream('recu_'.$id.'_'.date('d/m/Y').'.pdf');
    }
    public function recuDemandeCopieNaissance($id){
        $outPut = $this->headerDemanadeNaissance($id);
        $outPut.= $this->contentDemanadeNaissance($id);
        $outPut.= $this->footer();
        return $outPut;
    }
    public function headerDemanadeNaissance($id){
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);
        $recuInfo = Demande::find($id);
       $header = "<html>
        <head>
            <meta charset='utf-8'>
            <style>
               @page { size: 17cm 21cm landscape; }
                .fixed-header-left{
                    width: 35%;
                    height:20%;
                    text-align:center;
                    position: absolute; 
                    float: left;
                }
                .fixed-header-right{
                    width: 45%;
                    height:20%;
                    text-align:center;
                    position: absolute;
                    float: right;
                }
               
                .fixed-content{
                    margin:270px 0;
                    position: absolute;
                }
                .fixed-footer{
                    position: fixed; 
                    bottom: -28; 
                    float: right;
                    left: 0px; 
                    right: 0px;
                    height: 50px; 
                    text-align:center;
                }  
              
            </style>
            <body>
                <div class='fixed-header-left'>";
             $header.="COMMUNE ".strtoupper($this->premierLetre()."".$commune)."<hr width='50'/></b>
                    <img src=".$this->infosConfig()->logo." width='150' height='150'><br/> 
                    <b> Mairie ".$this->premierLetre()."".$this->infosConfig()->commune."</b><br/>";
            if($this->infosConfig()->adresse_marie!=null){
                 $header.="Adresse: ".$this->infosConfig()->adresse_marie."<br/>";
            }
            if($this->infosConfig()->telephone_mairie!=null){
                $header.="Tel : ".$this->infosConfig()->telephone_mairie."<br/>"; 
            }           
            if($this->infosConfig()->fax_mairie!=null){
                $header.="Fax : ".$this->infosConfig()->fax_mairie."<br/>"; 
            }     
            if($this->infosConfig()->site_web_mairie!=null){
                  $header.="".$this->infosConfig()->site_web_mairie."<br/> ";
            }       
            $header.="</div>
                    <div class='fixed-header-right'>
                    <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                    Union-Discipline-Travail<hr width='50'/></b> 
                    <p><i>DEMANDE N° <b>".$recuInfo->numero_demande."</b></i></p>
                </div>";
        return $header;
    }
    public function contentDemanadeNaissance($id){
        $recu = Naissance::where([['demandes.id', $id],['demandes.naissance_id','!=',null]]) 
                            ->join('demandes', 'demandes.naissance_id','=','naissances.id')
                            ->select('naissances.prenom_enfant','naissances.nom_enfant','naissances.date_naissance_enfant','naissances.numero_acte_naissance','naissances.date_dresser','demandes.montant','demandes.nombre_copie', 'demandes.nom_demandeur','demandes.numero_demande',DB::raw('DATE_FORMAT(demandes.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(demandes.date_retrait_demande, "%d-%m-%Y à %H:%i") as date_retraits'))
                            ->first();
        $content ="<div class='fixed-content'> 
                    <p style='text-align:center; font-size:20px;'><b><u>RECEPISSE DE DEMANDE DE COPIE D'EXTRAIT DE NAISSANCE</u></b></p>
                    <p><i> Naissance de </i><b>".$recu->prenom_enfant." ".$recu->nom_enfant." </b> <i>le</i><b> ".date("d-m-Y", strtotime($recu->date_naissance_enfant))."</b></p>
                    <p><i> Référence </i><b>".$recu->numero_acte_naissance." DU ".date("d-m-Y", strtotime($recu->date_dresser))."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Nombre de copies</i> <b>".$recu->nombre_copie."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Montant : <b>".number_format($recu->montant, 0, ',', ' ')." F CFA</b></p>
                    <p><i> Demande faite le </i><b>".$recu->date_demandes."</b> <i>par</i> <b>".$recu->nom_demandeur."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Date de retrait </i> <b>".$recu->date_retraits."</b></p>
                </div>";
        return $content;
    }
    
    //Etats reçu de demande copie de mariage
    public function recuDemandeCopieMariagePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->recuDemandeCopieMariage($id));
        return $pdf->stream('recu_'.$id.'_'.date('d/m/Y').'.pdf');
    }
    public function recuDemandeCopieMariage($id){
        $outPut = $this->headerDemanadeMariage($id);
        $outPut.= $this->contentDemanadeMariage($id);
        $outPut.= $this->footer();
        return $outPut;
    }
    public function headerDemanadeMariage($id){
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);
         $recuInfo = Mariage::where([['mariages.deleted_at', NULL],['demandes.mariage_id','!=',null],['demandes.id',$id]]) 
                                ->join('demandes', 'demandes.mariage_id','=','mariages.id')
                                ->select('demandes.numero_demande')
                                ->first();
        $header = "<html>
        <head>
            <meta charset='utf-8'>
            <style>
               @page { size: 17cm 21cm landscape; }
                .fixed-header-left{
                    width: 35%;
                    height:20%;
                    text-align:center;
                    position: absolute; 
                    float: left;
                }
                .fixed-header-right{
                    width: 45%;
                    height:20%;
                    text-align:center;
                    position: absolute;
                    float: right;
                }
               
                .fixed-content{
                    margin:270px 0;
                    position: absolute;
                }
                .fixed-footer{
                    position: fixed; 
                    bottom: -28; 
                    float: right;
                    left: 0px; 
                    right: 0px;
                    height: 50px; 
                    text-align:center;
                }  
              
            </style>
            <body>
                <div class='fixed-header-left'>";
             $header.="COMMUNE ".strtoupper($this->premierLetre()."".$commune)."<hr width='50'/></b>
                    <img src=".$this->infosConfig()->logo." width='150' height='150'><br/> 
                    <b> Mairie ".$this->premierLetre()."".$this->infosConfig()->commune."</b><br/>";
            if($this->infosConfig()->adresse_marie!=null){
                 $header.="Adresse: ".$this->infosConfig()->adresse_marie."<br/>";
            }
            if($this->infosConfig()->telephone_mairie!=null){
                $header.="Tel : ".$this->infosConfig()->telephone_mairie."<br/>"; 
            }           
            if($this->infosConfig()->fax_mairie!=null){
                $header.="Fax : ".$this->infosConfig()->fax_mairie."<br/>"; 
            }     
            if($this->infosConfig()->site_web_mairie!=null){
                  $header.="".$this->infosConfig()->site_web_mairie."<br/> ";
            }       
            $header.="</div>
                    <div class='fixed-header-right'>
                    <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                    Union-Discipline-Travail<hr width='50'/></b> 
                    <p><i>DEMANDE N° <b>".$recuInfo->numero_demande."</b></i></p>
                </div>";
        return $header;
    }
    
    public function contentDemanadeMariage($id){
        $recu = Mariage::where([['mariages.deleted_at', NULL],['demandes.mariage_id','!=',null],['demandes.id',$id]]) 
                        ->join('demandes', 'demandes.mariage_id','=','mariages.id')
                        ->select('mariages.*','demandes.nombre_copie','demandes.montant','demandes.date_demande','demandes.nom_demandeur','demandes.date_retrait_demande')
                        ->first();
        
        $content =" <div class='fixed-content'> 
                    <p style='text-align:center; font-size:20px;'><b><u>RECEPISSE DE DEMANDE DE COPIE D'EXTRAIT DE MARIAGE</u></b></p>
                    <p><i> Mariage entre </i><b>".$recu->nom_complet_homme."</b> <i>et</i> <b>".$recu->nom_complet_femme." </b><i>le </i><b>".date("d-m-Y", strtotime($recu->date_mariage))."</b></p>
                    <p><i> Référence </i><b>".$recu->numero_acte_mariage." DU ".date("d-m-Y", strtotime($recu->date_dresser))."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Nombre de copies</i> <b>".$recu->nombre_copie."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Montant : <b>".number_format($recu->montant, 0, ',', ' ')." F CFA</b></p>
                    <p><i> Demande faite le </i><b>".date("d-m-Y", strtotime($recu->date_demande))."</b> <i>par</i> <b>".$recu->nom_demandeur."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Date de retrait </i> <b>".date("d-m-Y", strtotime($recu->date_retrait_demande))."</b></p>
                </div>";
        return $content;
    }
    
     //Etats reçu de demande copie de décès
    public function recuDemandeCopieDecesPdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->recuDemandeCopieDeces($id));
        return $pdf->stream('recu_'.$id.'_'.date('d/m/Y').'.pdf');
    }
    public function recuDemandeCopieDeces($id){
        $outPut = $this->headerDemanadeDeces($id);
        $outPut.= $this->contentDemanadeDeces($id);
        $outPut.= $this->footer();
        return $outPut;
    }
    public function headerDemanadeDeces($id){
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);
        $recuInfo = $recuInfo = Demande::find($id);
       $header = "<html>
        <head>
            <meta charset='utf-8'>
            <style>
               @page { size: 17cm 21cm landscape; }
                .fixed-header-left{
                    width: 35%;
                    height:20%;
                    text-align:center;
                    position: absolute; 
                    float: left;
                }
                .fixed-header-right{
                    width: 45%;
                    height:20%;
                    text-align:center;
                    position: absolute;
                    float: right;
                }
               
                .fixed-content{
                    margin:270px 0;
                    position: absolute;
                }
                .fixed-footer{
                    position: fixed; 
                    bottom: -28; 
                    float: right;
                    left: 0px; 
                    right: 0px;
                    height: 50px; 
                    text-align:center;
                }  
              
            </style>
            <body>
                <div class='fixed-header-left'>";
             $header.="COMMUNE ".strtoupper($this->premierLetre()."".$commune)."<hr width='50'/></b>
                    <img src=".$this->infosConfig()->logo." width='150' height='150'><br/> 
                    <b> Mairie ".$this->premierLetre()."".$this->infosConfig()->commune."</b><br/>";
            if($this->infosConfig()->adresse_marie!=null){
                 $header.="Adresse: ".$this->infosConfig()->adresse_marie."<br/>";
            }
            if($this->infosConfig()->telephone_mairie!=null){
                $header.="Tel : ".$this->infosConfig()->telephone_mairie."<br/>"; 
            }           
            if($this->infosConfig()->fax_mairie!=null){
                $header.="Fax : ".$this->infosConfig()->fax_mairie."<br/>"; 
            }     
            if($this->infosConfig()->site_web_mairie!=null){
                  $header.="".$this->infosConfig()->site_web_mairie."<br/> ";
            }       
            $header.="</div>
                    <div class='fixed-header-right'>
                    <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                    Union-Discipline-Travail<hr width='50'/></b> 
                    <p><i>DEMANDE N° <b>".$recuInfo->numero_demande."</b></i></p>
                </div>";
        return $header;
    }
    public function contentDemanadeDeces($id){
        $recu = Decede::where([['decedes.deleted_at', NULL],['demandes.decede_id','!=',null],['demandes.id',$id]])
                        ->join('demandes', 'demandes.decede_id','=','decedes.id')
                        ->select('decedes.*','demandes.montant','demandes.nombre_copie','demandes.nom_demandeur','demandes.date_demande','demandes.date_retrait_demande')
                        ->first();
        $content =" <div class='fixed-content'> 
                    <p style='text-align:center; font-size:20px;'><b><u>RECEPISSE DE DEMANDE DE COPIE D'EXTRAIT DE DECES</u></b></p>
                    <p><i> Décès de </i><b>".$recu->nom_complet_decede." </b><i>le </i><b>".date("d-m-Y", strtotime($recu->date_deces))."</b></p>
                    <p><i> Référence </i><b>".$recu->numero_acte_deces." DU ".date("d-m-Y", strtotime($recu->date_dressers))."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Nombre de copies</i> <b>".$recu->nombre_copie."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Montant : <b>".number_format($recu->montant, 0, ',', ' ')." F CFA</b></p>
                    <p><i> Demande faite le </i><b>".date("d-m-Y", strtotime($recu->date_demande))."</b> <i>par</i> <b>".$recu->nom_demandeur."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Date de retrait </i> <b>".date("d-m-Y", strtotime($recu->date_retrait_demande))."</b></p>
                </div>";
        return $content;
    }
    
    public function footer(){
        $footer =  '<div class="fixed-footer">
                        <i>Document établi le <b>'.date("d/m/Y").' </b></i>     
                    </div>
                </body></html>';
        return $footer;
    }
    //Etat liste des personnes sans demande sur au moins 1 an
    public function ficheSansDemandePdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHTML($this->ficheSansDemande());
        return $pdf->stream('liste_personnes_sans_demande.pdf');
    }
    public function ficheSansDemande(){
        $datas = $this->listeSansDemande();
        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'><h3 align='center'><u>Liste des personnes n'ayant pas fait de demande de copie d'extrait de naissance depuis au moins 1 an</h3>
                    <table border='2' cellspacing='0' width='100%'>
                        <tr>
                            <th cellspacing='0' border='2' width='30%' align='center'>Prénom(s)</th>
                            <th cellspacing='0' border='2' width='20%' align='center'>Nom</th>
                            <th cellspacing='0' border='2' width='20%' align='center'>Date de nais.</th>
                            <th cellspacing='0' border='2' width='30%' align='center'>Lieu de nais.</th>
                            <th cellspacing='0' border='2' width='25%' align='center'>N° Extrait</th>
                            <th cellspacing='0' border='2' width='30%' align='center'>Père</th>
                            <th cellspacing='0' border='2' width='30%' align='center'>Mère</th>
                        </tr></div>";
         $total = 0;
       foreach ($datas as $data){
           $total = $total + 1;
           $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="center">'.$data->nom_enfant.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->prenom_enfant.'</td>
                            <td  cellspacing="0" border="2" align="center">'.date('d-m-Y', strtotime($data->date_naissance_enfant)).'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->lieu_naissance_enfant.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->numero_acte_naissance.' DU '.date('d-m-Y', strtotime($data->date_dresser)).'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->nom_complet_pere.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->nom_complet_mere.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').' personne(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
   //Header and footer des pdf pour les listes dans tableau
    public function headerFiche(){
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);
     
        $header = "<html>
                    <head>
                        <style>
                            @page{
                                margin: 100px 25px;
                                }
                            header{
                                    position: absolute;
                                    top: -60px;
                                    left: 0px;
                                    right: 0px;
                                    height:40px;
                                }
                                .fixed-header-left{
                                            width: 35%;
                                            height:30%;
                                            position: absolute; 
                                            top: 0;
                                            padding: 10px 0;
                                            text-align:center;
                                        }
                                .fixed-header-right{
                                            width: 35%;
                                            height:7%;
                                            float: right;
                                            position: absolute;
                                            top: 0;
                                            padding: 10px 0;
                                            text-align:center;
                                        }
                            .container-table{        
                                            margin:170px 0;
                                            width: 100%;
                                        }
                            .fixed-footer{.
                                width : 100%;
                                position: fixed; 
                                bottom: -28; 
                                left: 0px; 
                                right: 0px;
                                height: 50px; 
                                text-align:center;
                            }
                            .fixed-footer-right{
                                position: absolute; 
                                bottom: -125; 
                                height: 0; 
                                font-size:13px;
                                float : right;
                            }
                            .page-number:before {
                                            
                            }
                        </style>
                    </head>
   
        <body>
        <header>
            <div class='fixed-header-left'>
             <b>COMMUNE ".strtoupper($this->premierLetre()."".$commune)."</b><br/>
                   <img src=".$this->infosConfig()->logo." width='100' height='100'><br/> 
                    <b> Mairie ".$this->premierLetre()."".$this->infosConfig()->commune."</b><br/>";
                    if($this->infosConfig()->adresse_marie != null) {
                        $header .= "Adresse: " . $this->infosConfig()->adresse_marie . "<br/>";
                    }
                    if ($this->infosConfig()->telephone_mairie != null) {
                        $header .= "Tel : " . $this->infosConfig()->telephone_mairie . "<br/>";
                    }
                    if ($this->infosConfig()->fax_mairie != null) {
                        $header .= "Fax : " . $this->infosConfig()->fax_mairie . "<br/>";
                    }
                    if ($this->infosConfig()->site_web_mairie != null) {
                        $header .= "".$this->infosConfig()->site_web_mairie . "<br/> ";
                    }
                $header.="</div>
                    <div class='fixed-header-right'>
                       <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                        Union-Discipline-Travail<hr width='50'/></b>
                    </div>
        </header>";   
        return $header;
    }
    public function footerFiche(){
        $footer ="<div class='fixed-footer'>
                        <div class='page-number'></div>
                       
                    </div>
                    <div class='fixed-footer-right'>
                    </div>
            </body>
        </html>";
        return $footer;
    }
}
