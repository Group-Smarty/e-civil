<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\Naissance;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Picqer\Barcode\BarcodeGeneratorPNG;

include_once(app_path ()."/number-to-letters/nombre_en_lettre.php");

class NaissanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $nations = DB::table('nations')->Where('deleted_at', NULL)->orderBy('libelle_nation', 'asc')->get();
       $fonctions = DB::table('fonctions')->Where('deleted_at', NULL)->orderBy('libelle_fonction', 'asc')->get();
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Déclaration de naissance";
       $btnModalAjout = "TRUE";
       return view('ecivil.naissance.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur','fonctions','nations')); 
    }
    
    public function vueNouveauxMajeurs(){
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Liste des nouveaux majeurs pour l'année en cours";
       $btnModalAjout = "FALSE";
       return view('ecivil.naissance.nouveaux-majeurs',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function vueNaissanceByMois(){
        $annees = [];
        $cetteAnnee = (date("Y")-1);
        for ($index = 1950; $index <= $cetteAnnee; $index++) {
            $annees[$index] = $index;
        }
       $anneesObt = array_reverse($annees);
       $moisFr = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Liste des naissances par mois pour l'année en cours";
       $btnModalAjout = "FALSE";
       return view('ecivil.naissance.naissances-by-mois',compact('moisFr','anneesObt', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }
    
    public function vueNaissanceBySecteur(){
        $annees = [];
        $cetteAnnee = (date("Y")-1);
        for ($index = 1950; $index <= $cetteAnnee; $index++) {
            $annees[$index] = $index;
        }
       $anneesObt = array_reverse($annees);
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Naissance par lieu de naissance pour l'année en cours";
       $btnModalAjout = "FALSE";
       return view('ecivil.naissance.naissances-by-secteur',compact('anneesObt', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeNaissance(){
        $naissances = Naissance::with('nationalite_mere','nationalite_pere','fonction_pere','fonction_mere','fonction_declarant','fonction_temoin_1','fonction_temoin_2')
                        ->Where('naissances.deleted_at', NULL) 
                        ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_temoin_1, "%d-%m-%Y") as date_naissance_temoin_1s'),DB::raw('DATE_FORMAT(naissances.date_naissance_temoin_2, "%d-%m-%Y") as date_naissance_temoin_2s'),DB::raw('DATE_FORMAT(naissances.mention_date_mariage, "%d-%m-%Y") as mention_date_mariages'),DB::raw('DATE_FORMAT(naissances.mention_date_divorce, "%d-%m-%Y") as mention_date_divorces'),DB::raw('DATE_FORMAT(naissances.mention_date_deces, "%d-%m-%Y") as mention_date_decess'),DB::raw('DATE_FORMAT(naissances.date_naissance_declarant, "%d-%m-%Y") as date_naissance_declarants'),DB::raw('DATE_FORMAT(naissances.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(naissances.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(naissances.date_naissance_mere, "%d-%m-%Y") as date_naissance_meres'),DB::raw('DATE_FORMAT(naissances.date_naissance_pere, "%d-%m-%Y") as date_naissance_peres'),DB::raw('DATE_FORMAT(naissances.date_requisition, "%d-%m-%Y") as date_requisitions'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance_enfants'))
                        ->orderBy('naissances.id', 'DESC')
                        ->get();
       $jsonData["rows"] = $naissances->toArray();
       $jsonData["total"] = $naissances->count();
       return response()->json($jsonData);
    }
    public function listeNaissancesByActe($numero_acte){
         $naissances = Naissance::with('nationalite_mere','nationalite_pere','fonction_pere','fonction_mere','fonction_declarant','fonction_temoin_1','fonction_temoin_2')
                        ->Where([['naissances.deleted_at', NULL],['naissances.numero_acte_naissance','like','%'.$numero_acte.'%']]) 
                        ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_temoin_1, "%d-%m-%Y") as date_naissance_temoin_1s'),DB::raw('DATE_FORMAT(naissances.date_naissance_temoin_2, "%d-%m-%Y") as date_naissance_temoin_2s'),DB::raw('DATE_FORMAT(naissances.mention_date_mariage, "%d-%m-%Y") as mention_date_mariages'),DB::raw('DATE_FORMAT(naissances.mention_date_divorce, "%d-%m-%Y") as mention_date_divorces'),DB::raw('DATE_FORMAT(naissances.mention_date_deces, "%d-%m-%Y") as mention_date_decess'),DB::raw('DATE_FORMAT(naissances.date_naissance_declarant, "%d-%m-%Y") as date_naissance_declarants'),DB::raw('DATE_FORMAT(naissances.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(naissances.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(naissances.date_naissance_mere, "%d-%m-%Y") as date_naissance_meres'),DB::raw('DATE_FORMAT(naissances.date_naissance_pere, "%d-%m-%Y") as date_naissance_peres'),DB::raw('DATE_FORMAT(naissances.date_requisition, "%d-%m-%Y") as date_requisitions'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance_enfants'))
                        ->orderBy('naissances.id', 'DESC')
                        ->get();
       $jsonData["rows"] = $naissances->toArray();
       $jsonData["total"] = $naissances->count();
       return response()->json($jsonData);
    }
    public function listeNaissancesByName($name){
       $naissances = Naissance::with('nationalite_mere','nationalite_pere','fonction_pere','fonction_mere','fonction_declarant','fonction_temoin_1','fonction_temoin_2')
                        ->Where([['naissances.deleted_at', NULL],['naissances.prenom_enfant','like','%'.$name.'%']]) 
                        ->orWhere([['naissances.deleted_at', NULL],['naissances.nom_enfant','like','%'.$name.'%']]) 
                        ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_temoin_1, "%d-%m-%Y") as date_naissance_temoin_1s'),DB::raw('DATE_FORMAT(naissances.date_naissance_temoin_2, "%d-%m-%Y") as date_naissance_temoin_2s'),DB::raw('DATE_FORMAT(naissances.mention_date_mariage, "%d-%m-%Y") as mention_date_mariages'),DB::raw('DATE_FORMAT(naissances.mention_date_divorce, "%d-%m-%Y") as mention_date_divorces'),DB::raw('DATE_FORMAT(naissances.mention_date_deces, "%d-%m-%Y") as mention_date_decess'),DB::raw('DATE_FORMAT(naissances.date_naissance_declarant, "%d-%m-%Y") as date_naissance_declarants'),DB::raw('DATE_FORMAT(naissances.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(naissances.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(naissances.date_naissance_mere, "%d-%m-%Y") as date_naissance_meres'),DB::raw('DATE_FORMAT(naissances.date_naissance_pere, "%d-%m-%Y") as date_naissance_peres'),DB::raw('DATE_FORMAT(naissances.date_requisition, "%d-%m-%Y") as date_requisitions'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance_enfants'))
                        ->orderBy('naissances.id', 'DESC')
                        ->get();
       $jsonData["rows"] = $naissances->toArray();
       $jsonData["total"] = $naissances->count();
       return response()->json($jsonData);
    }
    public function listeNaissancesByDate($dates){
        $date = Carbon::createFromFormat('d-m-Y', $dates);
       $naissances = Naissance::with('nationalite_mere','nationalite_pere','fonction_pere','fonction_mere','fonction_declarant','fonction_temoin_1','fonction_temoin_2')
                        ->whereDate('naissances.date_naissance_enfant','=', $date)
                        ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_temoin_1, "%d-%m-%Y") as date_naissance_temoin_1s'),DB::raw('DATE_FORMAT(naissances.date_naissance_temoin_2, "%d-%m-%Y") as date_naissance_temoin_2s'),DB::raw('DATE_FORMAT(naissances.mention_date_mariage, "%d-%m-%Y") as mention_date_mariages'),DB::raw('DATE_FORMAT(naissances.mention_date_divorce, "%d-%m-%Y") as mention_date_divorces'),DB::raw('DATE_FORMAT(naissances.mention_date_deces, "%d-%m-%Y") as mention_date_decess'),DB::raw('DATE_FORMAT(naissances.date_naissance_declarant, "%d-%m-%Y") as date_naissance_declarants'),DB::raw('DATE_FORMAT(naissances.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(naissances.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(naissances.date_naissance_mere, "%d-%m-%Y") as date_naissance_meres'),DB::raw('DATE_FORMAT(naissances.date_naissance_pere, "%d-%m-%Y") as date_naissance_peres'),DB::raw('DATE_FORMAT(naissances.date_requisition, "%d-%m-%Y") as date_requisitions'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance_enfants'))
                        ->orderBy('naissances.id', 'DESC')
                        ->get();
       $jsonData["rows"] = $naissances->toArray();
       $jsonData["total"] = $naissances->count();
       return response()->json($jsonData);
    }
    public function listeNaissancesBySexe($sexe){
       $naissances = Naissance::with('nationalite_mere','nationalite_pere','fonction_pere','fonction_mere','fonction_declarant','fonction_temoin_1','fonction_temoin_2')
                ->Where([['naissances.deleted_at', NULL],['naissances.sexe',$sexe]]) 
                ->select('naissances.*', DB::raw('DATE_FORMAT(naissances.date_naissance_temoin_1, "%d-%m-%Y") as date_naissance_temoin_1s'), DB::raw('DATE_FORMAT(naissances.date_naissance_temoin_2, "%d-%m-%Y") as date_naissance_temoin_2s'), DB::raw('DATE_FORMAT(naissances.mention_date_mariage, "%d-%m-%Y") as mention_date_mariages'), DB::raw('DATE_FORMAT(naissances.mention_date_divorce, "%d-%m-%Y") as mention_date_divorces'), DB::raw('DATE_FORMAT(naissances.mention_date_deces, "%d-%m-%Y") as mention_date_decess'), DB::raw('DATE_FORMAT(naissances.date_naissance_declarant, "%d-%m-%Y") as date_naissance_declarants'), DB::raw('DATE_FORMAT(naissances.date_retrait, "%d-%m-%Y") as date_retraits'), DB::raw('DATE_FORMAT(naissances.date_declaration, "%d-%m-%Y") as date_declarations'), DB::raw('DATE_FORMAT(naissances.date_naissance_mere, "%d-%m-%Y") as date_naissance_meres'), DB::raw('DATE_FORMAT(naissances.date_naissance_pere, "%d-%m-%Y") as date_naissance_peres'), DB::raw('DATE_FORMAT(naissances.date_requisition, "%d-%m-%Y") as date_requisitions'), DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'), DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance_enfants'))
                ->orderBy('naissances.id', 'DESC')
                ->get();
        $jsonData["rows"] = $naissances->toArray();
       $jsonData["total"] = $naissances->count();
       return response()->json($jsonData);
    }
    
    public function findActeNaissanceById($id){
        $naissances = Naissance::where([['naissances.deleted_at', NULL],['naissances.id',$id]]) 
                ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance'))
                ->get();
       $jsonData["rows"] = $naissances->toArray();
       $jsonData["total"] = $naissances->count();
       return response()->json($jsonData);
    }
    
    public function listeNaissanceByAn(){
        $listes = Naissance::where('naissances.deleted_at',null)
                        ->select(DB::raw('count(*) as nombre'),DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%Y") as year'))
                        ->groupBy('year')->orderBy('year','desc')->get();
       return $listes;
    }
    
    public function listeNaissanceByMois(){
       $listes = Naissance::where('naissances.deleted_at',null)
                        ->whereYear('naissances.date_naissance_enfant', date('Y'))
                        ->select(DB::raw('count(*) as nombre'),DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%m") as month'))
                        ->groupBy('month')->get();
       return $listes;
    }
    
    public function listeNaissanceByMoisAnnee($annee){
       $listes = Naissance::where('naissances.deleted_at',null)
                        ->whereYear('naissances.date_naissance_enfant', $annee)
                        ->select(DB::raw('count(*) as nombre'),DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%m") as month'))
                        ->groupBy('month')->get();
       return $listes;
    }

    public function listeNaissanceBySecteur(){
        $listes = Naissance::where('naissances.deleted_at',null)
                        ->whereYear('naissances.date_naissance_enfant', date('Y'))
                        ->select(DB::raw('count(*) as nombre'),'naissances.lieu_naissance_enfant')
                        ->groupBy('lieu_naissance_enfant')->get();
       return $listes;
    }
    
    public function listeNaissanceBySecteurAnnee($annee){
        $listes = Naissance::where('naissances.deleted_at',null)
                        ->whereYear('naissances.date_naissance_enfant', $annee)
                        ->select(DB::raw('count(*) as nombre'),'naissances.lieu_naissance_enfant')
                        ->groupBy('lieu_naissance_enfant')->get();
       return $listes;
    }

    public function listeNouveauxMajeurs(){
        $nassances = Naissance::where('deleted_at',null)
                        ->whereYear('date_naissance_enfant','=', now()->year-21)->where('sexe','=','Masculin')
                        ->orWhereYear('date_naissance_enfant','=', now()->year-18)->where('sexe','=','Feminin')
                        ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance'),DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%Y") as annee_naissance'))
                        ->get();
        return $nassances;
    }
    
    public function listeNouveauxMajeursPeriode($debu,$fin){
        $date1 = Carbon::createFromFormat('d-m-Y', $debu);
        $date2 = Carbon::createFromFormat('d-m-Y', $fin);
        $nassances = Naissance::where('deleted_at',null)
                        ->whereDate('naissances.date_naissance_enfant','>=',$date1)
                        ->whereDate('naissances.date_naissance_enfant','<=', $date2)
                        ->whereYear('date_naissance_enfant','=', now()->year-21)->where('sexe','=','Masculin')
                        ->orWhereYear('date_naissance_enfant','=', now()->year-18)->where('sexe','=','Feminin')
                        ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance'),DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%Y") as annee_naissance'))
                        ->get();
        return $nassances;
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
            try {
                //Vérification pour voir si le numéro d'acte de naissance existe
                $naissance_double = Naissance::where('numero_acte_naissance',$data['numero_acte_naissance'])->whereDate('date_dresser',Carbon::createFromFormat('d-m-Y', $data['date_dresser']))->first();
                 if($naissance_double!=null){
                    throw new Exception("Ce numéro d'acte de naissance existe déjà!");
                }
                
                 //Gestion des messages concernant les champs obligatoires
                if(empty($data['date_naissance_enfant'])){
                    throw new Exception("Le champs date de naissance de l'enfant est vide!");
                }
                if(empty($data['date_declaration'])){
                    throw new Exception("Le champs date de déclaraton est vide!");
                }
                if(empty($data['prenom_enfant'])){
                    throw new Exception("Le champs prénom de l'enfant est vide!");
                }
                if(empty($data['nom_enfant'])){
                    throw new Exception("Le champs nom de l'enfant est vide!");
                }
                if(empty($data['numero_acte_naissance'])){
                    throw new Exception("Le champs numero d'acte de naissance est vide!");
                }
                if(empty($data['lieu_naissance_enfant'])){
                    throw new Exception("Le champs lieu de naissance de l'enfant est vide!");
                }
                if(empty($data['nom_complet_declarant'])){
                    throw new Exception("Le champs nom du déclarant est vide!");
                }
                if(empty($data['date_retrait'])){
                    throw new Exception("Le champs date de retrait de la déclaration est vide!");
                }
                if(empty($data['date_declaration'])){
                    throw new Exception("Le champs date de la déclaration est vide!");
                }
                if(empty($data['date_dresser'])){
                    throw new Exception("Le champs date du dresser est vide!");
                }
                if(empty($data['registre'])){
                    throw new Exception("Le champs registre est vide!");
                }
                
                //L'enfant ne pas être déclaré avant sa naissance
                if(Carbon::createFromFormat('d-m-Y', $data['date_naissance_enfant']) > Carbon::createFromFormat('d-m-Y', $data['date_declaration'])){
                    throw new Exception("La déclaration ne peut pas être faite avant la naissance. Vérifier la date de déclaration et celle de la naissance");
                }
                
                //La date de retrait de la déclaration ne doit pas etre plus petite que la date de la declaration
                if(Carbon::createFromFormat('d-m-Y', $data['date_declaration']) > Carbon::createFromFormat('d-m-Y', $data['date_retrait'])){
                    throw new Exception("La date de retrait ne doit pas etre superieur à la date de la déclaration");
                }
                

                //enregistrement de naissance
                $naissance = new Naissance;
                
                //Enfant
                $naissance->prenom_enfant = $data['prenom_enfant'];
                $naissance->nom_enfant = $data['nom_enfant'];
                $naissance->numero_acte_naissance = $data['numero_acte_naissance'];
                $naissance->sexe = $data['sexe'];
                $naissance->lieu_naissance_enfant = $data['lieu_naissance_enfant'];
                $naissance->date_naissance_enfant = Carbon::createFromFormat('d-m-Y', $data['date_naissance_enfant']);
                $naissance->date_dresser = Carbon::createFromFormat('d-m-Y', $data['date_dresser']);
                $naissance->registre = $data['registre'];
                $naissance->heure_naissance_enfant = isset($data['heure_naissance_enfant']) && !empty($data['heure_naissance_enfant']) ? $data['heure_naissance_enfant'] : null;
                
                //Parents
                $naissance->nom_complet_pere = isset($data['nom_complet_pere']) && !empty($data['nom_complet_pere']) ? $data['nom_complet_pere'] : null;
                $naissance->nom_complet_mere = isset($data['nom_complet_mere']) && !empty($data['nom_complet_mere']) ? $data['nom_complet_mere'] : null;
                $naissance->date_naissance_pere =isset($data['date_naissance_pere']) && !empty($data['date_naissance_pere']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_pere']) : null;
                $naissance->date_naissance_mere =isset($data['date_naissance_mere']) && !empty($data['date_naissance_mere']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_mere']) : null;
                $naissance->numero_piece_identite_pere = isset($data['numero_piece_identite_pere']) && !empty($data['numero_piece_identite_pere']) ? $data['numero_piece_identite_pere']:null;
                $naissance->numero_piece_identite_mere = isset($data['numero_piece_identite_mere']) && !empty($data['numero_piece_identite_mere']) ? $data['numero_piece_identite_mere']:null;
                $naissance->adresse_pere = isset($data['adresse_pere']) && !empty($data['adresse_pere']) ? $data['adresse_pere']:null;
                $naissance->adresse_mere = isset($data['adresse_mere']) && !empty($data['adresse_mere']) ? $data['adresse_mere']:null;
                $naissance->lieu_naissance_pere = isset($data['lieu_naissance_pere']) && !empty($data['lieu_naissance_pere']) ? $data['lieu_naissance_pere']:null;
                $naissance->lieu_naissance_mere = isset($data['lieu_naissance_mere']) && !empty($data['lieu_naissance_mere']) ? $data['lieu_naissance_mere']:null;
                $naissance->nationalite_pere = isset($data['nationalite_pere']) && !empty($data['nationalite_pere']) ? $data['nationalite_pere']:null;
                $naissance->nationalite_mere = isset($data['nationalite_mere']) && !empty($data['nationalite_mere']) ? $data['nationalite_mere']:null;
                $naissance->fonction_pere = isset($data['fonction_pere']) && !empty($data['fonction_pere']) ? $data['fonction_pere']:null;
                $naissance->fonction_mere = isset($data['fonction_mere']) && !empty($data['fonction_mere']) ? $data['fonction_mere']:null;
                $naissance->situation_parents = isset($data['situation_parents']) && !empty($data['situation_parents']) ? $data['situation_parents']:null;
                
                //Declarant
                $naissance->nom_complet_declarant = $data['nom_complet_declarant'];
                $naissance->date_declaration = Carbon::createFromFormat('d-m-Y', $data['date_declaration']);
                $naissance->date_retrait = Carbon::createFromFormat('d-m-Y', $data['date_retrait']);
                $naissance->contact_declarant = isset($data['contact_declarant']) && !empty($data['contact_declarant']) ? $data['contact_declarant']:null;
                $naissance->adresse_declarant = isset($data['adresse_declarant']) && !empty($data['adresse_declarant']) ? $data['adresse_declarant']:null;
                $naissance->date_naissance_declarant =isset($data['date_naissance_declarant']) && !empty($data['date_naissance_declarant']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_declarant']) : null;
                $naissance->nombre_copie = isset($data['nombre_copie']) && !empty($data['nombre_copie']) ? $data['nombre_copie']:1;
                $naissance->fonction_declarant = isset($data['fonction_declarant']) && !empty($data['fonction_declarant']) ? $data['fonction_declarant']:null;
                $naissance->montant_declaration = isset($data['montant_declaration']) && !empty($data['montant_declaration']) ? $data['montant_declaration']:0;
                
                //Autres
                $naissance->loi = isset($data['loi']) && !empty($data['loi']) ? $data['loi']:null;
                $naissance->numero_jugement_supletif = isset($data['numero_jugement_supletif']) && !empty($data['numero_jugement_supletif']) ? $data['numero_jugement_supletif']:null;
                $naissance->tribunale = isset($data['tribunale']) && !empty($data['tribunale']) ? $data['tribunale']:null;
                $naissance->mention_date_deces = isset($data['mention_date_deces']) && !empty($data['mention_date_deces']) ? Carbon::createFromFormat('d-m-Y', $data['mention_date_deces']):null;
                $naissance->mention_date_divorce = isset($data['mention_date_divorce']) && !empty($data['mention_date_divorce']) ? Carbon::createFromFormat('d-m-Y', $data['mention_date_divorce']):null;
                $naissance->mention_date_mariage = isset($data['mention_date_mariage']) && !empty($data['mention_date_mariage']) ? Carbon::createFromFormat('d-m-Y', $data['mention_date_mariage']):null;
                $naissance->mention_lieu_mariage = isset($data['mention_lieu_mariage']) && !empty($data['mention_lieu_mariage']) ? $data['mention_lieu_mariage']:null;
                $naissance->mention_lieu_deces = isset($data['mention_lieu_deces']) && !empty($data['mention_lieu_deces']) ? $data['mention_lieu_deces']:null;
                $naissance->mention_conjoint = isset($data['mention_conjoint']) && !empty($data['mention_conjoint']) ? $data['mention_conjoint']:null;
                $naissance->nom_temoin_1 = isset($data['nom_temoin_1']) && !empty($data['nom_temoin_1']) ? $data['nom_temoin_1']:null;
                $naissance->nom_temoin_2 = isset($data['nom_temoin_2']) && !empty($data['nom_temoin_2']) ? $data['nom_temoin_2']:null;
                $naissance->date_naissance_temoin_1 = isset($data['date_naissance_temoin_1']) && !empty($data['date_naissance_temoin_1']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_temoin_1']):null;
                $naissance->date_naissance_temoin_2 = isset($data['date_naissance_temoin_2']) && !empty($data['date_naissance_temoin_2']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_temoin_2']):null;
                $naissance->fonction_temoin_1 = isset($data['fonction_temoin_1']) && !empty($data['fonction_temoin_1']) ? $data['fonction_temoin_1']:null;
                $naissance->fonction_temoin_2 = isset($data['fonction_temoin_2']) && !empty($data['fonction_temoin_2']) ? $data['fonction_temoin_1']:null;
                $naissance->adresse_temoins_1 = isset($data['adresse_temoins_1']) && !empty($data['adresse_temoins_1']) ? $data['adresse_temoins_1']:null;
                $naissance->adresse_temoins_2 = isset($data['adresse_temoins_2']) && !empty($data['adresse_temoins_2']) ? $data['adresse_temoins_2']:null;
                $naissance->dressant = isset($data['dressant']) && !empty($data['dressant']) ? $data['dressant']:null;
                $naissance->numero_requisition = isset($data['numero_requisition']) && !empty($data['numero_requisition']) ? $data['numero_requisition']:null;
                $naissance->signataire = isset($data['signataire']) && !empty($data['signataire']) ? $data['signataire']:null;
                $naissance->langue_reception = isset($data['langue_reception']) && !empty($data['langue_reception']) ? $data['langue_reception']:null;
                $naissance->traducteur = isset($data['traducteur']) && !empty($data['traducteur']) ? $data['traducteur']:null;
                $naissance->date_requisition = isset($data['date_requisition']) && !empty($data['date_requisition']) ? Carbon::createFromFormat('d-m-Y', $data['date_requisition']):null;

                //En attendant 
                $naissance->mention_1 = isset($data['mention_1']) && !empty($data['mention_1']) ? $data['mention_1']:null;
                $naissance->mention_2 = isset($data['mention_2']) && !empty($data['mention_2']) ? $data['mention_2']:null;
                $naissance->mention_3 = isset($data['mention_3']) && !empty($data['mention_3']) ? $data['mention_3']:null;
                $naissance->mention_4 = isset($data['mention_4']) && !empty($data['mention_4']) ? $data['mention_4']:null;
                $naissance->mention_5 = isset($data['mention_5']) && !empty($data['mention_5']) ? $data['mention_5']:null;
                $naissance->mention_6 = isset($data['mention_6']) && !empty($data['mention_6']) ? $data['mention_6']:null;
                $naissance->mention_7 = isset($data['mention_7']) && !empty($data['mention_7']) ? $data['mention_7']:null;
                $naissance->mention_8 = isset($data['mention_8']) && !empty($data['mention_8']) ? $data['mention_8']:null;

                $naissance->created_by = Auth::user()->id;
                $naissance->save();
                $jsonData["data"] = json_decode($naissance);
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
     * @param  \App\Naissance  $naissance
     * @return Response
     */
    public function updateNaissance(Request $request)
    {
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        $naissance = Naissance::find($request->get('idNaissance'));
        
        if($naissance){
             $data = $request->all(); 
            try {

                 //Gestion des messages concernant les champs obligatoires
                if(empty($data['date_naissance_enfant'])){
                    throw new Exception("Le champs date de naissance de l'enfant est vide!");
                }
                if(empty($data['date_declaration'])){
                    throw new Exception("Le champs date de déclaraton est vide!");
                }
                if(empty($data['prenom_enfant'])){
                    throw new Exception("Le champs prénom de l'enfant est vide!");
                }
                if(empty($data['nom_enfant'])){
                    throw new Exception("Le champs nom de l'enfant est vide!");
                }
                if(empty($data['numero_acte_naissance'])){
                    throw new Exception("Le champs numero d'acte de naissance est vide!");
                }
                if(empty($data['lieu_naissance_enfant'])){
                    throw new Exception("Le champs lieu de naissance de l'enfant est vide!");
                }
                if(empty($data['nom_complet_declarant'])){
                    throw new Exception("Le champs nom du déclarant est vide!");
                }
                if(empty($data['date_retrait'])){
                    throw new Exception("Le champs date de retrait de la déclaration est vide!");
                }
                if(empty($data['date_declaration'])){
                    throw new Exception("Le champs date de la déclaration est vide!");
                }
                if(empty($data['date_dresser'])){
                    throw new Exception("Le champs date du dresser est vide!");
                }
                if(empty($data['registre'])){
                    throw new Exception("Le champs registre est vide!");
                }
                
                //L'enfant ne pas être déclaré avant sa naissance
                if(Carbon::createFromFormat('d-m-Y', $data['date_naissance_enfant']) > Carbon::createFromFormat('d-m-Y', $data['date_declaration'])){
                    throw new Exception("La déclaration ne peut pas être faite avant la naissance. Vérifier la date de déclaration et celle de la naissance");
                }
                
                //La date de retrait de la déclaration ne doit pas etre plus petite que la date de la declaration
                if(Carbon::createFromFormat('d-m-Y', $data['date_declaration']) > Carbon::createFromFormat('d-m-Y', $data['date_retrait'])){
                    throw new Exception("La date de retrait ne doit pas etre superieur à la date de la déclaration");
                }
                
                //Modification des infos de naissance
                   //Enfant
                $naissance->prenom_enfant = $data['prenom_enfant'];
                $naissance->nom_enfant = $data['nom_enfant'];
                $naissance->numero_acte_naissance = $data['numero_acte_naissance'];
                $naissance->sexe = $data['sexe'];
                $naissance->lieu_naissance_enfant = $data['lieu_naissance_enfant'];
                $naissance->date_naissance_enfant = Carbon::createFromFormat('d-m-Y', $data['date_naissance_enfant']);
                $naissance->date_dresser = Carbon::createFromFormat('d-m-Y', $data['date_dresser']);
                $naissance->registre = $data['registre'];
                $naissance->heure_naissance_enfant = isset($data['heure_naissance_enfant']) && !empty($data['heure_naissance_enfant']) ? $data['heure_naissance_enfant'] : null;
                
                //Parents
                $naissance->nom_complet_pere = isset($data['nom_complet_pere']) && !empty($data['nom_complet_pere']) ? $data['nom_complet_pere'] : null;
                $naissance->nom_complet_mere = isset($data['nom_complet_mere']) && !empty($data['nom_complet_mere']) ? $data['nom_complet_mere'] : null;
                $naissance->date_naissance_pere =isset($data['date_naissance_pere']) && !empty($data['date_naissance_pere']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_pere']) : null;
                $naissance->date_naissance_mere =isset($data['date_naissance_mere']) && !empty($data['date_naissance_mere']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_mere']) : null;
                $naissance->numero_piece_identite_pere = isset($data['numero_piece_identite_pere']) && !empty($data['numero_piece_identite_pere']) ? $data['numero_piece_identite_pere']:null;
                $naissance->numero_piece_identite_mere = isset($data['numero_piece_identite_mere']) && !empty($data['numero_piece_identite_mere']) ? $data['numero_piece_identite_mere']:null;
                $naissance->adresse_pere = isset($data['adresse_pere']) && !empty($data['adresse_pere']) ? $data['adresse_pere']:null;
                $naissance->adresse_mere = isset($data['adresse_mere']) && !empty($data['adresse_mere']) ? $data['adresse_mere']:null;
                $naissance->lieu_naissance_pere = isset($data['lieu_naissance_pere']) && !empty($data['lieu_naissance_pere']) ? $data['lieu_naissance_pere']:null;
                $naissance->lieu_naissance_mere = isset($data['lieu_naissance_mere']) && !empty($data['lieu_naissance_mere']) ? $data['lieu_naissance_mere']:null;
                $naissance->nationalite_pere = isset($data['nationalite_pere']) && !empty($data['nationalite_pere']) ? $data['nationalite_pere']:null;
                $naissance->nationalite_mere = isset($data['nationalite_mere']) && !empty($data['nationalite_mere']) ? $data['nationalite_mere']:null;
                $naissance->fonction_pere = isset($data['fonction_pere']) && !empty($data['fonction_pere']) ? $data['fonction_pere']:null;
                $naissance->fonction_mere = isset($data['fonction_mere']) && !empty($data['fonction_mere']) ? $data['fonction_mere']:null;
                $naissance->situation_parents = isset($data['situation_parents']) && !empty($data['situation_parents']) ? $data['situation_parents']:null;
                
                //Declarant 
                $naissance->nom_complet_declarant = $data['nom_complet_declarant'];
                $naissance->date_declaration = Carbon::createFromFormat('d-m-Y', $data['date_declaration']);
                $naissance->date_retrait = Carbon::createFromFormat('d-m-Y', $data['date_retrait']);
                $naissance->contact_declarant = isset($data['contact_declarant']) && !empty($data['contact_declarant']) ? $data['contact_declarant']:null;
                $naissance->adresse_declarant = isset($data['adresse_declarant']) && !empty($data['adresse_declarant']) ? $data['adresse_declarant']:null;
                $naissance->date_naissance_declarant =isset($data['date_naissance_declarant']) && !empty($data['date_naissance_declarant']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_declarant']) : null;
                $naissance->nombre_copie = isset($data['nombre_copie']) && !empty($data['nombre_copie']) ? $data['nombre_copie']:1;
                $naissance->fonction_declarant = isset($data['fonction_declarant']) && !empty($data['fonction_declarant']) ? $data['fonction_declarant']:null;
                $naissance->montant_declaration = isset($data['montant_declaration']) && !empty($data['montant_declaration']) ? $data['montant_declaration']:0;
                
                //Autres
                $naissance->loi = isset($data['loi']) && !empty($data['loi']) ? $data['loi']:null;
                $naissance->numero_jugement_supletif = isset($data['numero_jugement_supletif']) && !empty($data['numero_jugement_supletif']) ? $data['numero_jugement_supletif']:null;
                $naissance->tribunale = isset($data['tribunale']) && !empty($data['tribunale']) ? $data['tribunale']:null;
                $naissance->mention_date_deces = isset($data['mention_date_deces']) && !empty($data['mention_date_deces']) ? Carbon::createFromFormat('d-m-Y', $data['mention_date_deces']):null;
                $naissance->mention_date_divorce = isset($data['mention_date_divorce']) && !empty($data['mention_date_divorce']) ? Carbon::createFromFormat('d-m-Y', $data['mention_date_divorce']):null;
                $naissance->mention_date_mariage = isset($data['mention_date_mariage']) && !empty($data['mention_date_mariage']) ? Carbon::createFromFormat('d-m-Y', $data['mention_date_mariage']):null;
                $naissance->mention_lieu_mariage = isset($data['mention_lieu_mariage']) && !empty($data['mention_lieu_mariage']) ? $data['mention_lieu_mariage']:null;
                $naissance->mention_lieu_deces = isset($data['mention_lieu_deces']) && !empty($data['mention_lieu_deces']) ? $data['mention_lieu_deces']:null;
                $naissance->mention_conjoint = isset($data['mention_conjoint']) && !empty($data['mention_conjoint']) ? $data['mention_conjoint']:null;
                $naissance->nom_temoin_1 = isset($data['nom_temoin_1']) && !empty($data['nom_temoin_1']) ? $data['nom_temoin_1']:null;
                $naissance->nom_temoin_2 = isset($data['nom_temoin_2']) && !empty($data['nom_temoin_2']) ? $data['nom_temoin_2']:null;
                $naissance->date_naissance_temoin_1 = isset($data['date_naissance_temoin_1']) && !empty($data['date_naissance_temoin_1']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_temoin_1']):null;
                $naissance->date_naissance_temoin_2 = isset($data['date_naissance_temoin_2']) && !empty($data['date_naissance_temoin_2']) ? Carbon::createFromFormat('d-m-Y', $data['date_naissance_temoin_2']):null;
                $naissance->fonction_temoin_1 = isset($data['fonction_temoin_1']) && !empty($data['fonction_temoin_1']) ? $data['fonction_temoin_1']:null;
                $naissance->fonction_temoin_2 = isset($data['fonction_temoin_2']) && !empty($data['fonction_temoin_2']) ? $data['fonction_temoin_1']:null;
                $naissance->adresse_temoins_1 = isset($data['adresse_temoins_1']) && !empty($data['adresse_temoins_1']) ? $data['adresse_temoins_1']:null;
                $naissance->adresse_temoins_2 = isset($data['adresse_temoins_2']) && !empty($data['adresse_temoins_2']) ? $data['adresse_temoins_2']:null;
                $naissance->dressant = isset($data['dressant']) && !empty($data['dressant']) ? $data['dressant']:null;
                $naissance->numero_requisition = isset($data['numero_requisition']) && !empty($data['numero_requisition']) ? $data['numero_requisition']:null;
                $naissance->signataire = isset($data['signataire']) && !empty($data['signataire']) ? $data['signataire']:null;
                $naissance->langue_reception = isset($data['langue_reception']) && !empty($data['langue_reception']) ? $data['langue_reception']:null;
                $naissance->traducteur = isset($data['traducteur']) && !empty($data['traducteur']) ? $data['traducteur']:null;
                $naissance->date_requisition = isset($data['date_requisition']) && !empty($data['date_requisition']) ? Carbon::createFromFormat('d-m-Y', $data['date_requisition']):null;

                //En attendant 
                $naissance->mention_1 = isset($data['mention_1']) && !empty($data['mention_1']) ? $data['mention_1']:null;
                $naissance->mention_2 = isset($data['mention_2']) && !empty($data['mention_2']) ? $data['mention_2']:null;
                $naissance->mention_3 = isset($data['mention_3']) && !empty($data['mention_3']) ? $data['mention_3']:null;
                $naissance->mention_4 = isset($data['mention_4']) && !empty($data['mention_4']) ? $data['mention_4']:null;
                $naissance->mention_5 = isset($data['mention_5']) && !empty($data['mention_5']) ? $data['mention_5']:null;
                $naissance->mention_6 = isset($data['mention_6']) && !empty($data['mention_6']) ? $data['mention_6']:null;
                $naissance->mention_7 = isset($data['mention_7']) && !empty($data['mention_7']) ? $data['mention_7']:null;
                $naissance->mention_8 = isset($data['mention_8']) && !empty($data['mention_8']) ? $data['mention_8']:null;

                $naissance->updated_by = Auth::user()->id;
                $naissance->save();
               $jsonData["data"] = json_decode($naissance);
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
     * @param  \App\Naissance  $naissance
     * @return Response
     */
    public function destroy(Naissance $naissance)
    {
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($naissance){
                try {
                    $naissance->update(['deleted_by' => Auth::user()->id]);
                    $naissance->delete();
                    $jsonData["data"] = json_decode($naissance);
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
    
    
    //Extrait de naissance
    public function extraitDeclarationNaissancePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->extraitDeclarationNaissance($id));
        $naissance = Naissance::find($id);
        return $pdf->stream('extrait_'.$naissance->numero_acte_naissance.'.pdf');
    }
    public function extraitDeclarationNaissance($id){
        $outPut = $this->content($id);
        return $outPut;
    }
    public function content($id){
        $generator = new BarcodeGeneratorPNG();
        $naissance = Naissance::where([['naissances.deleted_at', NULL],['naissances.id',$id]]) 
                ->leftJoin('nations as nationPere', 'nationPere.id','=','naissances.nationalite_pere')
                ->leftJoin('nations as nationMere', 'nationMere.id','=','naissances.nationalite_mere')
                ->leftJoin('fonctions as fonctionMere', 'fonctionMere.id','=','naissances.fonction_mere')
                ->leftJoin('fonctions as fonctionPere', 'fonctionPere.id','=','naissances.fonction_pere')
                ->select('naissances.*','nationMere.libelle_nation as libelle_nation_mere','nationPere.libelle_nation as libelle_nation_pere','fonctionMere.libelle_fonction as libelle_fonction_mere','fonctionPere.libelle_fonction as libelle_fonction_pere')
                ->first();
        $month = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
        $date = $naissance->date_naissance_enfant;
        $day = date('d', strtotime($date));
        $mont = date('m', strtotime($date));
        $an = date('Y', strtotime($date));
        $heureNaissance = date('H', strtotime($naissance->heure_naissance_enfant));
        $minuteNaissance = date('i', strtotime($naissance->heure_naissance_enfant));
        
        if($heureNaissance < 10){
            $heureN = NumberToLetter(number_format(substr($heureNaissance,1,1)));
            if($heureN == "un"){
                $heureN = "une";
            }
        }else{
            $heureN = NumberToLetter(number_format($heureNaissance));
            if($heureN == "vingt et un"){
                $heureN = "vingt et une";
            }
        }
        if($minuteNaissance < 10){
            $minuteN = NumberToLetter(number_format(substr($minuteNaissance,1,1)));
             if($minuteN == "un"){
                $minuteN = "une";
            }
        }else{
            $minuteN = NumberToLetter(number_format($minuteNaissance));
            if($minuteN == "vingt et un"){
                $minuteN = "vingt et une";
            }
            if($minuteN == "trente et un"){
                $minuteN = "trente et une";
            }
            if($minuteN == "quarante et un"){
                $minuteN = "quarante et une";
            }
            if($minuteN == "cinquante et un"){
                $minuteN = "cinquante et une";
            }
        }
       
        $day == 01 ? $jour = 'premier' : $jour = NumberToLetter(number_format($day));
        $naissance->sexe == 'Masculin' ? $sexe = 'Fils' : $sexe = 'Fille';
        $naissance->sexe == 'Masculin' ? $ne = 'né' : $ne = 'née';
        !empty($naissance->nom_complet_pere)? $pere = $naissance->nom_complet_pere : $pere='';
        !empty($naissance->nom_complet_mere)? $mere = $naissance->nom_complet_mere : $mere='';
        $naissance->mention_date_deces!=null ? $neantDateDeces = date("d-m-Y", strtotime($naissance->mention_date_deces)) : $neantDateDeces ='…………………Néant………………';
        $naissance->mention_date_divorce!=null ? $neantDateDivorce = date("d-m-Y", strtotime($naissance->mention_date_divorce)) : $neantDateDivorce ='………………………Néant………………………………';
        $naissance->mention_date_mariage!=null ? $neantDateMariage = date("d-m-Y", strtotime($naissance->mention_date_mariage)) : $neantDateMariage ='……………………………Néant………………………';
        $naissance->mention_lieu_mariage!=null ? $neantLieuMariage = $naissance->mention_lieu_mariage : $neantLieuMariage ='……………………Néant……………...';
        $naissance->mention_lieu_deces!=null ? $neantLieuDeces = $naissance->mention_lieu_deces : $neantLieuDeces ='…………………Néant……………………………………';
        $naissance->mention_conjoint!=null ? $neantConjoint = $naissance->mention_conjoint : $neantConjoint ='………………………………………Néant…………………………………………………………………';
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);
        $nom_initial = strstr(Auth::user()->full_name, ' ', true); 
        $nmJS ="";
        $sigelJs ="";
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
                                            margin-left: 18px;
                                           
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
                       <b> Mairie ".$this->premierLetre()."".$this->infosConfig()->commune."</b><br/> ";
        if($this->infosConfig()->adresse_marie != null) {
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
        $content.="</p>
                   <p style='font-size:20;'><b> ETAT - CIVIL</b><p>
                    <p style='line-height:1.2; align:left;'>
                    <hr width='90%'/>
                    <b>N° ".$naissance->numero_acte_naissance." DU ".date("d-m-Y", strtotime($naissance->date_dresser))."</b> du registre
                     <hr width='90%'/></p>
                    <p style='line-height:1.5; text-align:left; margin-left: 18px;'>
                    <b>NAISSANCE DE :</b> <br/>
                    ".$naissance->nom_enfant." <br/>".$naissance->prenom_enfant."</p>";
                    if($naissance->numero_jugement_supletif!=null){
                        $sigelJs = "/Jgmt S";
                        $nmJS = $naissance->nom_enfant;
                        $content.="<p style='text-align:left;margin-left: 18px;'>
                        JS <b>".$naissance->numero_jugement_supletif."</b><br/><b>".$naissance->tribunale. 
                        "</p>";
                    }
                    if($naissance->heure_naissance_enfant!=null){
                        $heure_de_naissace = "<br/> à <b>".$heureN." heure(s) ".$minuteN." minute(s)</b>";
                    }else{
                        $heure_de_naissace = "";
                    }
                    if($naissance->numero_requisition!=null){
                        $content.="<p style='text-align:left;margin-left: 18px;'>
                        Réquisition <b>".$naissance->numero_requisition."</b> du <b>".date("d-m-Y", strtotime($naissance->date_requisition)). 
                        "</b></p>";
                    }
            $content.="</div>
                <div class='fixed-header-right'><span style='opacity:0.35;font-style: italic;'>".$nom_initial.$sigelJs."</span>                   
                    <div style='text-align:center;'>
                            REPUBLIQUE DE COTE D'IVOIRE<br/>Union-Discipline-Travail<hr width='50'/>
                           <span style='font-size:40px; font-weight: bold;'> EXTRAIT </span>
                        <p>DU REGISTRE DES ACTES DE L'ETAT CIVIL POUR L'ANNEE <b>".$naissance->registre."</b></p>";
                        if($naissance->loi){
                          $content.='<p>'.$naissance->loi.'</p>';
                        }
                    $content.="</div>
                    <div style='line-height:2;'>
                    Le <b>".$jour." ".$month[$mont]." ".NumberToLetter($an)."</b>".$heure_de_naissace."<br/>
                    est ".$ne." à <b>".$naissance->lieu_naissance_enfant."<br/>".$nmJS." ".$naissance->prenom_enfant."</b></div><br/>
                    <div style='line-height:1.5;'>".$sexe." de <b>".$pere."</b><br/>Profession <b>".$naissance->libelle_fonction_pere."</b><br/> domicilié à <b>".$naissance->adresse_pere."</b><br/>Nationalité  <b>".$naissance->libelle_nation_pere."</b></div><br/><div style='line-height:1.5;'>et de <b>".$mere."</b><br/>Profession <b>".$naissance->libelle_fonction_mere."</b><br/> domiciliée à <b>".$naissance->adresse_mere."</b><br/>Nationalité  <b>".$naissance->libelle_nation_mere."</b>
                    </div></div><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
                <div class='container'><br/>";
                $content.="<p>".$naissance->mention_2."</p> 
                <hr style='width:100%;'/>
                    <hr style='width:100%;'/>
                    <div style='text-align:center;'>";
                  $content.="<b>MENTIONS</b> (éventuellement) :
                    </div>
                    <p>Marié(e) le <b>".$neantDateMariage."</b> à <b>".$neantLieuMariage."</b></p>
                    <p>Avec <b>".$neantConjoint."<b></p>
                    <p>Mariage dissous par décision de divorce en date du <b>".$neantDateDivorce."</b></p>
                    <p>Décédé le <b>".$neantDateDeces."</b> à <b>".$neantLieuDeces."</b></p>
                    <p><i>Certifié le présent extrait conforme aux indications portées au registre.</i></p>
                    <p style='float:right;'><i>Délivré à <b>".$this->infosConfig()->commune."</b>, le <b>".date("d")."-".$month[date("m")]."-".date("Y")."</b></i><br/>L'Officier de l’Etat Civil<br/>
                        (Signature)</p>
                </div>";
            $content.="<br/><img src='data:image/png;base64,".base64_encode($generator->getBarcode(123456789, $generator::TYPE_CODE_128))."'>"; 
            $content.="</body>
        </html>";     
     return $content;
    }
    
    //Copie intégrale de naissance
    public function extraitCopieIntegralePdf($id){
        $pdf = \App::make("dompdf.wrapper");
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->extraitCopieIntegrale($id));
        $naissance = Naissance::find($id);
        return $pdf->stream('copie_integrale_acte_naissance_'.$naissance->numero_acte_naissance.'.pdf');
    }
    public function extraitCopieIntegrale($id){
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);

        $naissance = Naissance::where([['naissances.deleted_at', NULL],['naissances.id',$id]]) 
                        ->leftJoin('fonctions as fonctionPere', 'fonctionPere.id','=','naissances.fonction_pere')
                        ->leftJoin('fonctions as fonctionMere', 'fonctionMere.id','=','naissances.fonction_mere')
                        ->leftJoin('fonctions as fonctionTemoin1', 'fonctionTemoin1.id','=','naissances.fonction_temoin_1')
                        ->leftJoin('fonctions as fonctionTemoin2', 'fonctionTemoin2.id','=','naissances.fonction_temoin_2')
                        ->leftJoin('fonctions as fonctionDeclarant', 'fonctionDeclarant.id','=','naissances.fonction_declarant')
                        ->leftJoin('nations as nationPere', 'nationPere.id','=','naissances.nationalite_pere')
                        ->leftJoin('nations as nationMere', 'nationMere.id','=','naissances.nationalite_mere')
                        ->select('naissances.*','nationMere.libelle_nation as libelle_nationalite_mere','nationPere.libelle_nation as libelle_nationalite_pere','fonctionTemoin2.libelle_fonction as libelle_fonction_temoin_2','fonctionTemoin1.libelle_fonction as libelle_fonction_temoin_1','fonctionDeclarant.libelle_fonction as libelle_fonction_declarant','fonctionMere.libelle_fonction as libelle_fonction_mere','fonctionPere.libelle_fonction as libelle_fonction_pere')
                        ->first();
        $month = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
        $date = $naissance->date_naissance_enfant;
        $dateDr = $naissance->date_dresser;
        $day = date('d', strtotime($date));
        $mont = date('m', strtotime($date));
        $an = date('Y', strtotime($date));
        $daydr = date('d', strtotime($dateDr));
        $montdr = date('m', strtotime($dateDr));
        $andr = date('Y', strtotime($dateDr));
        $heureNaissance = date('H', strtotime($naissance->heure_naissance_enfant));
        $minuteNaissance = date('i', strtotime($naissance->heure_naissance_enfant));
        
        if($heureNaissance < 10){
            $heureN = NumberToLetter(number_format(substr($heureNaissance,1,1)));
            if($heureN == "un"){
                $heureN = "une";
            }
        }else{
            $heureN = NumberToLetter(number_format($heureNaissance));
            if($heureN == "vingt et un"){
                $heureN = "vingt et une";
            }
        }
        if($minuteNaissance < 10){
            $minuteN = NumberToLetter(number_format(substr($minuteNaissance,1,1)));
             if($minuteN == "un"){
                $minuteN = "une";
            }
        }else{
            $minuteN = NumberToLetter(number_format($minuteNaissance));
            if($minuteN == "vingt et un"){
                $minuteN = "vingt et une";
            }
            if($minuteN == "trente et un"){
                $minuteN = "trente et une";
            }
            if($minuteN == "quarante et un"){
                $minuteN = "quarante et une";
            }
            if($minuteN == "cinquante et un"){
                $minuteN = "cinquante et une";
            }
        }
        
        $day == 01 ? $jour = 'premier' : $jour = NumberToLetter(number_format($day));
        $daydr == 01 ? $jourdr = 'premier' : $jourdr = NumberToLetter(number_format($daydr));
        !empty($naissance->nom_complet_pere)? $pere = $naissance->nom_complet_pere : $pere='..........................................................................................................';
        !empty($naissance->nom_complet_mere)? $mere = $naissance->nom_complet_mere : $mere='..................................................................................................................';
        $aujourdhui = date("Y-m-d");
    
        if($naissance->date_naissance_pere!=null) {
            $dateNaissancePere = $naissance->date_naissance_pere;
            $dayNP = date('d', strtotime($dateNaissancePere));
            $montNP = date('m', strtotime($dateNaissancePere));
            $anNP = date('Y', strtotime($dateNaissancePere));
            $dayNP == 01 ? $jourNP = 'premier' : $jourNP = NumberToLetter(number_format($dayNP));
            $montNPaff = $month[$montNP];$anNPaff= NumberToLetter($anNP);
            $dateNaissancePere =  $jourNP." ".$montNPaff." ".$anNPaff;
        }else{
            $dateNaissancePere=".............................................................................................................................";
        }
        if($naissance->libelle_fonction_pere!=null){
            $libelle_fonction_pere = $naissance->libelle_fonction_pere;
        }else{
            $libelle_fonction_pere="...............................................................................................................";
        }
        if($naissance->lieu_naissance_pere!=null){
            $lieu_naissance_pere = $naissance->lieu_naissance_pere;
        }else{
            $lieu_naissance_pere="..................................................................................................................................";
        }
        if($naissance->adresse_pere!=null){
            $domicile_pere = $naissance->adresse_pere;
        }else{
            $domicile_pere=".................................................................................................................";
        }
        if($naissance->libelle_nationalite_pere!=null){
            $libelle_nation_pere = $naissance->libelle_nationalite_pere;
        }else{
            $libelle_nation_pere=".................................................................................................................";
        }
        if($naissance->date_naissance_mere!=null) {
            $dateNaissanceMere = $naissance->date_naissance_mere;
            $dayNM = date('d', strtotime($dateNaissanceMere));
            $montNM = date('m', strtotime($dateNaissanceMere));
            $anNM = date('Y', strtotime($dateNaissanceMere));
            $dayNM == 01 ? $jourNM = 'premier' : $jourNM = NumberToLetter(number_format($dayNM));
            $montNMaff = $month[$montNM];$anNMaff= NumberToLetter($anNM);
            $dateNaissanceMere =  $jourNM." ".$montNMaff." ".$anNMaff;
        }else{
            $dateNaissanceMere=".........................................................................................................................";
        }
        if($naissance->lieu_naissance_mere!=null){
            $lieu_naissance_mere = $naissance->lieu_naissance_mere;
        }else{
            $lieu_naissance_mere="................................................................................................................................";
        }
        if($naissance->libelle_fonction_mere!=null){
            $libelle_fonction_mere = $naissance->libelle_fonction_mere;
        }else{
            $libelle_fonction_mere="............................................................................................................";
        }
        if($naissance->adresse_mere!=null){
            $adresse_mere = $naissance->adresse_mere;
        }else{
            $adresse_mere="..............................................................................................................";
        }
        if($naissance->libelle_nationalite_mere!=null){
            $libelle_nation_mere = $naissance->libelle_nationalite_mere;
        }else{
            $libelle_nation_mere="...............................................................................................................";
        }
        if($naissance->date_naissance_declarant!=null){
            $dateNaissanceDecl = $naissance->date_naissance_declarant;
            $diffD = date_diff(date_create($dateNaissanceDecl), date_create($aujourdhui));
            $age_d = $diffD->format('%y');
            $age_declarant = $age_d." ans";
        }
        if($naissance->adresse_declarant!=null){
            $adresse_declarant = $naissance->adresse_declarant;
        }
        if($naissance->libelle_fonction_declarant!=null){
            $libelle_fonction_declarant = $naissance->libelle_fonction_declarant;
        }
        if($naissance->langue_reception!=null){
            $langue_reception = $naissance->langue_reception;
        }else{
            $langue_reception=".........................................................................................................";
        }
        if($naissance->traducteur!=null){
            $traducteur = $naissance->traducteur;
        }else{
            $traducteur="..........................................................";
        }
        if($naissance->dressant!=null){
            $nom_dressant = $naissance->dressant;
        }else{
            $nom_dressant="......................................................................................................................";
        }
        if($naissance->date_naissance_temoin_1!=null){ 
            $dateNaissanceT1 = $naissance->date_naissance_temoin_1;
            $diffT1 = date_diff(date_create($dateNaissanceT1), date_create($aujourdhui));
            $age_t1 = $diffT1->format('%y');
            $age_temoin_1 = $age_t1." ans";
        }
        if($naissance->date_naissance_temoin_2!=null){
            $dateNaissanceT2 = $naissance->date_naissance_temoin_2;
            $diffT2 = date_diff(date_create($dateNaissanceT2), date_create($aujourdhui));
            $age_t2 = $diffT2->format('%y');
            $age_temoin_2 = $age_t2." ans";
        }
        
        if($naissance->libelle_fonction_temoin_1!=null){
            $libelle_fonction_temoin_1 = $naissance->libelle_fonction_temoin_1." à ".$naissance->adresse_temoins_1;
        }
        if($naissance->libelle_fonction_temoin_2!=null){
            $libelle_fonction_temoin_2 = $naissance->libelle_fonction_temoin_2." à ".$naissance->adresse_temoins_2;
        }
        if($naissance->adresse_temoins_1!=null){
            $adresse_temoin1 = " à ".$naissance->adresse_temoins_1;
        }
        if($naissance->adresse_temoins_2!=null){
            $adresse_temoin2 = " à ".$naissance->adresse_temoins_2;
        }
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
                    line-height:1.3;
                    margin:205px 0;
                    width: 25%;
                    padding: 20px 0;
                }
                .fixed-content-right{
                    font-size:15px;
                    line-height:1.5;
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
                    <p style='font-size:25px;'><b><u>COPIE INTEGRALE D'EXTRAIT D'ACTE DE NAISSANCE</u></b></p>
                </div>";
               $nom="";
               $out_put.="<div class='line-horizontal'></div>";
               $out_put.="<div class='fixed-content-left'>
                            <b>Act N° ".$naissance->numero_acte_naissance." du ".date('d', strtotime($naissance->date_dresser))." ".$month[date('m', strtotime($naissance->date_dresser))]." ".date('Y', strtotime($naissance->date_dresser))."</b><br/>
                            Naissance de :<br/>
                            <b>".$naissance->nom_enfant."</b><br/>
                            <b>".$naissance->prenom_enfant."</b><br/>";
                            if($naissance->numero_jugement_supletif!=null){
                                $out_put.="JS Suplétif : <b>".$naissance->numero_jugement_supletif."</b><br/>";
                                $nom = $naissance->nom_enfant;
                            }
                            if($naissance->loi!=null){
                                $out_put.="LOI : <b>".$naissance->loi."</b><br/>";
                            }
                            if($naissance->mention_2!=null){
                                $out_put.="<b><u>Mention rectification</u></b><br/>".$naissance->mention_2."<br/>";    
                            }
                            if($naissance->heure_naissance_enfant!=null){
                                $heure_de_naissace = "<b>".$heureN." heure(s) ".$minuteN." minute(s)</b>";
                            }else{
                                $heure_de_naissace = "<b>....................................................................................................................................</b>";
                            }
                            if($naissance->mention_1!=null){
                                $out_put.="<b><u>Mention</u></b><br/>".$naissance->mention_1."<br/>";    
                            }
                            if($naissance->mention_3!=null){
                                $out_put.="<b><u>Mention</u></b><br/>".$naissance->mention_3."<br/>";    
                            }
                            if($naissance->mention_4!=null){
                                $out_put.="<b><u>Mention</u></b><br/>".$naissance->mention_4."<br/>";    
                            }
                            if($naissance->mention_5!=null){
                                $out_put.="<b><u>Mention</u></b><br/>".$naissance->mention_5."<br/>";    
                            }
                            if($naissance->mention_6!=null){
                                $out_put.="<b><u>Mention</u></b><br/>".$naissance->mention_6."<br/>";    
                            }
                            if($naissance->mention_7!=null){
                                $out_put.="<b><u>Mention</u></b><br/>".$naissance->mention_7."<br/>";    
                            }
                            if($naissance->mention_8!=null){
                                $out_put.="<b><u>Mention</u></b><br/>".$naissance->mention_8."";    
                            }
                            
               $out_put.="</div><div class='line-vertical'></div>";
               $out_put.="<div class='fixed-content-right'>
                    1. Le <b>".$jour." ".$month[$mont]." ".NumberToLetter($an)."</b><br/>
                    2. à ".$heure_de_naissace."<br/>
                    3. est né(e) à <b>".$naissance->lieu_naissance_enfant."</b><br/>
                    4. l'enfant <b>".$nom." ".$naissance->prenom_enfant."</b><br/>
                    5. de sexe <b>".$naissance->sexe."</b><br/>
                    6. Ayant pour Père <b>".$pere."</b><br/>
                    7. né le <b>".$dateNaissancePere."</b><br/>   
                    8. à <b>".$lieu_naissance_pere."</b><br/>
                    9. de profession <b>".$libelle_fonction_pere."</b><br/>
                    10. domicilié à <b>".$domicile_pere."</b><br/>
                    11. Nationalité <b>".$libelle_nation_pere."</b><br/>
                    12. Pour Mère <b>".$mere."</b><br/>
                    13. née le <b>".$dateNaissanceMere."</b><br/> 
                    14. à <b>".$lieu_naissance_mere."</b><br/>
                    15. de profession <b>".$libelle_fonction_mere."</b><br/>
                    16. domiciliée à <b>".$adresse_mere."</b><br/>
                    17. Nationalité <b>".$libelle_nation_mere."</b><br/>
                    18. Dressé le <b>".$jourdr." ".$month[$montdr]." ".NumberToLetter($andr)."</b><br/>
                    19. Sur la déclaration <b>".$naissance->nom_complet_declarant."</b><br/>
                    20. Reçue en langue <b>".$langue_reception."</b><br/>
                    21. Avec l'assistance de <b>".$traducteur."</b>, <br/>interprète, ayant prêté devant Nous le serment prévu par la loi.<br/>
                    22. Par nous <b>".$nom_dressant."</b><br/>
                    23. Lecture faite, et le déclarant invité à lire l’acte<br/>
                    24. L’acte ayant été traduit par l’interprète<br/>
                    25. Nous avons signé avec le/la déclarant(e)<br/><br/>
                    <p style='float:right;'>".$this->infosConfig()->commune.", le ".date("d")." ".$month[date("m")]." ".date("Y")."&nbsp;&nbsp;&nbsp;<br/>
                        Pour copie certifier conforme&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>
                        L'Officier de l'Etat-Civil&nbsp;&nbsp;&nbsp;
                    </p>
                </div></body>";
        return $out_put;
    }

        //Etat liste des naissance par année
    public function ficheNaissanceParAnnneePdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheNaissanceParAnnnee());
        return $pdf->stream('liste_naissances_par_annee.pdf');
    }
    
    public function ficheNaissanceParAnnnee(){
        $datas = $this->listeNaissanceByAn();
        $outPut = $this->headerFiche();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des naissances par année</u></h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="45%">Année</th>
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
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').' naissance(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Etat liste des naissance par mois
    public function ficheNaissanceParMoisPdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheNaissanceParMois());
        return $pdf->stream('liste_naissances_par_mois.pdf');
    }
    
    public function ficheNaissanceParMois(){
        $datas = $this->listeNaissanceByMois();
        $moisFr = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
        $outPut = $this->headerFiche();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des naissances par mois en '.date("Y").'</h3>
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
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').' naissance(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Etat liste des naissance par mois sur une année
    public function ficheNaissanceParMoisAnnneePdf($annee){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheNaissanceParMoisAnnnee($annee));
        return $pdf->stream('liste_naissances_par_mois_en_'.$annee.'.pdf');
    }
    
    public function ficheNaissanceParMoisAnnnee($annee){
        $datas = $this->listeNaissanceByMoisAnnee($annee);
        $moisFr = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
        $outPut = $this->headerFiche();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des naissances de '.$annee.' détaillée par mois</h3>
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
        $outPut.='<br/> Nombre totale en '.$annee.':<b> '.number_format($total, 0, ',', ' ').' naissance(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Etat liste des naissances par secteur 
    public function ficheNaissanceParSecteurPdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheNaissanceParSecteur());
        return $pdf->stream('liste_naissances_par_lieu.pdf');
    }
    
    public function ficheNaissanceParSecteur(){
        $datas = $this->listeNaissanceBySecteur();
        $outPut = $this->headerFiche();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des naissances par lieu en '.date("Y").'</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="45%">Lieu</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Nombre</th>
                        </tr></div>';
         $total = 0;
       foreach ($datas as $data){
           $total = $total + $data->nombre;
           $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="center">'.$data->lieu_naissance_enfant.'</td>
                            <td  cellspacing="0" border="2" align="center">'.number_format($data->nombre, 0, ',', ' ').'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').' naissance(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Etat liste des naissance par lieu sur une année
    public function ficheNaissanceParSecteurAnnneePdf($annee){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheNaissanceParSecteurAnnnee($annee));
        return $pdf->stream('liste_naissances_par_mois_en_'.$annee.'.pdf');
    }
    
    public function ficheNaissanceParSecteurAnnnee($annee){
        $datas = $this->listeNaissanceBySecteurAnnee($annee);
        $outPut = $this->headerFiche();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des naissances de '.$annee.' détaillée par lieu</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="45%">Lieu</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Nombre</th>
                        </tr></div>';
         $total = 0;
       foreach ($datas as $data){
           $total = $total + $data->nombre;
           $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="center">'.$data->lieu_naissance_enfant.'</td>
                            <td  cellspacing="0" border="2" align="center">'.number_format($data->nombre, 0, ',', ' ').'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale en '.$annee.':<b> '.number_format($total, 0, ',', ' ').' naissance(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Etat liste des nouvaeux majeurs
    public function ficheNouveauxMajeursPdf(){
         $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheNouveauxMajeurs());
        return $pdf->stream('liste_nouveaux_majeurs.pdf');
    }

    public function ficheNouveauxMajeurs(){
        $datas = $this->listeNouveauxMajeurs();
        $outPut = $this->headerFiche();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des nouveaux majeurs '.date("Y").'</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="35%" align="center">Prénom(s)</th>
                            <th cellspacing="0" border="2" width="25%" align="center">Nom</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Date de naissance</th>
                            <th cellspacing="0" border="2" width="30%" align="center">Lieu de naissance</th>
                        </tr></div>';
         $total = 0;
       foreach ($datas as $data){
           $total = $total + 1;
           $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="center">'.$data->prenom_enfant.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->nom_enfant.'</td>
                            <td  cellspacing="0" border="2" align="center">'.date('d-m-Y', strtotime($data->date_naissance_enfant)).'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->lieu_naissance_enfant.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').' nouveaux majeurs</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Liste des nouveaux majeurs sur une paériode
    
    public function ficheNouveauxMajeursPeriodePdf($debut,$fin){
         $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheNouveauxMajeursPeriode($debut,$fin));
        return $pdf->stream('liste_nouveaux_majeurs_du_'.$debut.'_au_'.$fin.'.pdf');
    }

    public function ficheNouveauxMajeursPeriode($debut,$fin){
        $datas = $this->listeNouveauxMajeursPeriode($debut,$fin);
        $outPut = $this->headerFiche();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des nouveaux majeurs du '.$debut.' au '.$fin.'</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="35%" align="center">Prénom(s)</th>
                            <th cellspacing="0" border="2" width="25%" align="center">Nom</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Date de naissance</th>
                            <th cellspacing="0" border="2" width="30%" align="center">Lieu de naissance</th>
                        </tr></div>';
         $total = 0;
       foreach ($datas as $data){
           $total = $total + 1;
           $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="center">'.$data->prenom_enfant.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->nom_enfant.'</td>
                            <td  cellspacing="0" border="2" align="center">'.date('d-m-Y', strtotime($data->date_naissance_enfant)).'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->lieu_naissance_enfant.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').' nouveaux majeurs</b>';
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
