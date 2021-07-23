<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\Decede;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Picqer\Barcode\BarcodeGeneratorPNG;
include_once(app_path ()."/number-to-letters/nombre_en_lettre.php");

class DecedeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $fonctions = DB::table('fonctions')->Where('deleted_at', NULL)->orderBy('libelle_fonction', 'asc')->get();
       $nations = DB::table('nations')->Where('deleted_at', NULL)->orderBy('libelle_nation', 'asc')->get();
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Déclaration des décès";
       $btnModalAjout = "TRUE";
       return view('ecivil.decede.index',compact('fonctions','nations', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }
    
    public function vueDecesParMois(){
        $annees = [];
        $cetteAnnee = (date("Y")-1);
        for ($index = 1950; $index <= $cetteAnnee; $index++) {
            $annees[$index] = $index;
        }
       $anneesObt = array_reverse($annees);
       $moisFr = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Liste des décès par mois pour l'année en cours";
       $btnModalAjout = "FALSE";
       return view('ecivil.decede.deces-by-mois',compact('moisFr','anneesObt', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
 
    }
    public function vueDecesParLieu(){
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Liste des décès par lieu pour l'année en cours";
       $btnModalAjout = "FALSE";
        return view('ecivil.decede.deces-by-lieu',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }
    public function vueDecesParMotif(){
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Liste des décès par motif pour l'année en cours";
       $btnModalAjout = "FALSE";
        return view('ecivil.decede.deces-by-motif',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeDeces(){
        $deces = Decede::with('fonction','fonction_declarant')
                ->Where('decedes.deleted_at', NULL) 
                ->select('decedes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(decedes.date_naissance_decede, "%d-%m-%Y") as date_naissance_decedes'),DB::raw('DATE_FORMAT(decedes.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(decedes.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(decedes.date_naissance_declarant, "%d-%m-%Y") as date_naissance_declarants'))
                ->orderBy('decedes.id', 'DESC')
                ->get();
       $jsonData["rows"] = $deces->toArray();
       $jsonData["total"] = $deces->count();
       return response()->json($jsonData);
    }
    public function listeDecedeByName($name){
        $deces = Decede::with('fonction','fonction_declarant')
                ->Where([['decedes.deleted_at', NULL],['decedes.nom_complet_decede','like','%'.$name.'%']]) 
                ->select('decedes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(decedes.date_naissance_decede, "%d-%m-%Y") as date_naissance_decedes'),DB::raw('DATE_FORMAT(decedes.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(decedes.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(decedes.date_naissance_declarant, "%d-%m-%Y") as date_naissance_declarants'))
                ->orderBy('decedes.id', 'DESC')
                ->get();
       $jsonData["rows"] = $deces->toArray();
       $jsonData["total"] = $deces->count();
       return response()->json($jsonData);
    }
    public function listeDecedeByNumeroActe($numero){
        $deces = Decede::with('fonction','fonction_declarant')
                ->Where([['decedes.deleted_at', NULL],['decedes.numero_acte_deces','like','%'.$numero.'%']]) 
                ->select('decedes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(decedes.date_naissance_decede, "%d-%m-%Y") as date_naissance_decedes'),DB::raw('DATE_FORMAT(decedes.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(decedes.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(decedes.date_naissance_declarant, "%d-%m-%Y") as date_naissance_declarants'))
                ->orderBy('decedes.id', 'DESC')
                ->get();
       $jsonData["rows"] = $deces->toArray();
       $jsonData["total"] = $deces->count();
       return response()->json($jsonData);
    }
    public function listeDecedeByDate($dates){
        $date = Carbon::createFromFormat('d-m-Y', $dates);
        $deces = Decede::with('fonction','fonction_declarant')
                ->whereDate('decedes.date_deces','=', $date)
                ->select('decedes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(decedes.date_naissance_decede, "%d-%m-%Y") as date_naissance_decedes'),DB::raw('DATE_FORMAT(decedes.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(decedes.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(decedes.date_naissance_declarant, "%d-%m-%Y") as date_naissance_declarants'))
                ->orderBy('decedes.id', 'DESC')
                ->get();
       $jsonData["rows"] = $deces->toArray();
       $jsonData["total"] = $deces->count();
       return response()->json($jsonData);
    }
    
    public function findActeDecesById($id){
        $deces = Decede::with('fonction')
                ->Where([['decedes.deleted_at', NULL],['decedes.id',$id]]) 
                ->select('decedes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'))
                ->get();
       $jsonData["rows"] = $deces->toArray();
       $jsonData["total"] = $deces->count();
       return response()->json($jsonData);
    }
    
    public function listeDecesByLieux(){
       $liste =  Decede::where('deleted_at',null)
                     ->whereYear('date_deces', date('Y'))
                     ->select('decedes.lieu_deces', DB::raw('count(*) as nombre'))
                     ->groupBy('decedes.lieu_deces')
                     ->orderBy('nombre', 'desc')->get();
        return $liste;
    }
    public function listeDecesByLieuPeriode($debut, $fin){
        $date1 = Carbon::createFromFormat('d-m-Y', $debut);
        $date2 = Carbon::createFromFormat('d-m-Y', $fin);
        $liste =  Decede::where('deleted_at',null)
                    ->whereDate('decedes.date_deces','>=',$date1)
                    ->whereDate('decedes.date_deces','<=', $date2)
                     ->select('decedes.lieu_deces', DB::raw('count(*) as nombre'))
                     ->groupBy('decedes.lieu_deces')
                     ->orderBy('nombre', 'desc')->get();
        return $liste;
    }
    public function listeDecesByMotif(){
        $liste =  Decede::where('deleted_at',null)
                            ->whereYear('date_deces', date('Y'))
                            ->select('decedes.motif_deces', DB::raw('count(*) as nombre'))
                            ->groupBy('decedes.motif_deces')
                            ->orderBy('nombre', 'desc')->get();
        return $liste;
    }
    public function listeDecesByMotifPeriode($debut, $fin){
        $date1 = Carbon::createFromFormat('d-m-Y', $debut);
        $date2 = Carbon::createFromFormat('d-m-Y', $fin);
        $liste =  Decede::where('deleted_at',null)
                        ->whereDate('decedes.date_deces','>=',$date1)
                        ->whereDate('decedes.date_deces','<=', $date2)
                        ->select('decedes.motif_deces', DB::raw('count(*) as nombre'))
                        ->groupBy('decedes.motif_deces')
                        ->orderBy('nombre', 'desc')->get();
        return $liste;
    }
    
    public function listeDecesByAn(){
        $liste = Decede::where('decedes.deleted_at',null)
                        ->select(DB::raw('count(*) as nombre'),DB::raw('DATE_FORMAT(decedes.date_deces, "%Y") as year'))
                        ->orderBy('year','desc')
                        ->groupBy('year')->get();
        return $liste;
    }
    public function listeDecesByMois(){
        $liste = Decede::where('decedes.deleted_at',null)
                        ->whereYear('date_deces', date('Y'))
                        ->select(DB::raw('count(*) as nombre'),DB::raw('DATE_FORMAT(decedes.date_deces, "%m") as month'))
                        ->groupBy('month')->get();
        return $liste;
    }
    public function listeDecesByMoisAnnee($annee){
        $liste = Decede::where('decedes.deleted_at',null)
                        ->whereYear('date_deces', $annee)
                        ->select(DB::raw('count(*) as nombre'),DB::raw('DATE_FORMAT(decedes.date_deces, "%m") as month'))
                        ->groupBy('month')->get();
        return $liste;
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
        if ($request->isMethod('post')) {
            $data = $request->all(); 
          
            try{
                //Vérification de doublon
                $deces_double = Decede::where('numero_acte_deces',$data['numero_acte_deces'])->whereDate('date_dresser',Carbon::createFromFormat('d-m-Y', $data['date_dresser']))->first();
                if($deces_double!=null){
                    throw new Exception("Ce numéro d'acte de décès existe déjà!");
                }
                
                //Gestion des messages concernant les champs obligatoires
                if(empty($data['nom_complet_decede'])){
                    throw new Exception("Le champs nom complet du défunt est vide!");
                }
                if(empty($data['numero_acte_deces'])){
                    throw new Exception("Le champs numéro acte du décès est vide!");
                }
                if(empty($data['date_deces'])){
                    throw new Exception("Le champs date du décès est vide!");
                }
                if(empty($data['date_dresser'])){
                    throw new Exception("Le champs date du dresser est vide!");
                }
                if(empty($data['registre'])){
                    throw new Exception("Le champs registre est vide!");
                }
                if(empty($data['date_retrait'])){
                    throw new Exception("Le champs date du retrait de la déclaration est vide!");
                }
                if(empty($data['date_declaration'])){
                    throw new Exception("Le champs date de la déclaration est vide!");
                }
                if(empty($data['nom_complet_declarant'])){
                    throw new Exception("Le champs nom du déclarant est vide!");
                }
                
                //La déclaration ne se fait pas avant le décès
                if(Carbon::createFromFormat('d-m-Y', $data['date_deces']) > Carbon::createFromFormat('d-m-Y', $data['date_declaration'])){
                    throw new Exception("La déclaration ne peut pas être faite avant le décès. Vérifier la date de déclaration et celle du décès");
                }
              
                //Enregistrement du deces
                $decede = new Decede;
                
                //Decedes
                $decede->nom_complet_decede = $data['nom_complet_decede'];
                $decede->numero_acte_deces = $data['numero_acte_deces'];
                $decede->date_deces = Carbon::createFromFormat('d-m-Y', $data['date_deces']);
                $decede->date_dresser = Carbon::createFromFormat('d-m-Y', $data['date_dresser']);
                $decede->registre = $data['registre'];
                $decede->sexe = $data['sexe'];
                $decede->heure_deces = isset($data['heure_deces']) && !empty($data['heure_deces']) ? $data['heure_deces']:null;
                $decede->nationalite = isset($data['nationalite']) && !empty($data['nationalite']) ? $data['nationalite']:null;
                $decede->date_naissance_decede = isset($data['date_naissance_decede']) && !empty($data['date_naissance_decede']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_decede']): null;
                $decede->motif_deces = isset($data['motif_deces']) && !empty($data['motif_deces']) ? $data['motif_deces'] : null;
                $decede->numero_acte_naissance_decede = isset($data['numero_acte_naissance_decede']) && !empty($data['numero_acte_naissance_decede']) ? $data['numero_acte_naissance_decede'] : null;
                $decede->fonction_id = isset($data['fonction_id']) && !empty($data['fonction_id']) ? $data['fonction_id'] : null;
                $decede->lieu_naissance_decede = isset($data['lieu_naissance_decede']) && !empty($data['lieu_naissance_decede']) ? $data['lieu_naissance_decede'] : null;
                $decede->lieu_deces = isset($data['lieu_deces']) && !empty($data['lieu_deces']) ? $data['lieu_deces'] : null;
                $decede->adresse_decede = isset($data['adresse_decede']) && !empty($data['adresse_decede']) ? $data['adresse_decede'] : null;
                
                //Parents
                $decede->nom_complet_pere = isset($data['nom_complet_pere']) && !empty($data['nom_complet_pere']) ? $data['nom_complet_pere'] : null;
                $decede->nom_complet_mere = isset($data['nom_complet_mere']) && !empty($data['nom_complet_mere']) ? $data['nom_complet_mere'] : null;
                $decede->adresse_pere = isset($data['adresse_pere']) && !empty($data['adresse_pere']) ? $data['adresse_pere'] : null;
                $decede->adresse_mere = isset($data['adresse_mere']) && !empty($data['adresse_mere']) ? $data['adresse_mere'] : null;

                //Déclarant 
                $decede->nom_complet_declarant = $data['nom_complet_declarant'];
                $decede->date_declaration = Carbon::createFromFormat('d-m-Y', $data['date_declaration']);
                $decede->date_retrait = Carbon::createFromFormat('d-m-Y', $data['date_retrait']);
                $decede->contact_declarant = isset($data['contact_declarant']) && !empty($data['contact_declarant']) ? $data['contact_declarant'] : null;
                $decede->adresse_declarant = isset($data['adresse_declarant']) && !empty($data['adresse_declarant']) ? $data['adresse_declarant'] : null;
                $decede->date_naissance_declarant = isset($data['date_naissance_declarant']) && !empty($data['date_naissance_declarant']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_declarant']) : null;
                $decede->fonction_declarant = isset($data['fonction_declarant']) && !empty($data['fonction_declarant']) ? $data['fonction_declarant'] : null;
                $decede->nombre_copie = isset($data['nombre_copie']) && !empty($data['nombre_copie']) ? $data['nombre_copie'] : 1;
                $decede->montant_declaration = isset($data['montant_declaration']) && !empty($data['montant_declaration']) ? $data['montant_declaration'] : 0;

                //Autres
                $decede->langue_reception = isset($data['langue_reception']) && !empty($data['langue_reception']) ? $data['langue_reception'] : null;
                $decede->traducteur = isset($data['traducteur']) && !empty($data['traducteur']) ? $data['traducteur'] : null;
                $decede->dressant = isset($data['dressant']) && !empty($data['dressant']) ? $data['dressant'] : null;
                
                //Ajout du scanne du PV s'il y a en 
                if(isset($data['scanne_pv']) && !empty($data['scanne_pv'])){
                    $scanne_pv = request()->file('scanne_pv');
                    $file_name = str_replace(' ', '_', strtolower(time().'.'.$scanne_pv->getClientOriginalName()));
                    $path = public_path().'/documents/pv_medecin/';
                    $scanne_pv->move($path,$file_name);
                    $decede->scanne_pv = 'documents/pv_medecin/'.$file_name;
                }
                $decede->created_by = Auth::user()->id;
                $decede->save();
                $jsonData["data"] = json_decode($decede);
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
     * @param  \App\Decede  $decede
     * @return Response
     */
    public function updateDecede(Request $request)
    {
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        $decede = Decede::find($request->get('idDecede'));
        if($decede){
            $data = $request->all(); 
            try{
                
                 //Gestion des messages concernant les champs obligatoires
                if(empty($data['nom_complet_decede'])){
                    throw new Exception("Le champs nom complet du défunt est vide!");
                }
                if(empty($data['numero_acte_deces'])){
                    throw new Exception("Le champs numéro acte du décès est vide!");
                }
                if(empty($data['date_deces'])){
                    throw new Exception("Le champs date du décès est vide!");
                }
                if(empty($data['date_dresser'])){
                    throw new Exception("Le champs date du dresser est vide!");
                }
                if(empty($data['registre'])){
                    throw new Exception("Le champs registre est vide!");
                }
                if(empty($data['date_retrait'])){
                    throw new Exception("Le champs date du retrait de la déclaration est vide!");
                }
                if(empty($data['date_declaration'])){
                    throw new Exception("Le champs date de la déclaration est vide!");
                }
                if(empty($data['nom_complet_declarant'])){
                    throw new Exception("Le champs nom du déclarant est vide!");
                }
                
                //La déclaration ne se fait pas avant le décès
                if(Carbon::createFromFormat('d-m-Y', $data['date_deces']) > Carbon::createFromFormat('d-m-Y', $data['date_declaration'])){
                    throw new Exception("La déclaration ne peut pas être faite avant le décès. Vérifier la date de déclaration et celle du décès");
                }
               //Decedes
                $decede->nom_complet_decede = $data['nom_complet_decede'];
                $decede->numero_acte_deces = $data['numero_acte_deces'];
                $decede->date_deces = Carbon::createFromFormat('d-m-Y', $data['date_deces']);
                $decede->date_dresser = Carbon::createFromFormat('d-m-Y', $data['date_dresser']);
                $decede->registre = $data['registre'];
                $decede->sexe = $data['sexe'];
                $decede->heure_deces = isset($data['heure_deces']) && !empty($data['heure_deces']) ? $data['heure_deces']:null;
                $decede->nationalite = isset($data['nationalite']) && !empty($data['nationalite']) ? $data['nationalite']:null;
                $decede->date_naissance_decede = isset($data['date_naissance_decede']) && !empty($data['date_naissance_decede']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_decede']): null;
                $decede->motif_deces = isset($data['motif_deces']) && !empty($data['motif_deces']) ? $data['motif_deces'] : null;
                $decede->numero_acte_naissance_decede = isset($data['numero_acte_naissance_decede']) && !empty($data['numero_acte_naissance_decede']) ? $data['numero_acte_naissance_decede'] : null;
                $decede->fonction_id = isset($data['fonction_id']) && !empty($data['fonction_id']) ? $data['fonction_id'] : null;
                $decede->lieu_naissance_decede = isset($data['lieu_naissance_decede']) && !empty($data['lieu_naissance_decede']) ? $data['lieu_naissance_decede'] : null;
                $decede->lieu_deces = isset($data['lieu_deces']) && !empty($data['lieu_deces']) ? $data['lieu_deces'] : null;
                $decede->adresse_decede = isset($data['adresse_decede']) && !empty($data['adresse_decede']) ? $data['adresse_decede'] : null;
                
                //Parents
                $decede->nom_complet_pere = isset($data['nom_complet_pere']) && !empty($data['nom_complet_pere']) ? $data['nom_complet_pere'] : null;
                $decede->nom_complet_mere = isset($data['nom_complet_mere']) && !empty($data['nom_complet_mere']) ? $data['nom_complet_mere'] : null;
                $decede->adresse_pere = isset($data['adresse_pere']) && !empty($data['adresse_pere']) ? $data['adresse_pere'] : null;
                $decede->adresse_mere = isset($data['adresse_mere']) && !empty($data['adresse_mere']) ? $data['adresse_mere'] : null;

                //Déclarant
                $decede->nom_complet_declarant = $data['nom_complet_declarant'];
                $decede->date_declaration = Carbon::createFromFormat('d-m-Y', $data['date_declaration']);
                $decede->date_retrait = Carbon::createFromFormat('d-m-Y', $data['date_retrait']);
                $decede->contact_declarant = isset($data['contact_declarant']) && !empty($data['contact_declarant']) ? $data['contact_declarant'] : null;
                $decede->adresse_declarant = isset($data['adresse_declarant']) && !empty($data['adresse_declarant']) ? $data['adresse_declarant'] : null;
                $decede->date_naissance_declarant = isset($data['date_naissance_declarant']) && !empty($data['date_naissance_declarant']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_declarant']) : null;
                $decede->fonction_declarant = isset($data['fonction_declarant']) && !empty($data['fonction_declarant']) ? $data['fonction_declarant'] : null;
                $decede->nombre_copie = isset($data['nombre_copie']) && !empty($data['nombre_copie']) ? $data['nombre_copie'] : 1;
                $decede->montant_declaration = isset($data['montant_declaration']) && !empty($data['montant_declaration']) ? $data['montant_declaration'] : 0;

                //Autres
                $decede->langue_reception = isset($data['langue_reception']) && !empty($data['langue_reception']) ? $data['langue_reception'] : null;
                $decede->traducteur = isset($data['traducteur']) && !empty($data['traducteur']) ? $data['traducteur'] : null;
                $decede->dressant = isset($data['dressant']) && !empty($data['dressant']) ? $data['dressant'] : null;
                
                //Ajout du scanne du PV s'il y a en 
                if(isset($data['scanne_pv']) && !empty($data['scanne_pv'])){
                    $scanne_pv = request()->file('scanne_pv');
                    $file_name = str_replace(' ', '_', strtolower(time().'.'.$scanne_pv->getClientOriginalName()));
                    $path = public_path().'/documents/pv_medecin/';
                    $scanne_pv->move($path,$file_name);
                    $decede->scanne_pv = 'documents/pv_medecin/'.$file_name;
                }
                
                $decede->updated_by = Auth::user()->id;
                $decede->save();
                $jsonData["data"] = json_decode($decede);
                return response()->json($jsonData);
            }catch(Exception $exc){
                $jsonData["code"] = -1;
                $jsonData["data"] = NULL;
                $jsonData["msg"] = $exc->getMessage();
                return response()->json($jsonData);
            }
        }
        return response()->json(["code" => 0, "msg" => "Saisie invalide", "data" => NULL]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Decede  $decede
     * @return Response
     */
    public function destroy(Decede $decede)
    {
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($decede){
                try {
                    $decede->update(['deleted_by' => Auth::user()->id]);
                    $decede->delete();
                    $jsonData["data"] = json_decode($decede);
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
    
    //Extrait d'act de décès
    public function extraitDeclarationDecesPdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->extraitDeclarationDeces($id));
        $deces = Decede::find($id);
        return $pdf->stream('acte_deces_'.$deces->numero_acte_deces.'.pdf');
    }
    public function extraitDeclarationDeces($id){
        $outPut = $this->content($id);
//        $outPut.= $this->footer();
        return $outPut;
    }
    public function content($id){
        $generator = new BarcodeGeneratorPNG();
        $deces = Decede::where([['decedes.deleted_at', NULL],['decedes.id',$id]]) 
                ->leftjoin('fonctions', 'fonctions.id','=','decedes.fonction_id')
                ->leftjoin('nations', 'nations.id','=','decedes.nationalite')
                ->select('decedes.*','nations.libelle_nation','fonctions.libelle_fonction')
                ->orderBy('decedes.id', 'DESC')
                ->first();
        $month = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
        $dateNdecede = $deces->date_naissance_decede;
        $date = $deces->date_deces;
        $day = date('d', strtotime($date));
        $mont = date('m', strtotime($date));
        $an = date('Y', strtotime($date));
        $heureDeces = date('H', strtotime($deces->heure_deces));
        $minDeces = date('i', strtotime($deces->heure_deces));
    
        $dayN = date('d', strtotime($dateNdecede));
        $montN = date('m', strtotime($dateNdecede));
        $anN = date('Y', strtotime($dateNdecede));
       
        $day == 01 ? $jour = 'premier' : $jour = NumberToLetter(number_format($day));
        $dayN == 01 ? $jourN = 'premier' : $jourN = NumberToLetter(number_format($dayN));
        
        if($heureDeces < 10){
            $heureD = NumberToLetter(number_format(substr($heureDeces,1,1)));
            if($heureD == "un"){
                $heureD = "une";
            }
        }else{
            $heureD = NumberToLetter(number_format($heureDeces));
            if($heureD == "vingt et un"){
                $heureD = "vingt et une";
            }
        }
        if($minDeces < 10){
            $minuteD = NumberToLetter(number_format(substr($minDeces,1,1)));
             if($minuteD == "un"){
                $minuteD = "une";
            }
        }else{
            $minuteD = NumberToLetter(number_format($minDeces));
            if($minuteD == "vingt et un"){
                $minuteD = "vingt et une";
            }
            if($minuteD == "trente et un"){
                $minuteD = "trente et une";
            }
            if($minuteD == "quarante et un"){
                $minuteD = "quarante et une";
            }
            if($minuteD == "cinquante et un"){
                $minuteD = "cinquante et une";
            }
        }
       
        $deces->sexe == 'Masculin' ? $sexe = 'Fils' : $sexe = 'Fille';
        $deces->sexe == 'Masculin' ? $decede = 'décédé' : $decede = 'décédée';
        $deces->sexe == 'Masculin' ? $ne = 'né' : $ne = 'née';
        !empty($deces->nom_complet_pere)? $pere = $deces->nom_complet_pere : $pere='';
        !empty($deces->nom_complet_mere)? $mere = $deces->nom_complet_mere : $mere='';
        
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);
        $nom_initial = strstr(Auth::user()->full_name, ' ', true); 
        $content = "<html>
                        <head>
                            <meta charset='utf-8'>
                            <title></title>
                                    <style>
                                        .container-table{        
                                            margin:135px 0;
                                            width: 100%;
                                        }
                                        .container{
                                            width: 100%;
                                            margin: 0 5px;
                                            font-size:15px;
                                           
                                        }
                                        .fixed-header-left{
                                            width: 40%;
                                            height:7%;
                                            position: absolute; 
                                            top: 0;
                                            padding: 10px 0;
                                            text-align:center;
                                        }
                                        .fixed-header-right{
                                            width: 60%;
                                            height:7%;
                                            float: right;
                                            position: absolute;
                                            top: 0;
                                            padding: 10px 0;
                                        }
                                        .fixed-footer{
                                            position: fixed; 
                                            bottom: -40; 
                                            left: 0px; 
                                            right: 0px;
                                            height: 50px; 
                                            text-align:center;
                                        }     
                                      
                                    </style>
                        </head>
                <body>
                <div class='fixed-header-left'>
                    <p>
                        <b>COMMUNE ".strtoupper($this->premierLetre()."".$commune)."<hr width='50'/></b>
                        <img src=".$this->infosConfig()->logo." width='150' height='140'><br/>
                        <b> Mairie ".$this->premierLetre()."".$this->infosConfig()->commune."</b><br/>";
                         if ($this->infosConfig()->adresse_marie != null) {
                            $content .= "Adresse: " . $this->infosConfig()->adresse_marie . "<br/>";
                        }
                        if ($this->infosConfig()->telephone_mairie != null) {
                            $content .= "Tel : " . $this->infosConfig()->telephone_mairie . "<br/>";
                        }
                        if ($this->infosConfig()->fax_mairie != null) {
                            $content .= "Fax : " . $this->infosConfig()->fax_mairie . "<br/>";
                        }
                        if ($this->infosConfig()->site_web_mairie != null) {
                            $content .= "" . $this->infosConfig()->site_web_mairie . "<br/> ";
                        }
        $content .="</p>
                    <br/>
                   <p style='font-size:20;'><b> ETAT - CIVIL</b><p>
                  <br/>
                    <p style='line-height:1.5; align:left;'>
                    <hr width='90%'/>
                    <b>N° ".$deces->numero_acte_deces." DU ".date("d-m-Y", strtotime($deces->date_dresser))."</b> du registre
                     <hr width='90%'/></p>
                    <p style='line-height:1.5; text-align:left;'>
                    <b>&nbsp;&nbsp;&nbsp;&nbsp;DECES DE :</b> <br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;".$deces->nom_complet_decede." <br/></p>";
                  
            $content.="</div>
                <div class='fixed-header-right'>
                        <span style='opacity:0.35;font-style: italic;'>".$nom_initial."</span> 
                    <div style='text-align:center;'>
                            REPUBLIQUE DE COTE D'IVOIRE<br/>Union-Discipline-Travail<hr width='50'/>
                           <span style='font-size:40px; font-weight: bold;'> EXTRAIT </span>
                        <p>DU REGISTRE DES ACTES DE L'ETAT CIVIL POUR L'ANNEE <b>".$deces->registre."</b></p><br/>
                    </div>
                    <div style='line-height:2;'>
                        Le <b>".$jour." ".$month[$mont]." ".NumberToLetter($an)."</b>";
                        if($deces->heure_deces!=null){
                        $content.="<br/> à <b>".$heureD." heure(s) ".$minuteD." minute(s)</b>";
                        }
                        $content.="<br/>est ".$decede." à <b>".$deces->lieu_deces." <br/>  ".$deces->nom_complet_decede."</b><br/>
                        ".$ne." à <b>".$deces->lieu_naissance_decede."</b> <br/>le <b>".$jourN." ".$month[$montN]." ".NumberToLetter($anN)."</b><br/>
                        Profession <b>".$deces->libelle_fonction."</b><br/>Nationalité : <b>".$deces->libelle_nation."</b><br/>
                        ".$sexe." de <b>".$pere."</b> <br/> et de <b>".$mere."</b><br/><br/><br/>
                    </div>    
                </div><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                <div class='container'>
                    <br/>
                    <p style='float:right;'><i>Délivré à <b>".$this->infosConfig()->commune."</b>, le <b>".date("d")."-".$month[date("m")]."-".date("Y")."</b></p>
                        <br/><br/>
                    <p style='text-align:right;'>
                        L'Officier de l’Etat Civil<br/>
                        (Signature)
                    </p>";
                    $content.="<br/><br/><p style='float:left;'><img src='data:image/png;base64,".base64_encode($generator->getBarcode(123456789, $generator::TYPE_CODE_128))."'></p>"; 

                $content.=" </div>
            </body>
        </html>";     
     return $content;
    }
    
    //Copie intégrale du décès
    public function extraitCopieIntegralePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->extraitCopieIntegrale($id));
        $decede = Decede::find($id);
        return $pdf->stream('copie_integrale_acte_deces_'.$decede->numero_acte_deces.'.pdf');
    }
    public function extraitCopieIntegrale($id){
        $decede = Decede::where([['decedes.deleted_at', NULL],['decedes.id',$id]]) 
                            ->leftjoin('fonctions as fonctionDefunt', 'fonctionDefunt.id','=','decedes.fonction_id')
                            ->leftjoin('fonctions as fonctionDeclarant', 'fonctionDeclarant.id','=','decedes.fonction_declarant')
                            ->leftjoin('nations', 'nations.id','=','decedes.nationalite')
                            ->select('decedes.*','fonctionDefunt.libelle_fonction as libelle_fonction_defunt','fonctionDeclarant.libelle_fonction as libelle_fonction_declarant',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y %H:%i") as date_deces'))
                            ->first();
       
        $month = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
        $date = $decede->date_deces;
        $dateDr = $decede->date_dresser;
        $dateN = $decede->date_naissance_decede;
        $day = date('d', strtotime($date));
        $mont = date('m', strtotime($date));
        $an = date('Y', strtotime($date));
        
        $daydr = date('d', strtotime($dateDr));
        $montdr = date('m', strtotime($dateDr));
        $andr = date('Y', strtotime($dateDr));
        
        $dayN = date('d', strtotime($dateN));
        $montN = date('m', strtotime($dateN));
        $anN = date('Y', strtotime($dateN));
        
        $day == 01 ? $jour = 'premier' : $jour = NumberToLetter(number_format($day));
        $daydr == 01 ? $jourdr = 'premier' : $jourdr = NumberToLetter(number_format($daydr));
        $dayN == 01 ? $jourN = 'premier' : $jourN = NumberToLetter(number_format($dayN));
        
        $heureDeces = date('H', strtotime($decede->heure_deces));
        $minDeces = date('i', strtotime($decede->heure_deces));
       
        if($heureDeces < 10){
            $heureD = NumberToLetter(number_format(substr($heureDeces,1,1)));
            if($heureD == "un"){
                $heureD = "une";
            }
        }else{
           
            $heureD = NumberToLetter(number_format($heureDeces));
            if($heureD == "vingt et un"){
                $heureD = "vingt et une";
            }
           
        }
        if($minDeces < 10){
            $minuteD = NumberToLetter(number_format(substr($minDeces,1,1)));
            if($minuteD == "un"){
                $minuteD = "une";
            }
        }else{
            $minuteD = NumberToLetter(number_format($minDeces));
            if($minuteD == "vingt et un"){
                $minuteD = "vingt et une";
            }
            if($minuteD == "trente et un"){
                $minuteD = "trente et une";
            }
            if($minuteD == "quarante et un"){
                $minuteD = "quarante et une";
            }
            if($minuteD == "cinquante et un"){
                $minuteD = "cinquante et une";
            }
        }
       
        !empty($decede->nom_complet_pere)? $pere = $decede->nom_complet_pere : $pere='................................................................................';
        !empty($decede->nom_complet_mere)? $mere = $decede->nom_complet_mere : $mere='................................................................................';
        $decede->sexe == "Masculin" ? $ne = "Né" : $ne = "Née";
        $aujourdhui = date("Y-m-d");
        $age_declarant=""; 
        if($decede->date_naissance_declarant!=null){
            $dateNaissanceDecl = $decede->date_naissance_declarant;
            $diffD = date_diff(date_create($dateNaissanceDecl), date_create($aujourdhui));
            $age_d = $diffD->format('%y');
            $age_declarant = $age_d." ans";
        }
        if($decede->date_naissance_decede!=null){
            $date_naissance_defunt = $jourN." ".$month[$montN]." ".NumberToLetter($anN);
        }else{
            $date_naissance_defunt = ".........................................................................................................................";
        }
       
     
        $libelle_fonction_declarant="";
        if($decede->libelle_fonction_declarant!=null){
            $libelle_fonction_declarant = $decede->libelle_fonction_declarant." à ".$decede->adresse_declarant;
        }
        if($decede->heure_deces!=null) {
            $heure_deces_copie_integrale = $heureD." heure(s) ".$minuteD." minute(s)";
        }else{
            $heure_deces_copie_integrale = "...............................................................................................................................";
        }
        if($decede->lieu_deces!=null) {
            $lieu_deces = $decede->lieu_deces;
        }else{
            $lieu_deces = "........................................................................................................";
        }
        if($decede->libelle_fonction_defun!=null) {
            $libelle_fonction_defun = $decede->libelle_fonction_defun;
        }else{
            $libelle_fonction_defun = ".................................................................................................................";
        }
        if($decede->adresse_decede!=null) {
            $adresse_decede = $decede->adresse_decede;
        }else{
            $adresse_decede = "...........................................................................................................";
        }
        if($decede->lieu_naissance_decede!=null) {
            $lieu_naissance_decede = $decede->lieu_naissance_decede;
        }else{
            $lieu_naissance_decede = ".................................................................................................................................";
        }
        if($decede->nom_complet_pere!=null) {
            $nom_complet_pere = $decede->nom_complet_pere;
        }else{
            $nom_complet_pere = "..............................................................................................................................";
        }
        if($decede->adresse_pere!=null) {
            $adresse_pere = $decede->adresse_pere;
        }else{
            $adresse_pere = "..............................................................................................................";
        }
        if($decede->nom_complet_mere!=null) {
            $nom_complet_mere = $decede->nom_complet_mere;
        }else{
            $nom_complet_mere = "..........................................................................................................................";
        }
        if($decede->adresse_mere!=null) {
            $adresse_mere = $decede->adresse_mere;
        }else{
            $adresse_mere = "..............................................................................................................";
        }
        if($decede->langue_reception!=null) {
            $langue_reception = $decede->langue_reception;
        }else{
            $langue_reception = "..........................................................................................................";
        }
        if($decede->traducteur!=null) {
            $traducteur = $decede->traducteur;
        }else{
            $traducteur = "...................................................................................................";
        }
        if($decede->dressant!=null) {
            $dressant = $decede->dressant;
        }else{
            $dressant = "......................................................................................................................";
        }
        
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);
     
         $out_put = "<html>
                <head>
                    <style>
                        .fixed-header-left{
                            width:35%;
                            height:20%;
                            text-align:left;
                            position: absolute; 
                            float: left;
                        }
                        .fixed-header-right{
                            width: 65%;
                            height:20%;
                            text-align:center;
                            position: absolute;
                            float: right;
                        }
                        .fixed-content-left{
                            font-size:15px;
                            position: absolute; 
                            top: 0;
                            line-height:1.8;
                            margin:205px 0;
                            width: 25%;
                            padding: 20px 0;
                        }
                        .fixed-content-right{
                            font-size:15px;
                            line-height:1.6;
                            margin:205px 0;
                            position: absolute; 
                            width: 75%;
                            left: 26%;
                            top: 0;
                            padding: 20px 0;
                        }
                        .line-vertical{
                            border-left: 1px solid;
                            height:58%;
                            position: absolute;
                            margin:225px 150px;
                            left: 25%;
                            margin-left: -3px;
                        }
                        .line-horizontal{
                            border-top: 1px solid #000;
                            width:1400px;
                            position: absolute;
                            margin:225px 0;
                        }

                    </style>
                </head>
            <body>
                <div class='fixed-header-left'>
                    <b>COMMUNE ".strtoupper($this->premierLetre()."".$commune)."</b><br/>
                   <img src=".$this->infosConfig()->logo." width='100' height='100'><br/> 
                    <b> Mairie ".$this->premierLetre()."".$this->infosConfig()->commune."</b><br/>";
                    if($this->infosConfig()->adresse_marie != null) {
                        $out_put .= "Adresse: " . $this->infosConfig()->adresse_marie . "<br/>";
                    }
                    if ($this->infosConfig()->telephone_mairie != null) {
                        $out_put .= "Tel : " . $this->infosConfig()->telephone_mairie . "<br/>";
                    }
                    if ($this->infosConfig()->fax_mairie != null) {
                        $out_put .= "Fax : " . $this->infosConfig()->fax_mairie . "<br/>";
                    }
                    if ($this->infosConfig()->site_web_mairie != null) {
                        $out_put .= "".$this->infosConfig()->site_web_mairie . "<br/> ";
                    }
                $out_put.="</div>
                    <div class='fixed-header-right'>
                       <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                        Union-Discipline-Travail<hr width='50'/></b>
                        <br/>
                        <p style='font-size:25px;'><b><u>COPIE INTEGRALE D'EXTRAIT D'ACTE DE DECES</u></b></p>
                    </div>";
                $out_put.="<div class='line-horizontal'></div>";
                $out_put.="<div class='fixed-content-left'>
                        <b>Act N° ".$decede->numero_acte_deces." du ".date('d', strtotime($decede->date_dresser))." ".$month[date('m', strtotime($decede->date_dresser))]." ".date('Y', strtotime($decede->date_dresser))."</b><br/>
                        Décès de :<br/>
                        <b>".$decede->nom_complet_decede."</b></div>";
        $out_put.="<div class='line-vertical'></div>";
        $out_put.="<div class='fixed-content-right'>
                    1. Le <b>".$jour." ".$month[$mont]." ".NumberToLetter($an)."</b><br/>
                    2. à <b>".$heure_deces_copie_integrale."</b><br/>
                    3. est décédé(e) à <b>".$lieu_deces."</b><br/>
                    4. <b>".$decede->nom_complet_decede."</b><br/>
                    5. Profession <b>".$libelle_fonction_defun."</b><br/>
                    6. Domicilié(e) à <b>".$adresse_decede."</b><br/>
                    7. ".$ne." le <b>".$date_naissance_defunt."</b><br/>
                    8. à <b>".$lieu_naissance_decede."</b><br/>
                    9. De <b>".$nom_complet_pere."</b><br/>
                    10. Domicilié à <b>".$adresse_pere."</b><br/>
                    11. et de <b>".$nom_complet_mere."</b><br/>
                    12. Domiciliée à <b>".$adresse_mere."</b><br/>
                    13. Dressé le <b>".$jourdr." ".$month[$montdr]." ".NumberToLetter($andr)."</b><br/>
                    14. Sur la déclaration de <b>".$decede->nom_complet_declarant."</b><br/>
                    15. Reçu en langue <b>".$langue_reception."</b><br/>
                    16. Avec l'assistance de <b>".$traducteur."</b><br/> Interprète, ayant prêté devant Nous le serment prévu par la loi.<br/>
                    17. Par nous <b>".$dressant."</b><br/>
                    18. Après que le déclarant ai été avertis des peines sanctionnant les fausses déclarations.<br/>
                    19. Lecture faite, et le déclarant invité à lire l'acte.<br/>
                    20. L'acte ayant été traduit par l'intérprête<br/>
                    21. Nous avons signé avec les déclarants<br/><br/>
                    <p style='float:right;'>".$this->infosConfig()->commune.", le ".date("d")." ".$month[date("m")]." ".date("Y")."&nbsp;&nbsp;&nbsp;<br/>
                        Pour copie certifier conforme&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>
                        L'Officier de l'Etat-Civil&nbsp;&nbsp;&nbsp;
                    </p>
                </div>";
        return $out_put;
    }

    //Etat des décès par motif
    public function ficheDecesMotifPdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheDecesMotif());
        return $pdf->stream('liste_deces_par_motif.pdf');
    }
    public function ficheDecesMotif(){
        $datas = $this->listeDecesByMotif();
        $outPut = $this->headerFiche();
         $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des décès par motif en '.date("Y").'</u></h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="45%" >Motif</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Nombre</th>
                        </tr></div>';
         $total = 0;
       foreach ($datas as $data){
           $total = $total + $data->nombre;
           $data->motif_deces != null ? $motif_deces = $data->motif_deces : $motif_deces = "Inconnu";
           $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="center">'.$motif_deces.'</td>
                            <td  cellspacing="0" border="2" align="center">'.number_format($data->nombre, 0, ',', ' ').'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale :<b> '.number_format($total, 0, ',', ' ').' décès</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Etat des décès par motif sur une période
    public function ficheDecesParMotifPeriodePdf($debu,$fin){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheDecesParMotifPeriode($debu,$fin));
        return $pdf->stream('liste_deces_par_motif_du_'.$debu.'_au_'.$fin.'.pdf');
    }
    public function ficheDecesParMotifPeriode($debu,$fin){
        $datas = $this->listeDecesByMotifPeriode($debu,$fin);
        $outPut = $this->headerFiche();
         $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des décès par motif du '.$debu.' au '.$fin.'</u></h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="45%" >Motif</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Nombre</th>
                        </tr></div>';
         $total = 0;
       foreach ($datas as $data){
           $total = $total + $data->nombre;
           $data->motif_deces != null ? $motif_deces = $data->motif_deces : $motif_deces = "Inconnu";
           $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="center">'.$motif_deces.'</td>
                            <td  cellspacing="0" border="2" align="center">'.number_format($data->nombre, 0, ',', ' ').'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale :<b> '.number_format($total, 0, ',', ' ').' décès</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }

    //Etat des décès par lieu
    public function ficheDecesLieuxPdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheDecesLieux());
        return $pdf->stream('liste_deces_par_lieu.pdf');
    }
    public function ficheDecesLieux(){
        $datas = $this->listeDecesByLieux();
        $outPut = $this->headerFiche();
         $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des décès par lieu au cours de cette année '.date("Y").'</u></h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="45%" >Lieu</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Nombre</th>
                        </tr></div>';
         $total = 0;
       foreach ($datas as $data){
           $total = $total + $data->nombre;
           $data->lieu_deces != null ? $lieu_deces = $data->lieu_deces : $lieu_deces = "Inconnu";
           $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="center">'.$lieu_deces.'</td>
                            <td  cellspacing="0" border="2" align="center">'.number_format($data->nombre, 0, ',', ' ').'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale :<b> '.number_format($total, 0, ',', ' ').' décès</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Etat décès par lieu sur une période
    public function ficheDecesParLieuPeriodePdf($debu,$fin){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheDecesParLieuPeriode($debu,$fin));
        return $pdf->stream('liste_deces_par_lieu_du_'.$debu.'_au_'.$fin.'.pdf');
    }
    public function ficheDecesParLieuPeriode($debu,$fin){
        $datas = $this->listeDecesByLieuPeriode($debu,$fin);
        $outPut = $this->headerFiche();
         $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des décès par lieu du '.$debu.' au '.$fin.'</u></h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="45%" >Lieu</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Nombre</th>
                        </tr></div>';
         $total = 0;
       foreach ($datas as $data){
           $total = $total + $data->nombre;
           $data->lieu_deces != null ? $lieu_deces = $data->lieu_deces : $lieu_deces = "Inconnu";
           $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="center">'.$lieu_deces.'</td>
                            <td  cellspacing="0" border="2" align="center">'.number_format($data->nombre, 0, ',', ' ').'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale :<b> '.number_format($total, 0, ',', ' ').' décès</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }

    //Etat décès par an
    public function ficheDecesParAnPdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheDecesParAn());
        return $pdf->stream('liste_deces_par_an.pdf');
    }
    public function ficheDecesParAn(){
        $datas = $this->listeDecesByAn();
        $outPut = $this->headerFiche();
         $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des décès par année </u></h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="45%" >Année</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Nombre</th>
                        </tr></div>';
         $total = 0;
       foreach ($datas as $data){
           $total = $total + $data->nombre;
           $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="center">'.$data->year.'</td>
                            <td  cellspacing="0" border="2" align="center">'.number_format($data->nombre, 0, ',', ' ').'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale :<b> '.number_format($total, 0, ',', ' ').' décès</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Etat décès par mois
    public function ficheDecesParMoisPdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheDecesParMois());
        return $pdf->stream('liste_deces_par_mois.pdf');
    }
    public function ficheDecesParMois(){
        $datas = $this->listeDecesByMois();
        $moisFr = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
        $outPut = $this->headerFiche();
         $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des décès par mois en '.date("Y").'</u></h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="45%" >Mois</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Nombre</th>
                        </tr></div>';
         $total = 0;
       foreach ($datas as $data){
           $total = $total + $data->nombre;
           $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="center">'.$moisFr[$data->month].'</td>
                            <td  cellspacing="0" border="2" align="center">'.number_format($data->nombre, 0, ',', ' ').'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale :<b> '.number_format($total, 0, ',', ' ').' décès</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Etat décès par mois sur une année
    public function ficheDecesParMoisAnneePdf($annee){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheDecesParMoisAnnee($annee));
        return $pdf->stream('liste_deces_par_mois_en_'.$annee.'.pdf');
    }
    public function ficheDecesParMoisAnnee($annee){
        $datas = $this->listeDecesByMoisAnnee($annee);
        $moisFr = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
        $outPut = $this->headerFiche();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste de tous les décès de '.$annee.' détaillés par mois</u></h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="45%">Mois</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Nombre</th>
                        </tr></div>';
         $total = 0;
       foreach ($datas as $data){
           $total = $total + $data->nombre;
           $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="center">'.$moisFr[$data->month].'</td>
                            <td  cellspacing="0" border="2" align="center">'.number_format($data->nombre, 0, ',', ' ').'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale en '.$annee.':<b> '.number_format($total, 0, ',', ' ').' décès</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }

    //Header and footer des pdf pour les listes dans tableau
    public function headerFiche(){
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);
     
        $header = '<html>
                    <head>
                        <style>
                          @page{
                                margin: 70px 25px;
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
                                            margin:125px 0;
                                            width: 100%;
                                        }
                            .fixed-footer{.
                                width : 100%;
                                position: fixed; 
                                bottom: -28; 
                                left: 0px; 
                                right: 0px;
                                height: 30px; 
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
                    .<script type="text/php">
                    if (isset($pdf)){
                        $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
                        $size = 10;
                        $font = $fontMetrics->getFont("Verdana");
                        $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
                        $x = ($pdf->get_width() - $width) / 2;
                        $y = $pdf->get_height() - 35;
                        $pdf->page_text($x, $y, $text, $font, $size);
                    }
                </script>
        <body>
        <header>
            <div class="fixed-header-left">
             <b>COMMUNE '.strtoupper($this->premierLetre().''.$commune).'</b><br/>
                   <img src='.$this->infosConfig()->logo.' width="100" height="100"><br/> 
                    <b> Mairie '.$this->premierLetre().''.$this->infosConfig()->commune.'</b><br/>';
                    if($this->infosConfig()->adresse_marie != null) {
                        $header .= 'Adresse: '.$this->infosConfig()->adresse_marie.'<br/>';
                    }
                    if ($this->infosConfig()->telephone_mairie != null) {
                        $header .='Tel : '.$this->infosConfig()->telephone_mairie.'<br/>';
                    }
                    if ($this->infosConfig()->fax_mairie != null) {
                        $header .='Fax : '.$this->infosConfig()->fax_mairie.'<br/>';
                    }
                    if ($this->infosConfig()->site_web_mairie != null) {
                        $header.=''.$this->infosConfig()->site_web_mairie.'<br/> ';
                    }
                $header.='</div>
                    <div class="fixed-header-right">
                       <b> REPUBLIQUE DE COTE D\'IVOIRE<br/> 
                        Union-Discipline-Travail<hr width="50"/></b>
                    </div>
        </header>';   
        return $header;
    }
    public function footerFiche(){
        $footer ="<div class='fixed-footer'>
                        <div class='page-number'></div>
                    </div>
                    <div class='fixed-footer-right'>
                     <i> Editer le ".date('d-m-Y')."</i>
                    </div>
            </body>
        </html>";
        return $footer;
    }
}
