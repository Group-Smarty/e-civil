<?php

namespace App\Http\Controllers\Ecivil;

use App\Helpers\ConfigurationHelper\Configuration;
use App\Http\Controllers\Controller;
use App\Models\Ecivil\CertficatCelebration;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
include_once(app_path ()."/number-to-letters/nombre_en_lettre.php");

class CertficatCelebrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $fonctions = DB::table('fonctions')->Where('deleted_at', NULL)->orderBy('libelle_fonction', 'asc')->get();
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Certificat de célébration";
       $btnModalAjout = "TRUE";
       return view('ecivil.certificat-celebration.index',compact('fonctions','btnModalAjout', 'menuPrincipal', 'titleControlleur'));
    }

    public function listeCertificatCelebration()
    {
        $certificats = CertficatCelebration::with('fonction_epouse','fonction_epoux')
                        ->where('certficat_celebrations.deleted_at', NULL) 
                        ->select('certficat_celebrations.*',DB::raw('DATE_FORMAT(certficat_celebrations.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(certficat_celebrations.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(certficat_celebrations.date_dresser, "%d-%m-%Y") as date_dressers'))
                        ->orderBy('certficat_celebrations.id', 'DESC')
                        ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatCelebrationByNom($nom)
    {
        $certificats = CertficatCelebration::with('fonction_epouse','fonction_epoux')
                        ->where([['certficat_celebrations.deleted_at', NULL],['certficat_celebrations.nom_epoux','like','%'.$nom.'%']]) 
                        ->orWhere([['certficat_celebrations.deleted_at', NULL],['certficat_celebrations.nom_epouse','like','%'.$nom.'%']]) 
                        ->select('certficat_celebrations.*',DB::raw('DATE_FORMAT(certficat_celebrations.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(certficat_celebrations.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(certficat_celebrations.date_dresser, "%d-%m-%Y") as date_dressers'))
                        ->orderBy('certficat_celebrations.id', 'DESC')
                        ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatCelebrationByDate($dates)
    {
        $date = Carbon::createFromFormat('d-m-Y', $dates);
        $certificats = CertficatCelebration::with('fonction_epouse','fonction_epoux')
                        ->where('certficat_celebrations.deleted_at', NULL) 
                        ->whereDate('certficat_celebrations.date_mariage','=',$date) 
                        ->orWhereDate('certficat_celebrations.date_demande','=',$date) 
                        ->select('certficat_celebrations.*',DB::raw('DATE_FORMAT(certficat_celebrations.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(certficat_celebrations.date_mariage, "%d-%m-%Y %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(certficat_celebrations.date_dresser, "%d-%m-%Y") as date_dressers'))
                        ->orderBy('certficat_celebrations.id', 'DESC')
                        ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
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
        if ($request->isMethod('post') && $request->input('nom_epoux') && $request->input('nom_epouse')) {
            $data = $request->all(); 
        try{
                
                //Enregistrement 
                $certficatCelebration = new CertficatCelebration;
                $certficatCelebration->numero_acte = $data['numero_acte'];
                $certficatCelebration->nom_epoux = $data['nom_epoux'];
                $certficatCelebration->nom_epouse = $data['nom_epouse'];
                $certficatCelebration->fonction_epouse = isset($data['fonction_epouse']) && !empty($data['fonction_epouse']) ? $data['fonction_epouse'] : null;
                $certficatCelebration->fonction_epoux = isset($data['fonction_epoux']) && !empty($data['fonction_epoux']) ? $data['fonction_epoux'] : null;
                $certficatCelebration->date_dresser = Carbon::createFromFormat('d-m-Y', $data['date_dresser']);
                $certficatCelebration->date_mariage = Carbon::createFromFormat('d-m-Y H:i', $data['date_mariage']);
                $certficatCelebration->date_demande = now();
                $certficatCelebration->created_by = Auth::user()->id;
                $certficatCelebration->save();
                
                $jsonData["data"] = json_decode($certficatCelebration);
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
     * @param  \App\CertficatCelebration  $certficatCelebration
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $certficatCelebration = CertficatCelebration::find($id);
         $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if($certficatCelebration){
            try{
                $data = $request->all(); 
                $certficatCelebration->numero_acte = $data['numero_acte'];
                $certficatCelebration->nom_epoux = $data['nom_epoux'];
                $certficatCelebration->nom_epouse = $data['nom_epouse'];
                $certficatCelebration->fonction_epouse = isset($data['fonction_epouse']) && !empty($data['fonction_epouse']) ? $data['fonction_epouse'] : null;
                $certficatCelebration->fonction_epoux = isset($data['fonction_epoux']) && !empty($data['fonction_epoux']) ? $data['fonction_epoux'] : null;
                $certficatCelebration->date_dresser = Carbon::createFromFormat('d-m-Y', $data['date_dresser']);
                $certficatCelebration->date_mariage = Carbon::createFromFormat('d-m-Y H:i', $data['date_mariage']);
                $certficatCelebration->updated_by = Auth::user()->id;
                $certficatCelebration->save();
                $jsonData["data"] = json_decode($certficatCelebration);
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
     * @param  \App\CertficatCelebration  $certficatCelebration
     * @return Response
     */
    public function destroy(CertficatCelebration $certficatCelebration)
    {
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($certficatCelebration){
                try {
                    $certficatCelebration->update(['deleted_by' => Auth::user()->id]);
                    $certficatCelebration->delete();
                    $jsonData["data"] = json_decode($certficatCelebration);
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
        $get_configuration_infos = Configuration::get_configuration_infos(1);
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
    
    //Etat
    public function ficheCertificatCelebrationPdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheCertificatCelebration($id));
        return $pdf->stream('fiche_certificat_celebration'.$id.'_'.date('d/m/Y').'.pdf');
    }

    public function ficheCertificatCelebration($id){
        $outPut = $this->header();
        $outPut.= $this->content($id);
        return $outPut;
    }
    
     public function header(){
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);

       $header = "<html>
        <head>
            <meta charset='utf-8'>
            <style>
               
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
                    margin:260px 0;
                    position: absolute;
                    font-size : 18px;
                }
              
            </style>
            <body>
                <div class='fixed-header-left'>
                    COMMUNE ".strtoupper($this->premierLetre()."".$commune)."<hr width='50'/></b>
                    <img src=".$this->infosConfig()->logo." width='150' height='150'><br/> 
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
                    $header .= "" . $this->infosConfig()->site_web_mairie . "<br/> ";
                } 
                $header .="</div>
                        <div class='fixed-header-right'>
                           <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                            Union-Discipline-Travail<hr width='50'/></b>
                        </div>";
        return $header;
    }
    
    public function content($id){
        $infos = CertficatCelebration::where([['certficat_celebrations.deleted_at', NULL],['certficat_celebrations.id',$id]]) 
                        ->leftJoin('fonctions as fonctionEpouse','fonctionEpouse.id','=','certficat_celebrations.fonction_epouse') 
                        ->leftJoin('fonctions as fonctionEpoux','fonctionEpoux.id','=','certficat_celebrations.fonction_epoux') 
                        ->select('certficat_celebrations.*','fonctionEpoux.libelle_fonction as libelle_fonction_epoux','fonctionEpouse.libelle_fonction as libelle_fonction_epouse',DB::raw('DATE_FORMAT(certficat_celebrations.date_dresser, "%d/%m/%Y") as date_dressers'))
                        ->first();
        
        $month = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
       
        $dateMariage = $infos->date_mariage;
        
        $infos->libelle_fonction_epoux!=null ? $libelle_fonction_epoux = $infos->libelle_fonction_epoux : $libelle_fonction_epoux =".....................................................................................................................";
        $infos->libelle_fonction_epouse!=null ? $libelle_fonction_epouse = $infos->libelle_fonction_epouse : $libelle_fonction_epouse =".....................................................................................................................";
       
        
        $dayMariage = date('d', strtotime($dateMariage));
        $montMariage  = date('m', strtotime($dateMariage));
        $anMariage  = date('Y', strtotime($dateMariage));
        
        $infos->civilite == "Mlle" ? $civilite ="Mademoiselle" : $civilite="Monsieur";
        $infos->civilite == "Mlle" ? $ne ="née" : $ne="né";
        
        $dayMariage == 01 ? $jourMariage = 'premier' : $jourMariage = NumberToLetter(number_format($dayMariage));
        
        $content = "<div class='fixed-content'>
                       <p style='text-align:center; font-size:25px;'><b><u>CERTIFICAT DE CELEBRATION</u></b></p><br/>
                       <p><b>N° ".$infos->numero_acte." DU ".$infos->date_dressers."</b></p>
                       <p>Le mariage de <b>".$infos->nom_epoux."</b>".$infos->concerne."</b></p>
                       <p>Profession <b>".$libelle_fonction_epoux."</b></p>
                       <p>Et de <b>".$infos->nom_epouse."</b></p>
                       <p>Profession <b>".$libelle_fonction_epouse."</b></p>
                       <p>A été célébré en notre Mairie aujourd'hui <b>".$jourMariage." ".$month[$montMariage]." ".NumberToLetter($anMariage)."</b> à <b>".date('H', strtotime($dateMariage))." Heure(s)</b></p><br/><br/>
                       <p>Option du régime</p>
                       <p style='float:right;bottom:0;'>".$this->infosConfig()->commune.", le ".date("d/m/Y")."<br/><br/> L'Officier de l'Etat-Civil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                   </div>";
        return $content;
    }
}
