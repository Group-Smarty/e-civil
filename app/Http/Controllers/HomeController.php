<?php

namespace App\Http\Controllers;

use App\Models\Courrier\Annuaire;
use App\Models\Courrier\Courrier;
use App\Models\Ecivil\Decede;
use App\Models\Ecivil\Demande;
use App\Models\Ecivil\Mariage;
use App\Models\Ecivil\Naissance;
use App\Models\Recrutement\Contrat;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use function now;
use function view;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {   $courriers = Courrier::whereDay('date_courrier',date('d'))->get();
        $nassancesAnnee = Naissance::whereYear('date_naissance_enfant', date('Y'))->get();
        $nassances = Naissance::where('deleted_at',null)->get();
        $courrierEntr = Courrier::whereDay('date_courrier',date('d'))->where('emmettre_recu','=','Recus')->get();
        $courrierSort = Courrier::whereDay('date_courrier',date('d'))->where('emmettre_recu','=','Emis')->get();
        $annuaires = Annuaire::where('deleted_at',null)->get();
        $naissanceFemme = Naissance::where('sexe','=','Feminin')->get();
        $naissanceHomme = Naissance::where('sexe','=','Masculin')->get();
        $mariagesAnnee = Mariage::whereYear('date_mariage', date('Y'))->get();
        $decesAnnee = Decede::whereYear('date_deces', date('Y'))->get();
        $deces = Decede::where('deleted_at',null)->get();
        $decesFemme = Decede::where('sexe','=','Feminin')->get();
        $decesHomme = Decede::where('sexe','=','Masculin')->get();
        $contrat = Contrat::where('deleted_at',null)->get();
        
        $get_configuration_infos = \App\Helpers\ConfigurationHelper\Configuration::get_configuration_infos(1);  
        
         //Calcule d'age 
        $ageFemmes = []; $ageHommes  = []; $nouveauxMajeurs = [];
        $aujourdhui = date("Y-m-d");
        $trnch1H=0; $trnch2H=0; $trnch3H=0; $trnch4H=0; $trnch5H=0; $trnch6H=0; $trnch7H=0; $trnch8H=0;
        $trnch1F=0; $trnch2F=0; $trnch3F=0; $trnch4F=0; $trnch5F=0; $trnch6F=0; $trnch7F=0; $trnch8F=0;
        
        foreach ($nassances as $naissance){ 
            if($naissance->sexe=='Feminin'){
                $diffFemme = date_diff(date_create($naissance->date_naissance_enfant), date_create($aujourdhui));
                $ageFemme = $diffFemme->format('%y');
                if($ageFemme==18){
                    $nouveauxMajeurs[$naissance->id] = $naissance;
                }
                if($ageFemme>=0 && $ageFemme<=10){
                    $trnch1F = $trnch1F + 1;
                }
                if($ageFemme>10 && $ageFemme<=20){
                    $trnch2F = $trnch2F + 1;
                }
                if($ageFemme>20 && $ageFemme<=30){
                    $trnch3F = $trnch3F + 1;
                }
                if($ageFemme>30 && $ageFemme<=40){
                    $trnch4F = $trnch4F + 1;
                }
                if($ageFemme>40 && $ageFemme<=50){
                    $trnch5F = $trnch5F + 1;
                }
                if($ageFemme>50 && $ageFemme<=60){
                    $trnch6F = $trnch6F + 1;
                }
                if($ageFemme>60 && $ageFemme<=70){
                    $trnch7F = $trnch7F +1;
                }
                if($ageFemme>70){
                    $trnch8F = $trnch8F +1;
                }
            }
            if($naissance->sexe=='Masculin'){
                $diffHomme = date_diff(date_create($naissance->date_naissance_enfant), date_create($aujourdhui));
                $ageHomme = $diffHomme->format('%y');
                if($ageHomme==21){
                    $nouveauxMajeurs[$naissance->id] = $naissance;
                }
                if($ageHomme>=0 && $ageHomme<=10){
                    $trnch1H = $trnch1H + 1;
                }
                if($ageHomme>10 && $ageHomme<=20){
                    $trnch2H = $trnch2H + 1;
                }
                if($ageHomme>20 && $ageHomme<=30){
                    $trnch3H = $trnch3H + 1;
                }
                if($ageHomme>30 && $ageHomme<=40){
                    $trnch4H = $trnch4H + 1;
                }
                if($ageHomme>40 && $ageHomme<=50){
                    $trnch5H = $trnch5H + 1;
                }
                if($ageHomme>50 && $ageHomme<=60){
                    $trnch6H = $trnch6H + 1;
                }
                if($ageHomme>60 && $ageHomme<=70){
                    $trnch7H = $trnch7H +1;
                }
                if($ageHomme>70){
                    $trnch8H = $trnch8H +1;
                }
            }
        }
        $ageHommes = [$trnch1H,$trnch2H,$trnch3H,$trnch4H,$trnch5H,$trnch6H,$trnch7H,$trnch8H];
        $ageFemmes = [$trnch1F,$trnch2F,$trnch3F,$trnch4F,$trnch5F,$trnch6F,$trnch7F,$trnch8F];
        $listeDecesByLieu = Decede::where('deleted_at',null)
                            ->select('decedes.lieu_deces', DB::raw('count(*) as total'))
                            ->groupBy('decedes.lieu_deces')
                            ->orderBy('total', 'desc')->take(5)->get();
        $listeDecesByMotif = Decede::where('deleted_at',null)
                            ->select('decedes.motif_deces', DB::raw('count(*) as total'))
                            ->groupBy('decedes.motif_deces')
                            ->orderBy('total', 'desc')->take(5)->get();
        $prochainsMariages =  Mariage::with('regime')
                            ->Where([['mariages.deleted_at', NULL],['mariages.date_mariage','>', now()]]) 
                            ->select('mariages.*')
                            ->orderBy('mariages.id', 'DESC')
                            ->take(5)->get(); 
        $listeDecesByAn = Decede::where('decedes.deleted_at',null)
                        ->select(DB::raw('count(*) as total'),DB::raw('DATE_FORMAT(decedes.date_deces, "%Y") as year'))
                        ->orderBy('year','desc')
                        ->groupBy('year') ->take(5)->get();
        $listeDecesByMois = Decede::where('decedes.deleted_at',null)
                        ->whereYear('date_deces', date('Y'))
                        ->select(DB::raw('count(*) as total'),DB::raw('DATE_FORMAT(decedes.date_deces, "%m") as month'))
                        ->groupBy('month') ->take(5)->get();
        $nataliteByAn = Naissance::where('naissances.deleted_at',null)
                        ->select(DB::raw('count(*) as total'),DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%Y") as year'))
                        ->groupBy('year')->orderBy('year','desc')->take(5)->get();
        $nataliteByMois = Naissance::where('naissances.deleted_at',null)
                          ->whereYear('naissances.date_naissance_enfant', date('Y'))
                        ->select(DB::raw('count(*) as total'),DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%m") as month'))
                        ->groupBy('month') ->take(5)->get();
        $nataliteByQrt = Naissance::where('naissances.deleted_at',null)
                        ->whereYear('naissances.date_naissance_enfant', date('Y'))
                        ->select(DB::raw('count(*) as total'),'naissances.lieu_naissance_enfant')
                        ->groupBy('lieu_naissance_enfant')->take(5)->get();
        
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
        $personneSansDemande = Naissance::where('naissances.deleted_at',null)
                                ->whereNotIn('naissances.id', $data)
                                ->select('naissances.*') 
                                ->take(5)->get(); 
        $moisFr = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
        $menuPrincipal = "Accueil";
        $titleControlleur = "Tableau de bord";
        $btnModalAjout = "FALSE";

        if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Caissier'){
        return view('home', compact('get_configuration_infos', 'moisFr','courriers', 'courrierEntr','courrierSort','annuaires', 'personneSansDemande', 'nataliteByQrt', 'nataliteByAn','nataliteByMois','listeDecesByAn','listeDecesByMois', 'nouveauxMajeurs','prochainsMariages','nouveauxMajeurs','listeDecesByLieu','listeDecesByMotif','nassancesAnnee','ageHommes','ageFemmes','nassances','naissanceFemme','naissanceHomme', 'mariagesAnnee','decesAnnee','deces','decesFemme','decesHomme', 'contrat', 'menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }
        if(Auth::user()->role == 'Operatrice' or Auth::user()->role == 'Courrier'){
        return view('home-operatrice', compact('get_configuration_infos', 'moisFr','courriers', 'courrierEntr','courrierSort','annuaires', 'personneSansDemande', 'nataliteByQrt', 'nataliteByAn','nataliteByMois','listeDecesByAn','listeDecesByMois', 'nouveauxMajeurs','prochainsMariages','nouveauxMajeurs','listeDecesByLieu','listeDecesByMotif','nassancesAnnee','ageHommes','ageFemmes','nassances','naissanceFemme','naissanceHomme', 'mariagesAnnee','decesAnnee','deces','decesFemme','decesHomme', 'contrat', 'menuPrincipal', 'titleControlleur', 'btnModalAjout'));
        }
    }
}
