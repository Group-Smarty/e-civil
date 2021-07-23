<?php

namespace App\Http\Controllers\Etat;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\Decede;
use App\Models\Ecivil\Mariage;
use App\Models\Ecivil\Naissance;
use App\Models\Parametre\Regime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EtatController extends Controller
{

    public function vueEtatNaissance()
    {
       $menuPrincipal = "Etat";
       $titleControlleur = "Liste des naissances";
       $btnModalAjout = "FALSE";
       return view('etat.naissance',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }
    
    public function vueEtatMariage()
    {
       $regimes = DB::table('regimes')->Where('deleted_at', NULL)->orderBy('libelle_regime', 'asc')->get();
       $menuPrincipal = "Etat";
       $titleControlleur = "Liste des mariages";
       $btnModalAjout = "FALSE";
       return view('etat.mariage',compact('regimes','btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }
    
    public function vueEtatDeces()
    {
       $menuPrincipal = "Etat";
       $titleControlleur = "Liste des décès";
       $btnModalAjout = "FALSE";
       return view('etat.deces',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }
    
    
    
    //Naissances
    public function listeNaissance(){
        $naissances = Naissance::where('naissances.deleted_at', NULL)
                    ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('naissances.id', 'DESC')
                    ->get();
       $jsonData["rows"] = $naissances->toArray();
       $jsonData["total"] = $naissances->count();
       return response()->json($jsonData);
    }
    public function listeNaissanceByPeriode($debut,$fin){
        $dateDebut = Carbon::createFromFormat('d-m-Y', $debut);
        $dateFin = Carbon::createFromFormat('d-m-Y', $fin);
        $naissances = Naissance::where('naissances.deleted_at', NULL)
                    ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->whereDate('naissances.date_naissance_enfant','>=', $dateDebut)
                    ->whereDate('naissances.date_naissance_enfant','<=', $dateFin)
                    ->orderBy('naissances.id', 'DESC')
                    ->get();
       $jsonData["rows"] = $naissances->toArray();
       $jsonData["total"] = $naissances->count();
       return response()->json($jsonData);
    }
    public function listeNaissanceBySexe($sexe){
        $naissances = Naissance::where([['naissances.deleted_at', NULL],['naissances.sexe',$sexe]])
                    ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('naissances.id', 'DESC')
                    ->get();
       $jsonData["rows"] = $naissances->toArray();
       $jsonData["total"] = $naissances->count();
       return response()->json($jsonData);
    }
    public function listeNaissanceByPeriodeSexe($debut,$fin,$sexe){
        $dateDebut = Carbon::createFromFormat('d-m-Y', $debut);
        $dateFin = Carbon::createFromFormat('d-m-Y', $fin);
        $naissances = Naissance::where([['naissances.deleted_at', NULL],['naissances.sexe',$sexe]])
                    ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->whereDate('naissances.date_naissance_enfant','>=', $dateDebut)
                    ->whereDate('naissances.date_naissance_enfant','<=', $dateFin)
                    ->orderBy('naissances.id', 'DESC')
                    ->get();
       $jsonData["rows"] = $naissances->toArray();
       $jsonData["total"] = $naissances->count();
       return response()->json($jsonData);
    }
    
    //Mariages
    public function listeMariage(){
        $mariages = Mariage::where('mariages.deleted_at', NULL)
                            ->join('regimes', 'regimes.id','=','mariages.regime_id')
                            ->select('mariages.*','regimes.libelle_regime',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                            ->orderBy('mariages.id', 'DESC')
                            ->get();
       $jsonData["rows"] = $mariages->toArray();
       $jsonData["total"] = $mariages->count();
       return response()->json($jsonData);
    }
    public function listeMariageByPeriode($debut,$fin){
        $dateDebut = Carbon::createFromFormat('d-m-Y', $debut);
        $dateFin = Carbon::createFromFormat('d-m-Y', $fin);
        $mariages = Mariage::where('mariages.deleted_at', NULL)
                    ->join('regimes', 'regimes.id','=','mariages.regime_id')
                    ->select('mariages.*','regimes.libelle_regime',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->whereDate('mariages.date_mariage','>=', $dateDebut)
                    ->whereDate('mariages.date_mariage','<=', $dateFin)
                    ->orderBy('mariages.id', 'DESC')
                    ->get();
        $jsonData["rows"] = $mariages->toArray();
        $jsonData["total"] = $mariages->count();
        return response()->json($jsonData);
    }
    public function listeMariageByRegime($regime){
        $mariages = Mariage::where([['mariages.deleted_at', NULL],['mariages.regime_id',$regime]])
                    ->join('regimes', 'regimes.id','=','mariages.regime_id')
                    ->select('mariages.*','regimes.libelle_regime',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('mariages.id', 'DESC')
                    ->get();
       $jsonData["rows"] = $mariages->toArray();
       $jsonData["total"] = $mariages->count();
       return response()->json($jsonData);
    }
    public function listeMariageByRegimePeriode($debut,$fin,$regime){
        $dateDebut = Carbon::createFromFormat('d-m-Y', $debut);
        $dateFin = Carbon::createFromFormat('d-m-Y', $fin);
        $mariages = Mariage::where([['mariages.deleted_at', NULL],['mariages.regime_id',$regime]])
                    ->join('regimes', 'regimes.id','=','mariages.regime_id')
                    ->select('mariages.*','regimes.libelle_regime',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->whereDate('mariages.date_mariage','>=', $dateDebut)
                    ->whereDate('mariages.date_mariage','<=', $dateFin)
                    ->orderBy('mariages.id', 'DESC')
                    ->get();
        $jsonData["rows"] = $mariages->toArray();
        $jsonData["total"] = $mariages->count();
        return response()->json($jsonData);
    }

    //Décès
    public function listeDeces(){
        $deces = Decede::where('decedes.deleted_at', NULL) 
                ->select('decedes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                ->orderBy('decedes.id', 'DESC')
                ->get();
       $jsonData["rows"] = $deces->toArray();
       $jsonData["total"] = $deces->count();
       return response()->json($jsonData);
    }
    public function listeDecesBySexe($sexe){
        $deces = Decede::where([['decedes.deleted_at', NULL],['decedes.sexe',$sexe]]) 
                ->select('decedes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                ->orderBy('decedes.id', 'DESC')
                ->get();
       $jsonData["rows"] = $deces->toArray();
       $jsonData["total"] = $deces->count();
       return response()->json($jsonData);
    }
    public function listeDecesByPeriode($debut,$fin){
        $dateDebut = Carbon::createFromFormat('d-m-Y', $debut);
        $dateFin = Carbon::createFromFormat('d-m-Y', $fin);
        $deces = Decede::where('decedes.deleted_at', NULL) 
                ->select('decedes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                ->whereDate('decedes.date_deces','>=', $dateDebut)
                ->whereDate('decedes.date_deces','<=', $dateFin)
                ->orderBy('decedes.id', 'DESC')
                ->get();
       $jsonData["rows"] = $deces->toArray();
       $jsonData["total"] = $deces->count();
       return response()->json($jsonData);
    }
    public function listeDecesByPeriodeSexe($debut,$fin,$sexe){
        $dateDebut = Carbon::createFromFormat('d-m-Y', $debut);
        $dateFin = Carbon::createFromFormat('d-m-Y', $fin);
        $deces = Decede::where([['decedes.deleted_at', NULL],['decedes.sexe',$sexe]]) 
                ->select('decedes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                ->whereDate('decedes.date_deces','>=', $dateDebut)
                ->whereDate('decedes.date_deces','<=', $dateFin)
                ->orderBy('decedes.id', 'DESC')
                ->get();
       $jsonData["rows"] = $deces->toArray();
       $jsonData["total"] = $deces->count();
       return response()->json($jsonData);
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
    
    //**Etat Naissance**// 
        //Liste des naissances
    public function listeNaissancePdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeNaissances());
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('liste_naissances.pdf');
    }
    public function listeNaissances(){
        $datas = Naissance::where('naissances.deleted_at', NULL)
                    ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('naissances.id', 'DESC')
                    ->get();
        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des naissances </u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='35%' align='center'>N° de l'acte </th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Nom complet</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Date de naissance</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Lieu de naissance</th>
                                <th cellspacing='0' border='2' width='15%' align='center'>Sexe</th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Père</th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Mère</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $data->heure_naissance_enfant != null ? $heur = " à ".substr($data->heure_naissance_enfant, 0, -3) : $heur = "";
            $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_acte_naissance.' DU '.$data->date_dressers.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_enfant.' '.$data->prenom_enfant.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_naissance.$heur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->lieu_naissance_enfant.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->sexe.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_pere.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_mere.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' naissance(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Naissanc par période
    public function listeNaissanceByPeriodePdf($debut,$fin){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeNaissanceByPeriodes($debut,$fin));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('liste_naissances_du_'.$debut.'_au_'.$fin.'_.pdf');
    }
    public function listeNaissanceByPeriodes($debut,$fin){
        $dateDebut = Carbon::createFromFormat('d-m-Y', $debut);
        $dateFin = Carbon::createFromFormat('d-m-Y', $fin);
        $datas = Naissance::where('naissances.deleted_at', NULL)
                    ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->whereDate('naissances.date_naissance_enfant','>=', $dateDebut)
                    ->whereDate('naissances.date_naissance_enfant','<=', $dateFin)
                    ->orderBy('naissances.id', 'DESC')
                    ->get();
        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des naissances du ".$debut." au ".$fin."</u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='35%' align='center'>N° de l'acte </th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Nom complet</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Date de naissance</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Lieu de naissance</th>
                                <th cellspacing='0' border='2' width='15%' align='center'>Sexe</th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Père</th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Mère</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $data->heure_naissance_enfant != null ? $heur = " à ".substr($data->heure_naissance_enfant, 0, -3) : $heur = "";

            $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_acte_naissance.' DU '.$data->date_dressers.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_enfant.' '.$data->prenom_enfant.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_naissance.$heur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->lieu_naissance_enfant.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->sexe.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_pere.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_mere.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' naissance(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Naissanc par sexe
    public function listeNaissanceBySexePdf($sexe){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeNaissanceBySexes($sexe));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('liste_naissances_personne_sexe_'.$sexe.'_.pdf');
    }
    public function listeNaissanceBySexes($sexe){
        $datas = Naissance::where([['naissances.deleted_at', NULL],['naissances.sexe',$sexe]])
                    ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('naissances.id', 'DESC')
                    ->get();
        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des naissances des personnes de sexe ".$sexe."</u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='35%' align='center'>N° de l'acte </th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Nom complet</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Date de naissance</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Lieu de naissance</th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Père</th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Mère</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $data->heure_naissance_enfant != null ? $heur = " à ".substr($data->heure_naissance_enfant, 0, -3) : $heur = "";
            $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_acte_naissance.' DU '.$data->date_dressers.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_enfant.' '.$data->prenom_enfant.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_naissance.$heur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->lieu_naissance_enfant.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_pere.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_mere.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' naissance(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Naissanc par sexe sur une période
    public function listeNaissanceBySexePeriodePdf($debut,$fin,$sexe){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeNaissanceBySexePeriodes($debut,$fin,$sexe));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('liste_naissances_personne_du_sexe_'.$sexe.'_du_'.$debut.'_au_'.$fin.'.pdf');
    }
    public function listeNaissanceBySexePeriodes($debut,$fin,$sexe){
        $dateDebut = Carbon::createFromFormat('d-m-Y', $debut);
        $dateFin = Carbon::createFromFormat('d-m-Y', $fin);
        $datas = Naissance::where([['naissances.deleted_at', NULL],['naissances.sexe',$sexe]])
                    ->select('naissances.*',DB::raw('DATE_FORMAT(naissances.date_naissance_enfant, "%d-%m-%Y") as date_naissance'),DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->whereDate('naissances.date_naissance_enfant','>=', $dateDebut)
                    ->whereDate('naissances.date_naissance_enfant','<=', $dateFin)
                    ->orderBy('naissances.id', 'DESC')
                    ->get();
        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des naissances des personnes de sexe ".$sexe." du ".$debut." au ".$fin."</u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='35%' align='center'>N° de l'acte </th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Nom complet</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Date de naissance</th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Lieu de naissance</th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Père</th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Mère</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $data->heure_naissance_enfant != null ? $heur = " à ".substr($data->heure_naissance_enfant, 0, -3) : $heur = "";

            $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_acte_naissance.' DU '.$data->date_dressers.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_enfant.' '.$data->prenom_enfant.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_naissance.$heur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->lieu_naissance_enfant.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_pere.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_mere.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' naissance(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
     //**Etat Mariage**// 
        //Liste des mariages
    public function listeMariagePdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHTML($this->listeMariages());
        return $pdf->stream('liste_mariages.pdf');
    }
    public function listeMariages(){
        $datas = Mariage::where('mariages.deleted_at', NULL)
                    ->join('regimes', 'regimes.id','=','mariages.regime_id')
                    ->select('mariages.*','regimes.libelle_regime',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y à %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('mariages.id', 'DESC')
                    ->get();
        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des mariages </u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='45%' align='center'>N° de l'acte </th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Date du mariage</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Epoux</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Epouse</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Régime</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_acte_mariage.' DU '.$data->date_dressers.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_mariages.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_homme.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_femme.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_regime.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' mariages(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
        //Liste des mariages sur une période
    public function listeMariageByPeriodePdf($debut,$fin){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHTML($this->listeMariageByPeriodes($debut,$fin));
        return $pdf->stream('liste_mariages_du_'.$debut.'_au_'.$fin.'.pdf');
    }
    public function listeMariageByPeriodes($debut,$fin){
        $dateDebut = Carbon::createFromFormat('d-m-Y', $debut);
        $dateFin = Carbon::createFromFormat('d-m-Y', $fin);
        $datas = Mariage::where('mariages.deleted_at', NULL)
                    ->join('regimes', 'regimes.id','=','mariages.regime_id')
                    ->select('mariages.*','regimes.libelle_regime',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y à %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->whereDate('mariages.date_mariage','>=', $dateDebut)
                    ->whereDate('mariages.date_mariage','<=', $dateFin)
                    ->orderBy('mariages.id', 'DESC')
                    ->get();
        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des mariages du ".$debut." au ".$fin."</u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='45%' align='center'>N° de l'acte </th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Date du mariage</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Epoux</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Epouse</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Régime</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $outPut .= '
                          <tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_acte_mariage.' DU '.$data->date_dressers.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_mariages.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_homme.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_femme.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_regime.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' mariages(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }

     //Liste des mariages pour un régime
    public function listeMariageByRegimePdf($regime){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeMariageByRegimes($regime));
        return $pdf->stream('liste_mariages_by_regime.pdf');
    }
    public function listeMariageByRegimes($regime){
        $infoRegime = Regime::find($regime);
        $datas = Mariage::where([['mariages.deleted_at', NULL],['mariages.regime_id',$regime]])
                    ->join('regimes', 'regimes.id','=','mariages.regime_id')
                    ->select('mariages.*','regimes.libelle_regime',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y à %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->orderBy('mariages.id', 'DESC')
                    ->get();
        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des mariages avec pour régime ".$infoRegime->libelle_regime."</u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='45%' align='center'>N° de l'acte </th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Date du mariage</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Epoux</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Epouse</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_acte_mariage.' DU '.$data->date_dressers.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_mariages.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_homme.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_femme.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' mariages(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Liste des mariages pour un régime sur une période
    public function listeMariageByRegimePeriodePdf($debut,$fin,$regime){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeMariageByRegimePeriodes($debut,$fin,$regime));
        return $pdf->stream('liste_mariages_by_regime.pdf');
    }
    public function listeMariageByRegimePeriodes($debut,$fin,$regime){
        $infoRegime = Regime::find($regime);
        $dateDebut = Carbon::createFromFormat('d-m-Y', $debut);
        $dateFin = Carbon::createFromFormat('d-m-Y', $fin);
        $datas = Mariage::where([['mariages.deleted_at', NULL],['mariages.regime_id',$regime]])
                    ->join('regimes', 'regimes.id','=','mariages.regime_id')
                    ->select('mariages.*','regimes.libelle_regime',DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y à %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                    ->whereDate('mariages.date_mariage','>=', $dateDebut)
                    ->whereDate('mariages.date_mariage','<=', $dateFin)
                    ->orderBy('mariages.id', 'DESC')
                    ->get();
        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des mariages avec pour régime ".$infoRegime->libelle_regime." sur la période du ".$debut." au ".$fin."</u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='45%' align='center'>N° de l'acte </th>
                                <th cellspacing='0' border='2' width='35%' align='center'>Date du mariage</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Epoux</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Epouse</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $data->naissance_id_homme !=null ? $epoux = $data->nomHomme.' '.$data->prenomHomme : $epoux = $data->nom_complet_homme;
            $data->naissance_id_femme !=null ? $epouse = $data->nomFemme.' '.$data->prenomFemme : $epouse = $data->nom_complet_femme;
            $outPut .= '
                         <tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_acte_mariage.' DU '.$data->date_dressers.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_mariages.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_homme.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_femme.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' mariages(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //**Etat Décès**// 
        //Liste des décès
    public function listeDecesPdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeDecess());
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('liste_deces.pdf');
    }
    public function listeDecess(){
        $datas = Decede::where('decedes.deleted_at', NULL) 
                ->select('decedes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                ->orderBy('decedes.id', 'DESC')
                ->get();
        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des décès </u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='30%' align='center'>N° de l'acte </th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Date du décès</th>
                                <th cellspacing='0' border='2' width='45%' align='center'>Nom complet du défunt</th>
                                <th cellspacing='0' border='2' width='15%' align='center'>Sexe</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Lieu du décès</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Motif du décès</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $data->heure_deces != null ? $heure = " à ".substr($data->heure_deces, 0, -3) : $heure = "";
            $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_acte_deces.' DU '.$data->date_dressers.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_decess.$heure.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_decede.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->sexe.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->lieu_deces.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->motif_deces.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' décès(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Liste des décès par sexe
    public function listeDecesBySexePdf($sexe){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeDecesBySexes($sexe));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('liste_deces_sexe_'.$sexe.'.pdf');
    }
    public function listeDecesBySexes($sexe){
        $datas = Decede::where([['decedes.deleted_at', NULL],['decedes.sexe',$sexe]]) 
                ->select('decedes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                ->orderBy('decedes.id', 'DESC')
                ->get();
        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des décès des personnes du sexe ".$sexe."</u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='30%' align='center'>N° de l'acte </th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Date du décès</th>
                                <th cellspacing='0' border='2' width='50%' align='center'>Nom complet du défunt</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Lieu du décès</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Motif du décès</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $data->heure_deces != null ? $heure = " à ".substr($data->heure_deces, 0, -3) : $heure = "";
            $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_acte_deces.' DU '.$data->date_dressers.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_decess.$heure.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_decede.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->lieu_deces.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->motif_deces.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' décès(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
     //Liste des décès par période
    public function listeDecesByPeriodePdf($debut,$fin){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeDecesByPeriodes($debut,$fin));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('liste_deces_du_'.$debut.'_au_'.$fin.'.pdf');
    }
    public function listeDecesByPeriodes($debut,$fin){
        $dateDebut = Carbon::createFromFormat('d-m-Y', $debut);
        $dateFin = Carbon::createFromFormat('d-m-Y', $fin);
        $datas = Decede::where('decedes.deleted_at', NULL) 
                ->select('decedes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                ->whereDate('decedes.date_deces','>=', $dateDebut)
                ->whereDate('decedes.date_deces','<=', $dateFin)
                ->orderBy('decedes.id', 'DESC')
                ->get();
        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des décès du ".$debut." au ".$fin."</u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='30%' align='center'>N° de l'acte </th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Date du décès</th>
                                <th cellspacing='0' border='2' width='45%' align='center'>Nom complet du défunt</th>
                                <th cellspacing='0' border='2' width='15%' align='center'>Sexe</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Lieu du décès</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Motif du décès</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
            $data->heure_deces != null ? $heure = " à ".substr($data->heure_deces, 0, -3) : $heure = "";
            $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_acte_deces.' DU '.$data->date_dressers.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_decess.$heure.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_decede.'</td>
                            <td  cellspacing="0" border="2" align="center">'.$data->sexe.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->lieu_deces.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->motif_deces.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' décès(s)</b>';
        $outPut.= $this->footerFiche();
        return $outPut;
    }
    
    //Liste des décès par sexe sur une période
    public function listeDecesBySexePeriodePdf($debut,$fin,$sexe){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->listeDecesBySexePeriodes($debut,$fin,$sexe));
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream('liste_deces_du_sexe_'.$sexe.'_du_'.$debut.'_au_'.$fin.'.pdf');
    }
    public function listeDecesBySexePeriodes($debut,$fin,$sexe){
        $dateDebut = Carbon::createFromFormat('d-m-Y', $debut);
        $dateFin = Carbon::createFromFormat('d-m-Y', $fin);
        $datas = Decede::where([['decedes.deleted_at', NULL],['decedes.sexe',$sexe]]) 
                ->select('decedes.*',DB::raw('DATE_FORMAT(decedes.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(decedes.date_dresser, "%d-%m-%Y") as date_dressers'))
                ->whereDate('decedes.date_deces','>=', $dateDebut)
                ->whereDate('decedes.date_deces','<=', $dateFin)
                ->orderBy('decedes.id', 'DESC')
                ->get();
        $outPut = $this->headerFiche();
        $outPut .= "<div class='container-table'>
                        <h3 align='center'><u> Liste des décès des personnes du sexe ".$sexe." sur la période du ".$debut." au ".$fin."</u></h3>
                        <table border='2' cellspacing='0' width='100%'>
                            <tr>
                                <th cellspacing='0' border='2' width='30%' align='center'>N° de l'acte </th>
                                <th cellspacing='0' border='2' width='30%' align='center'>Date du décès</th>
                                <th cellspacing='0' border='2' width='50%' align='center'>Nom complet du défunt</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Lieu du décès</th>
                                <th cellspacing='0' border='2' width='40%' align='center'>Motif du décès</th>
                            </tr>
                        ";
        $total = 0;
        foreach ($datas as $data){
            $total = $total + 1;
             $data->heure_deces != null ? $heure = " à ".substr($data->heure_deces, 0, -3) : $heure = "";
            $outPut .= '
                        <tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_acte_deces.' DU '.$data->date_dressers.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_decess.$heure.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nom_complet_decede.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->lieu_deces.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->motif_deces.'</td>
                        </tr>
                       ';
       }
       
        $outPut .='</table></div>';
        $outPut.='Nombre totale:<b> '.number_format($total, 0, ',', ' ').' décès(s)</b>';
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
