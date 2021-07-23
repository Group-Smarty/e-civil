<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\Mariage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Picqer\Barcode\BarcodeGeneratorPNG;

include_once(app_path ()."/number-to-letters/nombre_en_lettre.php");

class MariageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    { 
       $regimes = DB::table('regimes')->Where('deleted_at', NULL)->orderBy('libelle_regime', 'asc')->get();
       $fonctions = DB::table('fonctions')->Where('deleted_at', NULL)->orderBy('libelle_fonction', 'asc')->get();
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Déclaration de mariage";
       $btnModalAjout = "TRUE";
       return view('ecivil.mariage.index',compact('regimes','fonctions','btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }
    
    public function vueProchainMariage(){
       $moisFr = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Liste des futurs mariages";
       $btnModalAjout = "FALSE";
       return view('ecivil.mariage.prochains-mariages',compact('moisFr','btnModalAjout', 'menuPrincipal', 'titleControlleur')); 

    }

    public function listeMariage()
    {
        $mariages = Mariage::with('regime','fonction_homme','fonction_femme','fonction_declarant','fonction_temoin_1','fonction_temoin_2')
                            ->select('mariages.*',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(mariages.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(mariages.date_naissance_femme, "%d-%m-%Y") as date_naissance_femmes'),DB::raw('DATE_FORMAT(mariages.date_naissance_homme, "%d-%m-%Y") as date_naissance_hommes'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                            ->Where('mariages.deleted_at', NULL) 
                            ->orderBy('mariages.id', 'DESC')
                            ->get();
       $jsonData["rows"] = $mariages->toArray();
       $jsonData["total"] = $mariages->count();
       return response()->json($jsonData);
    }

    public function listeMariagesByNames($name){
        $mariages = Mariage::with('regime','fonction_homme','fonction_femme','fonction_declarant','fonction_temoin_1','fonction_temoin_2')
                            ->Where([['mariages.deleted_at', NULL],['mariages.nom_complet_homme','like','%'.$name.'%']]) 
                            ->orWhere([['mariages.deleted_at', NULL],['mariages.nom_complet_femme','like','%'.$name.'%']]) 
                            ->select('mariages.*',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(mariages.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(mariages.date_naissance_femme, "%d-%m-%Y") as date_naissance_femmes'),DB::raw('DATE_FORMAT(mariages.date_naissance_homme, "%d-%m-%Y") as date_naissance_hommes'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                            ->orderBy('mariages.id', 'DESC')
                            ->get();
       $jsonData["rows"] = $mariages->toArray();
       $jsonData["total"] = $mariages->count();
       return response()->json($jsonData);
    }
    
    public function listeMariagesByNumeroActe($numero_acte){
        $mariages = Mariage::with('regime','fonction_homme','fonction_femme','fonction_declarant','fonction_temoin_1','fonction_temoin_2')
                            ->Where([['mariages.deleted_at', NULL],['mariages.numero_acte_mariage','like','%'.$numero_acte.'%']]) 
                            ->select('mariages.*',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(mariages.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(mariages.date_naissance_femme, "%d-%m-%Y") as date_naissance_femmes'),DB::raw('DATE_FORMAT(mariages.date_naissance_homme, "%d-%m-%Y") as date_naissance_hommes'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                            ->orderBy('mariages.id', 'DESC')
                            ->get();
       $jsonData["rows"] = $mariages->toArray();
       $jsonData["total"] = $mariages->count();
       return response()->json($jsonData);
    }
    
    public function listeMariagesByDate($dates){
        $date = Carbon::createFromFormat('d-m-Y', $dates);
        $mariages = Mariage::with('regime','fonction_homme','fonction_femme','fonction_declarant','fonction_temoin_1','fonction_temoin_2')
                            ->select('mariages.*',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(mariages.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(mariages.date_naissance_femme, "%d-%m-%Y") as date_naissance_femmes'),DB::raw('DATE_FORMAT(mariages.date_naissance_homme, "%d-%m-%Y") as date_naissance_hommes'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                            ->Where('mariages.deleted_at', NULL) 
                            ->whereDate('mariages.date_mariage','=', $date)
                            ->orderBy('mariages.id', 'DESC')
                            ->get();
       $jsonData["rows"] = $mariages->toArray();
       $jsonData["total"] = $mariages->count();
       return response()->json($jsonData);
    }
    
    public function findActeMariageById($id){
        $mariages = Mariage::with('regime','fonction_homme','fonction_femme')
                            ->Where([['mariages.deleted_at', NULL],['mariages.id',$id]]) 
                            ->select('mariages.*',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_naissance_femme, "%d-%m-%Y") as date_naissance_femmes'),DB::raw('DATE_FORMAT(mariages.date_naissance_homme, "%d-%m-%Y") as date_naissance_hommes'))
                            ->get();
       $jsonData["rows"] = $mariages->toArray();
       $jsonData["total"] = $mariages->count();
       return response()->json($jsonData);
    }

    public function listeProchainMariages(){
        $toDays = date("Y-m-d");
        $liste = Mariage::with('regime')
                          ->select('mariages.*',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(mariages.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(mariages.date_naissance_femme, "%d-%m-%Y") as date_naissance_femmes'),DB::raw('DATE_FORMAT(mariages.date_naissance_homme, "%d-%m-%Y") as date_naissance_hommes'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                          ->Where('mariages.deleted_at', NULL) 
                          ->whereDate('mariages.date_mariage','>', $toDays)
                          ->orderBy('mariages.id', 'DESC')->get(); 
        return $liste;
    }
    
    public function listeProchainMariagesParMois($mois){
        $moisFr = ['Janvier'=>'01','Février'=>'02','Mars'=>'03','Avril'=>'04','Mai'=>'05','Juin'=>'06','Juillet'=>'07','Août'=>'08','Septembre'=>'09','Octobre'=>'10','Novembre'=>'11','Decembre'=>'12'];
        $toDays = date("Y-m-d");
        $liste = Mariage::with('regime')
                         ->whereMonth('mariages.date_mariage',$moisFr[$mois])
                         ->whereDate('mariages.date_mariage','>',$toDays)
                         ->Where('mariages.deleted_at', NULL) 
                         ->select('mariages.*',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(mariages.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(mariages.date_naissance_femme, "%d-%m-%Y") as date_naissance_femmes'),DB::raw('DATE_FORMAT(mariages.date_naissance_homme, "%d-%m-%Y") as date_naissance_hommes'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                         ->orderBy('mariages.id', 'DESC')->get(); 
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
        if ($request->isMethod('post') &&  $request->input('nom_complet_homme') && $request->input('nom_complet_femme')) {
            $data = $request->all(); 
          
            try{
                //Vérification de doublon
                $mariage_double = Mariage::where('numero_acte_mariage',$data['numero_acte_mariage'])->whereDate('date_dresser',Carbon::createFromFormat('d-m-Y', $data['date_dresser']))->first();
                if($mariage_double!=null){
                    throw new Exception("Ce numéro d'acte de mariage existe déjà!");
                }
                
                //Gestion des messages concernant les champs obligatoires
                if(empty($data['numero_acte_mariage'])){
                    throw new Exception("Le champs numero acte de mariage est vide!");
                }
                if(empty($data['registre'])){
                    throw new Exception("Le champs registre est vide!");
                }
                if(empty($data['date_dresser'])){
                    throw new Exception("Le champs date du dresser est vide!");
                }
                if(empty($data['date_mariage'])){
                    throw new Exception("Le champs date de mariage est vide!");
                }
                if(empty($data['nom_complet_homme'])){
                    throw new Exception("Le champs nom de l'homme est vide!");
                }
                if(empty($data['nom_complet_femme'])){
                    throw new Exception("Le champs nom de la femme est vide!");
                }
                if(empty($data['nom_complet_declarant'])){
                    throw new Exception("Le champs nom du déclarant est vide!");
                }
                if(empty($data['date_declaration'])){
                    throw new Exception("Le champs date de déclaration est vide!");
                }
                if(empty($data['date_retrait'])){
                    throw new Exception("Le champs date du retrait de la déclaration est vide!");
                }
                
                //Enregistrement
                $mariage = new Mariage;
                
                //Mariage
                $mariage->numero_acte_mariage = $data['numero_acte_mariage'];
                $mariage->registre = $data['registre'];
                $mariage->date_dresser = Carbon::createFromFormat('d-m-Y', $data['date_dresser']);
                $mariage->date_mariage = Carbon::createFromFormat('d-m-Y H:i', $data['date_mariage']);
                $mariage->regime_id = isset($data['regime_id']) && !empty($data['regime_id']) ? $data['regime_id'] : Null;

                //Epoux 
                $mariage->nom_complet_homme = $data['nom_complet_homme'];
                $mariage->adresse_domicile_homme = isset($data['adresse_domicile_homme']) && !empty( $data['adresse_domicile_homme'])? $data['adresse_domicile_homme']:null;
                $mariage->fonction_homme = isset($data['fonction_homme']) && !empty( $data['fonction_homme'])? $data['fonction_homme']:null;
                $mariage->numero_acte_naissance_homme = isset($data['numero_acte_naissance_homme']) && !empty( $data['numero_acte_naissance_homme'])? $data['numero_acte_naissance_homme']:null;
                $mariage->lieu_naissance_homme = isset($data['lieu_naissance_homme']) && !empty( $data['lieu_naissance_homme'])? $data['lieu_naissance_homme']:null;
                $mariage->date_naissance_homme = isset($data['date_naissance_homme']) && !empty( $data['date_naissance_homme'])? Carbon::createFromFormat('d-m-Y', $data['date_naissance_homme']):null;
                $mariage->decret_autorisation_homme = isset($data['decret_autorisation_homme']) && !empty($data['decret_autorisation_homme'])? $data['decret_autorisation_homme']:null;
                
                //Epouse
                $mariage->nom_complet_femme = $data['nom_complet_femme'];
                $mariage->adresse_domicile_femme = isset($data['adresse_domicile_femme']) && !empty( $data['adresse_domicile_femme'])? $data['adresse_domicile_femme']:null;
                $mariage->fonction_femme = isset($data['fonction_femme']) && !empty( $data['fonction_femme'])? $data['fonction_femme']:null;
                $mariage->numero_acte_naissance_femme = isset($data['numero_acte_naissance_femme']) && !empty( $data['numero_acte_naissance_femme'])? $data['numero_acte_naissance_femme']:null;
                $mariage->lieu_naissance_femme = isset($data['lieu_naissance_femme']) && !empty( $data['lieu_naissance_femme'])? $data['lieu_naissance_femme']:null;
                $mariage->date_naissance_femme = isset($data['date_naissance_femme']) && !empty( $data['date_naissance_femme'])? Carbon::createFromFormat('d-m-Y', $data['date_naissance_femme']):null;
                $mariage->decret_autorisation_femme = isset($data['decret_autorisation_femme']) && !empty($data['decret_autorisation_femme'])? $data['decret_autorisation_femme']:null;
                
                //Parent epoux
                $mariage->nom_complet_pere_homme = isset($data['nom_complet_pere_homme']) && !empty( $data['nom_complet_pere_homme'])? $data['nom_complet_pere_homme']:null;
                $mariage->nom_complet_mere_homme = isset($data['nom_complet_mere_homme']) && !empty( $data['nom_complet_mere_homme'])? $data['nom_complet_mere_homme']:null;
                $mariage->adresse_mere_homme = isset($data['adresse_mere_homme']) && !empty( $data['adresse_mere_homme'])? $data['adresse_mere_homme']:null;
                $mariage->adresse_pere_homme = isset($data['adresse_pere_homme']) && !empty( $data['adresse_pere_homme'])? $data['adresse_pere_homme']:null;
                
                 //Parent epouse
                $mariage->nom_complet_pere_femme = isset($data['nom_complet_pere_femme']) && !empty( $data['nom_complet_pere_femme'])? $data['nom_complet_pere_femme']:null;
                $mariage->nom_complet_mere_femme = isset($data['nom_complet_mere_femme']) && !empty( $data['nom_complet_mere_femme'])? $data['nom_complet_mere_femme']:null;
                $mariage->adresse_mere_femme = isset($data['adresse_mere_femme']) && !empty( $data['adresse_mere_femme'])? $data['adresse_mere_femme']:null;
                $mariage->adresse_pere_femme = isset($data['adresse_pere_femme']) && !empty( $data['adresse_pere_femme'])? $data['adresse_pere_femme']:null;
               
                //Déclarant 
                $mariage->nom_complet_declarant = $data['nom_complet_declarant'];
                $mariage->date_declaration = Carbon::createFromFormat('d-m-Y', $data['date_declaration']);
                $mariage->date_retrait = Carbon::createFromFormat('d-m-Y', $data['date_retrait']);
                $mariage->contact_declarant = isset($data['contact_declarant']) && !empty( $data['contact_declarant'])? $data['contact_declarant']:null;
                $mariage->adresse_declarant = isset($data['adresse_declarant']) && !empty( $data['adresse_declarant'])? $data['adresse_declarant']:null;
                $mariage->date_naissance_declarant = isset($data['date_naissance_declarant']) && !empty( $data['date_naissance_declarant'])? Carbon::createFromFormat('d-m-Y', $data['date_naissance_declarant']):null;
                $mariage->fonction_declarant = isset($data['fonction_declarant']) && !empty( $data['fonction_declarant'])? $data['fonction_declarant']:null;
                $mariage->nombre_copie = isset($data['nombre_copie']) && !empty( $data['nombre_copie'])? $data['nombre_copie']:1;
                $mariage->montant_declaration = isset($data['montant_declaration']) && !empty( $data['montant_declaration'])? $data['montant_declaration']:0;
                
                //Autres
//                $mariage->dressant = isset($data['dressant']) && !empty( $data['dressant'])? $data['dressant']:null;
                $mariage->langue_reception = isset($data['langue_reception']) && !empty( $data['langue_reception'])? $data['langue_reception']:null;
                $mariage->traducteur = isset($data['traducteur']) && !empty( $data['traducteur'])? $data['traducteur']:null;
                $mariage->nom_complet_temoin_1 = isset($data['nom_complet_temoin_1']) && !empty( $data['nom_complet_temoin_1'])? $data['nom_complet_temoin_1']:null;
                $mariage->nom_complet_temoin_2 = isset($data['nom_complet_temoin_2']) && !empty( $data['nom_complet_temoin_2'])? $data['nom_complet_temoin_2']:null;
                $mariage->adresse_temoin_1 = isset($data['adresse_temoin_1']) && !empty( $data['adresse_temoin_1'])? $data['adresse_temoin_1']:null;
                $mariage->adresse_temoin_2 = isset($data['adresse_temoin_2']) && !empty( $data['adresse_temoin_2'])? $data['adresse_temoin_2']:null;
                $mariage->fonction_temoin_1 = isset($data['fonction_temoin_1']) && !empty( $data['fonction_temoin_1'])? $data['fonction_temoin_1']:null;
                $mariage->fonction_temoin_2 = isset($data['fonction_temoin_2']) && !empty( $data['fonction_temoin_2'])? $data['fonction_temoin_2']:null;
                $mariage->signataire = isset($data['signataire']) && !empty( $data['signataire'])? $data['signataire']:null;
                
                //En attendant
                $mariage->mention_1 = isset($data['mention_1']) && !empty($data['mention_1']) ? $data['mention_1']:null;
                $mariage->mention_2 = isset($data['mention_2']) && !empty($data['mention_2']) ? $data['mention_2']:null;
                $mariage->mention_3 = isset($data['mention_3']) && !empty($data['mention_3']) ? $data['mention_3']:null;
                $mariage->mention_4 = isset($data['mention_4']) && !empty($data['mention_4']) ? $data['mention_4']:null;
                $mariage->mention_5 = isset($data['mention_5']) && !empty($data['mention_5']) ? $data['mention_5']:null;
                $mariage->mention_6 = isset($data['mention_6']) && !empty($data['mention_6']) ? $data['mention_6']:null;
                $mariage->mention_7 = isset($data['mention_7']) && !empty($data['mention_7']) ? $data['mention_7']:null;
                $mariage->mention_8 = isset($data['mention_8']) && !empty($data['mention_8']) ? $data['mention_8']:null;
                
                $mariage->created_by = Auth::user()->id;
                $mariage->save();
                $jsonData["data"] = json_decode($mariage);
                return response()->json($jsonData);
            }
            catch (Exception $exc) {
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
     * @param  \App\Mariage  $mariage
     * @return Response
     */
    public function updateMariage(Request $request)
    {
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        $mariage = Mariage::find($request->get('idMariage'));
        if($mariage){
             $data = $request->all(); 
            try {
                
                 //Gestion des messages concernant les champs obligatoires
                if(empty($data['numero_acte_mariage'])){
                    throw new Exception("Le champs numero acte de mariage est vide!");
                }
                if(empty($data['registre'])){
                    throw new Exception("Le champs registre est vide!");
                }
                if(empty($data['date_dresser'])){
                    throw new Exception("Le champs date du dresser est vide!");
                }
                if(empty($data['date_mariage'])){
                    throw new Exception("Le champs date de mariage est vide!");
                }
                if(empty($data['nom_complet_homme'])){
                    throw new Exception("Le champs nom de l'homme est vide!");
                }
                if(empty($data['nom_complet_femme'])){
                    throw new Exception("Le champs nom de la femme est vide!");
                }
                if(empty($data['nom_complet_declarant'])){
                    throw new Exception("Le champs nom du déclarant est vide!");
                }
                if(empty($data['date_declaration'])){
                    throw new Exception("Le champs date de déclaration est vide!");
                }
                if(empty($data['date_retrait'])){
                    throw new Exception("Le champs date du retrait de la déclaration est vide!");
                }
              
                $mariage->numero_acte_mariage = $data['numero_acte_mariage'];
                $mariage->registre = $data['registre'];
                $mariage->date_dresser = Carbon::createFromFormat('d-m-Y', $data['date_dresser']);
                $mariage->date_mariage = Carbon::createFromFormat('d-m-Y H:i', $data['date_mariage']);
                $mariage->regime_id = isset($data['regime_id']) && !empty($data['regime_id']) ? $data['regime_id'] : Null;

                //Epoux 
                $mariage->nom_complet_homme = $data['nom_complet_homme'];
                $mariage->adresse_domicile_homme = isset($data['adresse_domicile_homme']) && !empty( $data['adresse_domicile_homme'])? $data['adresse_domicile_homme']:null;
                $mariage->fonction_homme = isset($data['fonction_homme']) && !empty( $data['fonction_homme'])? $data['fonction_homme']:null;
                $mariage->numero_acte_naissance_homme = isset($data['numero_acte_naissance_homme']) && !empty( $data['numero_acte_naissance_homme'])? $data['numero_acte_naissance_homme']:null;
                $mariage->lieu_naissance_homme = isset($data['lieu_naissance_homme']) && !empty( $data['lieu_naissance_homme'])? $data['lieu_naissance_homme']:null;
                $mariage->date_naissance_homme = isset($data['date_naissance_homme']) && !empty( $data['date_naissance_homme'])? Carbon::createFromFormat('d-m-Y', $data['date_naissance_homme']):null;
                $mariage->decret_autorisation_homme = isset($data['decret_autorisation_homme']) && !empty($data['decret_autorisation_homme'])? $data['decret_autorisation_homme']:null;
                
                //Epouse 
                $mariage->nom_complet_femme = $data['nom_complet_femme'];
                $mariage->adresse_domicile_femme = isset($data['adresse_domicile_femme']) && !empty( $data['adresse_domicile_femme'])? $data['adresse_domicile_femme']:null;
                $mariage->fonction_femme = isset($data['fonction_femme']) && !empty( $data['fonction_femme'])? $data['fonction_femme']:null;
                $mariage->numero_acte_naissance_femme = isset($data['numero_acte_naissance_femme']) && !empty( $data['numero_acte_naissance_femme'])? $data['numero_acte_naissance_femme']:null;
                $mariage->lieu_naissance_femme = isset($data['lieu_naissance_femme']) && !empty( $data['lieu_naissance_femme'])? $data['lieu_naissance_femme']:null;
                $mariage->date_naissance_femme = isset($data['date_naissance_femme']) && !empty( $data['date_naissance_femme'])? Carbon::createFromFormat('d-m-Y', $data['date_naissance_femme']):null;
                $mariage->decret_autorisation_femme = isset($data['decret_autorisation_femme']) && !empty($data['decret_autorisation_femme'])? $data['decret_autorisation_femme']:null;

                //Parent epoux
                $mariage->nom_complet_pere_homme = isset($data['nom_complet_pere_homme']) && !empty( $data['nom_complet_pere_homme'])? $data['nom_complet_pere_homme']:null;
                $mariage->nom_complet_mere_homme = isset($data['nom_complet_mere_homme']) && !empty( $data['nom_complet_mere_homme'])? $data['nom_complet_mere_homme']:null;
                $mariage->adresse_mere_homme = isset($data['adresse_mere_homme']) && !empty( $data['adresse_mere_homme'])? $data['adresse_mere_homme']:null;
                $mariage->adresse_pere_homme = isset($data['adresse_pere_homme']) && !empty( $data['adresse_pere_homme'])? $data['adresse_pere_homme']:null;
                
                 //Parent epouse
                $mariage->nom_complet_pere_femme = isset($data['nom_complet_pere_femme']) && !empty( $data['nom_complet_pere_femme'])? $data['nom_complet_pere_femme']:null;
                $mariage->nom_complet_mere_femme = isset($data['nom_complet_mere_femme']) && !empty( $data['nom_complet_mere_femme'])? $data['nom_complet_mere_femme']:null;
                $mariage->adresse_mere_femme = isset($data['adresse_mere_femme']) && !empty( $data['adresse_mere_femme'])? $data['adresse_mere_femme']:null;
                $mariage->adresse_pere_femme = isset($data['adresse_pere_femme']) && !empty( $data['adresse_pere_femme'])? $data['adresse_pere_femme']:null;
               
                //Déclarant 
                $mariage->nom_complet_declarant = $data['nom_complet_declarant'];
                $mariage->date_declaration = Carbon::createFromFormat('d-m-Y', $data['date_declaration']);
                $mariage->date_retrait = Carbon::createFromFormat('d-m-Y', $data['date_retrait']);
                $mariage->contact_declarant = isset($data['contact_declarant']) && !empty( $data['contact_declarant'])? $data['contact_declarant']:null;
                $mariage->adresse_declarant = isset($data['adresse_declarant']) && !empty( $data['adresse_declarant'])? $data['adresse_declarant']:null;
                $mariage->date_naissance_declarant = isset($data['date_naissance_declarant']) && !empty( $data['date_naissance_declarant'])? Carbon::createFromFormat('d-m-Y', $data['date_naissance_declarant']):null;
                $mariage->fonction_declarant = isset($data['fonction_declarant']) && !empty( $data['fonction_declarant'])? $data['fonction_declarant']:null;
                $mariage->nombre_copie = isset($data['nombre_copie']) && !empty( $data['nombre_copie'])? $data['nombre_copie']:1;
                $mariage->montant_declaration = isset($data['montant_declaration']) && !empty( $data['montant_declaration'])? $data['montant_declaration']:0;
                
                //Autres
//                $mariage->dressant = isset($data['dressant']) && !empty( $data['dressant'])? $data['dressant']:null;
                $mariage->langue_reception = isset($data['langue_reception']) && !empty( $data['langue_reception'])? $data['langue_reception']:null;
                $mariage->traducteur = isset($data['traducteur']) && !empty( $data['traducteur'])? $data['traducteur']:null;
                $mariage->nom_complet_temoin_1 = isset($data['nom_complet_temoin_1']) && !empty( $data['nom_complet_temoin_1'])? $data['nom_complet_temoin_1']:null;
                $mariage->nom_complet_temoin_2 = isset($data['nom_complet_temoin_2']) && !empty( $data['nom_complet_temoin_2'])? $data['nom_complet_temoin_2']:null;
                $mariage->adresse_temoin_1 = isset($data['adresse_temoin_1']) && !empty( $data['adresse_temoin_1'])? $data['adresse_temoin_1']:null;
                $mariage->adresse_temoin_2 = isset($data['adresse_temoin_2']) && !empty( $data['adresse_temoin_2'])? $data['adresse_temoin_2']:null;
                $mariage->fonction_temoin_1 = isset($data['fonction_temoin_1']) && !empty( $data['fonction_temoin_1'])? $data['fonction_temoin_1']:null;
                $mariage->fonction_temoin_2 = isset($data['fonction_temoin_2']) && !empty( $data['fonction_temoin_2'])? $data['fonction_temoin_2']:null;
                $mariage->signataire = isset($data['signataire']) && !empty( $data['signataire'])? $data['signataire']:null;
                
                //En attendant
                $mariage->mention_1 = isset($data['mention_1']) && !empty($data['mention_1']) ? $data['mention_1']:null;
                $mariage->mention_2 = isset($data['mention_2']) && !empty($data['mention_2']) ? $data['mention_2']:null;
                $mariage->mention_3 = isset($data['mention_3']) && !empty($data['mention_3']) ? $data['mention_3']:null;
                $mariage->mention_4 = isset($data['mention_4']) && !empty($data['mention_4']) ? $data['mention_4']:null;
                $mariage->mention_5 = isset($data['mention_5']) && !empty($data['mention_5']) ? $data['mention_5']:null;
                $mariage->mention_6 = isset($data['mention_6']) && !empty($data['mention_6']) ? $data['mention_6']:null;
                $mariage->mention_7 = isset($data['mention_7']) && !empty($data['mention_7']) ? $data['mention_7']:null;
                $mariage->mention_8 = isset($data['mention_8']) && !empty($data['mention_8']) ? $data['mention_8']:null;
                $mariage->updated_by = Auth::user()->id;
                $mariage->save(); 
                
                $jsonData["data"] = json_decode($mariage);
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
     * @param  \App\Mariage  $mariage
     * @return Response
     */
    public function destroy(Mariage $mariage)
    {
       $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($mariage){
                try {
                    $mariage->update(['deleted_by' => Auth::user()->id]);
                    $mariage->delete();
                    $jsonData["data"] = json_decode($mariage);
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
    
    //Etats
    public function extraitDeclarationMariagePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->extraitDeclarationMariage($id));
        $mariage = Mariage::find($id);
        return $pdf->stream('extrait_acte_mariage_'.$mariage->numero_acte_mariage.'.pdf');
    }
    public function extraitDeclarationMariage($id){
        $outPut = $this->content($id);
//        $outPut.= $this->footer();
        return $outPut;
    }
    public function content($id){
        $generator = new BarcodeGeneratorPNG();
        $mariage = Mariage::where([['mariages.deleted_at', NULL],['mariages.id',$id]]) 
                            ->join('regimes', 'regimes.id','=','mariages.regime_id')
                            ->leftJoin('fonctions as fonctionHomme', 'fonctionHomme.id','=','mariages.fonction_homme')
                            ->leftJoin('fonctions as fonctionFemme', 'fonctionFemme.id','=','mariages.fonction_femme')
                            ->select('mariages.*','fonctionFemme.libelle_fonction as fonction_femme','fonctionHomme.libelle_fonction as fonction_homme','regimes.libelle_regime',DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                            ->first();
        $month = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
        $dateMariage = $mariage->date_mariage;
        $dayMariage = date('d', strtotime($dateMariage));
        $montMariage = date('m', strtotime($dateMariage));
        $anMariage = date('Y', strtotime($dateMariage));
        $heureMariage = date('H', strtotime($dateMariage));
        $minuteMariage = date('i', strtotime($dateMariage));
        $dayMariage == 01 ? $jourMariage = 'premier' : $jourMariage = NumberToLetter(number_format($dayMariage));
        
        if($mariage->date_naissance_homme!=null){
            $dateNaissanceHomme = $mariage->date_naissance_homme; 
     
            $dayNaissanceH = date('d', strtotime($dateNaissanceHomme));
            $montNaissanceH = date('m', strtotime($dateNaissanceHomme));
            $anNaissanceH = date('Y', strtotime($dateNaissanceHomme)); 
                    
            $dayNaissanceH == 01 ? $jourNaissanceH = 'premier' : $jourNaissanceH = NumberToLetter(number_format($dayNaissanceH));
            $NaissanceHomme = $jourNaissanceH." ".$month[$montNaissanceH]." ".NumberToLetter($anNaissanceH);
        }else{
            $NaissanceHomme ="";
        }
        
        if($mariage->date_naissance_femme!=null){
            $dateNaissanceFemme = $mariage->date_naissance_femme;
            $dayNaissanceF = date('d', strtotime($dateNaissanceFemme));
            $montNaissanceF = date('m', strtotime($dateNaissanceFemme));
            $anNaissanceF = date('Y', strtotime($dateNaissanceFemme));
            
            $dayNaissanceF == 01 ? $jourNaissanceF = 'premier' : $jourNaissanceF = NumberToLetter(number_format($dayNaissanceF));
            $NaissanceFemme = $jourNaissanceF." ".$month[$montNaissanceF]." ".NumberToLetter($anNaissanceF);
        }else{
            $NaissanceFemme ="";
        }
       
        if($mariage->lieu_naissance_homme!=null){
           $lieuNaissanceH = $mariage->lieu_naissance_homme; 
        }else{
            $lieuNaissanceH ="";
        }
        
        if($mariage->lieu_naissance_femme!=null){
            $lieuNaissanceF = $mariage->lieu_naissance_femme;
        }else{
            $lieuNaissanceF = "";
        }
       
        if($mariage->nom_complet_pere_homme!=null){
            $nomPereHomme = $mariage->nom_complet_pere_homme;
        }else{
            $nomPereHomme="";
        }
        if($mariage->nom_complet_mere_homme!=null){
            $nomMereHomme = $mariage->nom_complet_mere_homme;
        }else{
            $nomMereHomme="";
        }
        
        if($mariage->nom_complet_pere_femme!=null){
            $nomPereFemme = $mariage->nom_complet_pere_femme;
        }else{
            $nomPereFemme="";
        }
        if($mariage->nom_complet_mere_femme!=null){
            $nomMereFemme = $mariage->nom_complet_mere_femme;
        }else{
            $nomMereFemme="";
        }

        if($heureMariage < 10){
            $heureM = NumberToLetter(number_format(substr($heureMariage,1,1)));
            if($heureM == "un"){
                $heureM = "une";
            }
        }else{
            $heureM = NumberToLetter(number_format($heureMariage));
            if($heureM == "vingt et un"){
                $heureM = "vingt et une";
            }
          
        }
        if($minuteMariage < 10){
            $minuteM = NumberToLetter(number_format(substr($minuteMariage,1,1)));
             if($minuteM == "un"){
                $minuteM = "une";
            }
        }else{
            $minuteM = NumberToLetter(number_format($minuteMariage));
            if($minuteM == "vingt et un"){
                $minuteM = "vingt et une";
            }
            if($minuteM == "trente et un"){
                $minuteM = "trente et une";
            }
            if($minuteM == "quarante et un"){
                $minuteM = "quarante et une";
            }
            if($minuteM == "cinquante et un"){
                $minuteM = "cinquante et une";
            }
        }
        
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
                                           padding: 300px 0;
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
                                            width: 65%;
                                            height:7%;
                                            float: right;
                                            position: absolute;
                                            top: 0;
                                            padding: 10px 0;
                                        }
                                        .fixed-footer{
                                            position: fixed; 
                                            bottom: -28; 
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
                         <b> Mairie ".$this->premierLetre()."".$this->infosConfig()->commune."</b><br/> ";
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
                    <b>N° ".$mariage->numero_acte_mariage." DU ".$mariage->date_dressers."</b> du registre
                     <hr width='90%'/>
                    </p>
                    <p style='line-height:1.8; text-align:left;'>
                    &nbsp;&nbsp;MARIAGE ENTRE : <br/>
                    <b>&nbsp;&nbsp;".$mariage->nom_complet_homme."</b> <br/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;et <br/><b>&nbsp;&nbsp;".$mariage->nom_complet_femme."</b></p>";
                   
           $content.="</div>
                <div class='fixed-header-right'>
                    <span style='opacity:0.35;font-style: italic;'>".$nom_initial."</span>
                    <div style='text-align:center;'>
                            REPUBLIQUE DE COTE D'IVOIRE<br/>Union-Discipline-Travail<hr width='50'/>
                           <span style='font-size:40px; font-weight: bold;'> EXTRAIT </span>
                        <p>DU REGISTRE DES ACTES DE L'ETAT CIVIL POUR L'ANNEE <b>".$mariage->registre."</b></p><br/>
                    </div>
                    <div style='line-height:2;'>
                        Le <b>".$jourMariage." ".$month[$montMariage]." ".NumberToLetter($anMariage)."</b><br/> à <b>".$heureM." heure(s) ".$minuteM." minute(s)</b><br/>
                        Entre <b>".$mariage->nom_complet_homme."</b><br/> de profession <b>".$mariage->fonction_homme."</b><br/>
                        Né le <b>".$NaissanceHomme."</b><br/> à <b>".$lieuNaissanceH."</b><br/>
                        Fils de <b>".$nomPereHomme."</b> <br/> et de <b>".$nomMereHomme."</b><br/>
                        Domicilé à <b>".$mariage->adresse_domicile_homme."</b><br/>
                        Et <b>".$mariage->nom_complet_femme."</b><br/> de profession <b>".$mariage->fonction_femme."</b><br/>
                        Neé le <b>".$NaissanceFemme."</b><br/> à <b>".$lieuNaissanceF."</b><br/>
                        Fille de <b>".$nomPereFemme."</b> <br/>et de <b>".$nomMereFemme."</b><br/>
                        Domicilée à <b>".$mariage->adresse_domicile_femme."</b>
                    </div>
                    <br/><br/>
                    <p><i>Certifié le présent extrait conforme aux indications portées au registre.</i></p>
                    <p style='float:right;'><i>Délivré à <b>".$this->infosConfig()->commune."</b>, le <b>".date("d")."-".$month[date("m")]."-".date("Y")."</b></i></p>
                         <br/>
                    <p style='float:right;'>
                        L’Officier de l’Etat Civil, <br/>
                        (Signature)
                    </p>";
                     
                    $content.="<br/><br/><p style='float:left;'><img src='data:image/png;base64,".base64_encode($generator->getBarcode(123456789, $generator::TYPE_CODE_128))."'></p>"; 

             $content.="</div>
            </body>
        </html>";     
     return $content;
    }
    
    
    //Copie intégrale de mariage
    public function extraitCopieIntegralePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->extraitCopieIntegrale($id));
        $mariage = Mariage::find($id);
        return $pdf->stream('copie_integrale_acte_mariage_'.$mariage->numero_acte_mariage.'.pdf');
    }
    public function extraitCopieIntegrale($id){
        $mariage = Mariage::where([['mariages.deleted_at', NULL],['mariages.id',$id]])
                                ->join('regimes', 'regimes.id','=','mariages.regime_id')
                                ->leftJoin('fonctions as fonctionHomme', 'fonctionHomme.id','=','mariages.fonction_homme')
                                ->leftJoin('fonctions as fonctionFemme', 'fonctionFemme.id','=','mariages.fonction_femme')
                                ->leftJoin('fonctions as fonctionT1', 'fonctionT1.id','=','mariages.fonction_temoin_1')
                                ->leftJoin('fonctions as fonctionT2', 'fonctionT2.id','=','mariages.fonction_temoin_2')
                                ->leftJoin('fonctions as fonctionDeclarant', 'fonctionDeclarant.id','=','mariages.fonction_declarant')
                                ->select('mariages.*','regimes.libelle_regime','fonctionHomme.libelle_fonction as libelle_fonction_homme','fonctionFemme.libelle_fonction as libelle_fonction_femme','fonctionT1.libelle_fonction as libelle_fonction_t1','fonctionT2.libelle_fonction as libelle_fonction_t2','fonctionDeclarant.libelle_fonction as libelle_fonction_declarant',DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                                ->first();
        
        $month = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
        $dateMariage = $mariage->date_mariage;
//        $dateDresser = $mariage->date_dresser;
        
        $yearMariage = date('Y', strtotime($dateMariage));
        $monthMariage = date('m', strtotime($dateMariage));
        $dayMariage = date('d', strtotime($dateMariage));
        $heureMariage = date('H', strtotime($dateMariage));
        $minuteMariage = date('i', strtotime($dateMariage));
        
//        $yearDresser = date('Y', strtotime($dateDresser));
//        $monthDresser = date('m', strtotime($dateDresser));
//        $dayDresser = date('d', strtotime($dateDresser));
//        
        $dayMariage == 01 ? $jourMariag = 'premier' : $jourMariag = NumberToLetter(number_format($dayMariage));
//        $dayDresser == 01 ? $jourDresser = 'premier' : $jourDresser = NumberToLetter(number_format($dayDresser));
        

        if($mariage->date_naissance_homme!=null){
            $dateNHomme = $mariage->date_naissance_homme;
            $yearNHomme = date('Y', strtotime($dateNHomme));
            $monthNHomme = date('m', strtotime($dateNHomme));
            $dayNHomme = date('d', strtotime($dateNHomme));
            $dayNHomme == 01 ? $jourNHomme = 'premier' : $jourNHomme = NumberToLetter(number_format($dayNHomme));
            
            $date_naissance_homme = $jourNHomme." ".$month[$monthNHomme]." ".NumberToLetter($yearNHomme);
        }else{
            $date_naissance_homme = "...............................................................................................................................";
        }
        
        if($mariage->date_naissance_femme!=null){
           
            $dateNFemme = $mariage->date_naissance_femme;
            $yearNFemme = date('Y', strtotime($dateNFemme));
            $monthNFemme = date('m', strtotime($dateNFemme));
            $dayNFemme = date('d', strtotime($dateNFemme));
            $dayNFemme == 01 ? $jourNFemme = 'premier' : $jourNFemme = NumberToLetter(number_format($dayNFemme));
            
            $date_naissance_femme =  $jourNFemme." ".$month[$monthNFemme]." ".NumberToLetter($yearNFemme);
            
        }else{
            $date_naissance_femme = "...........................................................................................................................";
        }

        if($mariage->libelle_fonction_homme!=null){
            $fonction_homme = $mariage->libelle_fonction_homme;
        }else{
            $fonction_homme = "......................................................................................................................";
        }
        if($mariage->fonction_femme!=null){
             $fonction_femme = $mariage->libelle_fonction_femme;
        }else{
            $fonction_femme = "....................................................................................................................";
        }
        if($mariage->libelle_fonction_t1!=null){
            $libelle_fonction_t_1 = $mariage->libelle_fonction_t1;
        }else{
            $libelle_fonction_t_1="...........................................";
        }
        if($mariage->libelle_fonction_t2!=null){
            $libelle_fonction_t_2 = $mariage->libelle_fonction_t2;
        }else{
            $libelle_fonction_t_2=".....................................";
        }
        if($mariage->libelle_fonction_declarant!=null){
            $libelle_fonction_declarant = $mariage->libelle_fonction_declarant;
        }else{
            $libelle_fonction_declarant=".............................................................................";
        }
        
        if($mariage->lieu_naissance_homme!=null){
            $lieu_naissance_homme = $mariage->lieu_naissance_homme;
        }else{
            $lieu_naissance_homme = ".....................................................................................................................................";
        }
        
        if($mariage->lieu_naissance_femme!=null){
            $lieu_naissance_femme = $mariage->lieu_naissance_femme;
        }else{
            $lieu_naissance_femme = "...................................................................................................................................";
        }
        
        if($mariage->signataire!=null){
            $signataire = $mariage->signataire;
        }else{
            $signataire = ".....................................................";
        }
//         if($mariage->dressant!=null){
//            $dressant = $mariage->dressant;
//        }else{
//            $dressant = "................................................................................................................";
//        }
        
        if($mariage->nom_complet_pere_homme!=null){
            $nom_complet_pere_homme = $mariage->nom_complet_pere_homme;
        }else{
            $nom_complet_pere_homme = "............................................................................................................................";
        }
        
        if($mariage->nom_complet_mere_homme!=null){
            $nom_complet_mere_homme = $mariage->nom_complet_mere_homme;
        }else{
            $nom_complet_mere_homme = ".............................................................................................................................";
        }
        
        if($mariage->nom_complet_pere_femme!=null){
            $nom_complet_pere_femme = $mariage->nom_complet_pere_femme;
        }else{
            $nom_complet_pere_femme = ".........................................................................................................................";
        }
        
        if($mariage->nom_complet_mere_femme!=null){
            $nom_complet_mere_femme = $mariage->nom_complet_mere_femme;
        }else{
            $nom_complet_mere_femme = ".............................................................................................................................";
        }
        
        if($mariage->adresse_domicile_homme!=null){
            $adresse_domicile_homme = $mariage->adresse_domicile_homme;
        }else{
            $adresse_domicile_homme = "..................................................................................................................";
        }
        
        if($mariage->adresse_domicile_femme!=null){
            $adresse_domicile_femme = $mariage->adresse_domicile_femme;
        }else{
            $adresse_domicile_femme = "..................................................................................................................";
        }
        
        if($mariage->decret_autorisation_homme!=null){
             $decret_autorisation_homme = $mariage->decret_autorisation_homme;
        }else{
            $decret_autorisation_homme = "...............................................................................";
        }
        
        if($mariage->decret_autorisation_femme!=null){
             $decret_autorisation_femme = $mariage->decret_autorisation_femme;
        }else{
            $decret_autorisation_femme = "..............................................................................";
        }
        
        if($mariage->nom_complet_temoin_1!=null){
           $nom_complet_temoin_1 = $mariage->nom_complet_temoin_1;
        }else{
            $nom_complet_temoin_1 = "...................................";
        }
        
        if($mariage->nom_complet_temoin_2!=null){
           $nom_complet_temoin_2 = $mariage->nom_complet_temoin_2;
        }else{
            $nom_complet_temoin_2 = "....................................";
        }
        if($mariage->adresse_temoin_1!=null){
           $adresse_temoin_1 = $mariage->adresse_temoin_1;
        }else{
            $adresse_temoin_1 = ".........................";
        }
        
        if($mariage->adresse_temoin_2!=null){
           $adresse_temoin_2 = $mariage->adresse_temoin_2;
        }else{
            $adresse_temoin_2 = "..............................";
        }
        
        if($mariage->traducteur!=null){
            $traducteur = $mariage->traducteur;
        }else{
            $traducteur = "...............................................................................................";
        }
        
        
        if($heureMariage < 10){
            $heureM = NumberToLetter(number_format(substr($heureMariage,1,1)));
            if($heureM == "un"){
                $heureM = "une";
            }
        }else{
            $heureM = NumberToLetter(number_format($heureMariage));
            if($heureM == "vingt et un"){
                $heureM = "vingt et une";
            }
          
        }
        if($minuteMariage < 10){
            $minuteM = NumberToLetter(number_format(substr($minuteMariage,1,1)));
             if($minuteM == "un"){
                $minuteM = "une";
            }
        }else{
            $minuteM = NumberToLetter(number_format($minuteMariage));
            if($minuteM == "vingt et un"){
                $minuteM = "vingt et une";
            }
            if($minuteM == "trente et un"){
                $minuteM = "trente et une";
            }
            if($minuteM == "quarante et un"){
                $minuteM = "quarante et une";
            }
            if($minuteM == "cinquante et un"){
                $minuteM = "cinquante et une";
            }
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
                            line-height:1.2;
                            margin:205px 0;
                            position: absolute; 
                            width: 75%;
                            left: 26%;
                            top: 0;
                            padding: 20px 0;
                        }
                        .line-vertical{
                            border-left: 1px solid;
                            height:100%;
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
                        <p style='font-size:25px;'><b><u>COPIE INTEGRALE D'EXTRAIT D'ACTE DE MARIAGE</u></b></p>
                    </div>";
                $out_put.="<div class='line-horizontal'></div>";
                $out_put.="<div class='fixed-content-left'>
                        <b>Act N° ".$mariage->numero_acte_mariage." du ".date('d', strtotime($mariage->date_dresser))." ".$month[date('m', strtotime($mariage->date_dresser))]." ".date('Y', strtotime($mariage->date_dresser))."</b><br/>
                        Mariage :<br/>
                        de <b>".$mariage->nom_complet_homme."</b><br/>
                        et de <b>".$mariage->nom_complet_femme."</b><br/><br/>
                        Sur notre interpellation les intéressés ont déclaré pour option : <br/><b>".$mariage->libelle_regime."</b><br/><br/><br/>
                       </div>";
        $out_put.="<div class='line-vertical'></div>";
        $out_put.="<div class='fixed-content-right'>
                    1. Le <b>".$jourMariag." ".$month[$monthMariage]." ".NumberToLetter($yearMariage)."</b><br/>
                    2. à <b>".$heureM." heure(s) ".$minuteM." minute(s)</b><br/>
                    3. Devant nous, <b>".$signataire."</b><br/>
                    4. est comparu publiquement à la mairie <b>".$this->premierLetre()."".$this->infosConfig()->commune."</b><br/>
                    5. <b>".$mariage->nom_complet_homme."</b><br/>
                    6. Profession <b>".$fonction_homme."</b><br/>
                    7. Né le <b>".$date_naissance_homme."</b><br/>
                    8. à <b>".$lieu_naissance_homme."</b><br/>
                    9. Fils de <b>".$nom_complet_pere_homme."</b><br/>
                    10. et de <b>".$nom_complet_mere_homme."</b><br/>
                    11. Domicilié à <b>".$adresse_domicile_homme."</b><br/>
                    12. <b>Célibataire</b><br/>
                    13. Autorisé à contracter mariage par <b>".$decret_autorisation_homme."</b><br/>
                    14. Et de <b>".$mariage->nom_complet_femme."</b><br/>
                    15. Profession <b>".$fonction_femme."</b><br/>
                    16. Née le <b>".$date_naissance_femme."</b><br/>
                    17. à <b>".$lieu_naissance_femme."</b><br/>   
                    18. Fille de <b>".$nom_complet_pere_femme."</b><br/>
                    19. et de <b>".$nom_complet_mere_femme."</b><br/>
                    20. Domicilié à <b>".$adresse_domicile_femme."</b><br/> 
                    21. <b>Célibataire</b><br/>   
                    22. Autorisée à contracter mariage par <b>".$decret_autorisation_femme."</b><br/>
                       &nbsp;lesquels ont déclaré l'un après l'autre vouloir se prendre pour époux et Nous avons prononcé, au non de la loi, qu'ils sont unis par le mariage, en présence de<br/>
                    23. 1' <b>".$nom_complet_temoin_1."</b>, <b>".$libelle_fonction_t_1."</b> domicilié(e) à <b>".$adresse_temoin_1."</b><br/>
                    24. 2' <b>".$nom_complet_temoin_2."</b>, <b>".$libelle_fonction_t_2."</b> domicilié(e) à <b>".$adresse_temoin_2."</b><br/>
                        &nbsp;témoins majeurs<br/>
                    25. avec l'assistance de <b>".$traducteur."</b>, <br/>Interprète, ayant prêté devant Nous le serment prévu par la loi. Avant de dresser l'acte, Nous avons averti les parties comparantes et les témoins des peines prévues par la loi pour sanctionner les fausses déclarations.<br/>
                    26. Lecture faite, les époux et les témoins invités à lire l'acte.<br/>
                    27. Nous avons signé avec les époux et les témoins<br/><br/>
                   
                    <p style='float:right;'>".$this->infosConfig()->commune.", le ".date("d")." ".$month[date("m")]." ".date("Y")."&nbsp;&nbsp;&nbsp;<br/>
                        Pour copie certifier conforme&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>
                        L'Officier de l'Etat-Civil&nbsp;&nbsp;&nbsp;
                    </p>
                </div>";
        return $out_put;
    }
    
    //Etat liste des futurs mariages
    public function ficheProchainsMariagesPdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheProchainsMariages());
        return $pdf->stream('liste_futurs_mariages.pdf');
    }
    public function ficheProchainsMariages(){
        $datas = $this->listeProchainMariages();
        $outPut = $this->headerFiche();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des prochains mariages</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="20%">Date</th>
                            <th cellspacing="0" border="2" width="30%" align="center">Epoux</th>
                            <th cellspacing="0" border="2" width="30%" align="center">Epouse</th>
                            <th cellspacing="0" border="2" width="20%" align="center">Contact</th>
                        </tr></div>';
         $total = 0;
       foreach ($datas as $data){
           $total = $total + 1;
           $outPut .= '
                       <tr>
                            <td  cellspacing="0" border="2">&nbsp;'.$data->date_mariages.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->nom_complet_homme.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->nom_complet_femme.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->contact_declarant.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').' mariage(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Etat liste des futurs mariages par mois
    public function ficheProchainsMariagesParMoisPdf($mois){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheProchainsMariagesParMois($mois));
        return $pdf->stream('liste_futurs_mariages_du_mois_de_'.$mois.'.pdf');
    }
    public function ficheProchainsMariagesParMois($mois){
        $datas = $this->listeProchainMariagesParMois($mois);
        $outPut = $this->headerFiche();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des prochains mariages en '.$mois.'</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="20%">Date</th>
                            <th cellspacing="0" border="2" width="30%" align="center">Epoux</th>
                            <th cellspacing="0" border="2" width="30%" align="center">Epouse</th>
                            <th cellspacing="0" border="2" width="20%" align="center">Contact</th>
                        </tr></div>';
         $total = 0;
       foreach ($datas as $data){
           $total = $total + 1;
           $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2">&nbsp;'.$data->date_mariages.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->nom_complet_homme.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->nom_complet_femme.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->contact_declarant.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').' mariage(s) en '.$mois.'</b>';
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
