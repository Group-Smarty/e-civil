<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\CertificatCelibat;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
include_once(app_path ()."/number-to-letters/nombre_en_lettre.php");

class CertificatCelibatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Certificat de célibat";
       $btnModalAjout = "TRUE";
       return view('ecivil.certificat-celibat.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur'));
    }

    public function listeCertificatCelibat()
    {
        $certificats = CertificatCelibat::where('certificat_celibats.deleted_at', NULL) 
                        ->select('certificat_celibats.*',DB::raw('DATE_FORMAT(certificat_celibats.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(certificat_celibats.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(certificat_celibats.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(certificat_celibats.date_requette, "%d-%m-%Y") as date_requettes'),DB::raw('DATE_FORMAT(certificat_celibats.date_mariage, "%d-%m-%Y") as date_mariages'))
                        ->orderBy('certificat_celibats.id', 'DESC')
                        ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatCelibatByNom($nom)
    {
        $certificats = CertificatCelibat::where([['certificat_celibats.deleted_at', NULL],['certificat_celibats.concerne','like','%'.$nom.'%']]) 
                        ->select('certificat_celibats.*',DB::raw('DATE_FORMAT(certificat_celibats.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(certificat_celibats.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(certificat_celibats.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(certificat_celibats.date_requette, "%d-%m-%Y") as date_requettes'),DB::raw('DATE_FORMAT(certificat_celibats.date_mariage, "%d-%m-%Y") as date_mariages'))
                        ->orderBy('certificat_celibats.id', 'DESC')
                        ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatCelibatByDate($dates)
    {
        $date = Carbon::createFromFormat('d-m-Y', $dates);
        $certificats = CertificatCelibat::where('certificat_celibats.deleted_at', NULL) 
                        ->whereDate('certificat_celibats.date_demande','=',$date) 
                        ->orWhereDate('certificat_celibats.date_naissance','=',$date) 
                        ->select('certificat_celibats.*',DB::raw('DATE_FORMAT(certificat_celibats.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(certificat_celibats.date_demande, "%d-%m-%Y") as date_demandes'),DB::raw('DATE_FORMAT(certificat_celibats.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(certificat_celibats.date_requette, "%d-%m-%Y") as date_requettes'),DB::raw('DATE_FORMAT(certificat_celibats.date_mariage, "%d-%m-%Y") as date_mariages'))
                        ->orderBy('certificat_celibats.id', 'DESC')
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
        if ($request->isMethod('post') && $request->input('concerne')) {
            $data = $request->all(); 
        try{
                
                //Enregistrement 
                $certificatCelibat = new CertificatCelibat;
                $certificatCelibat->civilite = $data['civilite'];
                $certificatCelibat->concerne = $data['concerne'];
                $certificatCelibat->type = $data['type'];
                $certificatCelibat->lieu_naissance = $data['lieu_naissance'];
                $certificatCelibat->numero_act_naissance = $data['numero_act_naissance'];
                $certificatCelibat->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $certificatCelibat->date_dresser = Carbon::createFromFormat('d-m-Y', $data['date_dresser']);
                $certificatCelibat->nom_pere = isset($data['nom_pere']) && !empty($data['nom_pere']) ? $data['nom_pere'] : null;
                $certificatCelibat->nom_mere = isset($data['nom_mere']) && !empty($data['nom_mere']) ? $data['nom_mere'] : null;
                $certificatCelibat->lieu_mariage = isset($data['lieu_mariage']) && !empty($data['lieu_mariage']) ? $data['lieu_mariage'] : null;
                $certificatCelibat->conjoint = isset($data['conjoint']) && !empty($data['conjoint']) ? $data['conjoint'] : null;
                $certificatCelibat->tribunal = isset($data['tribunal']) && !empty($data['tribunal']) ? $data['tribunal'] : null;
                $certificatCelibat->raison_disolution_mariage = isset($data['raison_disolution_mariage']) && !empty($data['raison_disolution_mariage']) ? $data['raison_disolution_mariage'] : null;
                $certificatCelibat->numero_requette = isset($data['numero_requette']) && !empty($data['numero_requette']) ? $data['numero_requette'] : null;
                $certificatCelibat->date_requette = isset($data['date_requette']) && !empty($data['date_requette']) ? Carbon::createFromFormat('d-m-Y', $data['date_requette']) : null;
                $certificatCelibat->date_mariage = isset($data['date_mariage']) && !empty($data['date_mariage']) ? Carbon::createFromFormat('d-m-Y', $data['date_mariage']) : null;
                $certificatCelibat->date_demande = now();
                $certificatCelibat->created_by = Auth::user()->id;
                $certificatCelibat->save();
                
                $jsonData["data"] = json_decode($certificatCelibat);
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
     * @param  \App\CertificatCelibat  $certificatCelibat
     * @return Response
     */
    public function update(Request $request, CertificatCelibat $certificatCelibat)
    {
         $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if($certificatCelibat){
            try{
                $data = $request->all(); 
                $certificatCelibat->civilite = $data['civilite'];
                $certificatCelibat->concerne = $data['concerne'];
                $certificatCelibat->type = $data['type'];
                $certificatCelibat->lieu_naissance = $data['lieu_naissance'];
                $certificatCelibat->numero_act_naissance = $data['numero_act_naissance'];
                $certificatCelibat->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $certificatCelibat->date_dresser = Carbon::createFromFormat('d-m-Y', $data['date_dresser']);
                $certificatCelibat->nom_pere = isset($data['nom_pere']) && !empty($data['nom_pere']) ? $data['nom_pere'] : null;
                $certificatCelibat->nom_mere = isset($data['nom_mere']) && !empty($data['nom_mere']) ? $data['nom_mere'] : null;
                $certificatCelibat->lieu_mariage = isset($data['lieu_mariage']) && !empty($data['lieu_mariage']) ? $data['lieu_mariage'] : null;
                $certificatCelibat->conjoint = isset($data['conjoint']) && !empty($data['conjoint']) ? $data['conjoint'] : null;
                $certificatCelibat->tribunal = isset($data['tribunal']) && !empty($data['tribunal']) ? $data['tribunal'] : null;
                $certificatCelibat->raison_disolution_mariage = isset($data['raison_disolution_mariage']) && !empty($data['raison_disolution_mariage']) ? $data['raison_disolution_mariage'] : null;
                $certificatCelibat->numero_requette = isset($data['numero_requette']) && !empty($data['numero_requette']) ? $data['numero_requette'] : null;
                $certificatCelibat->date_requette = isset($data['date_requette']) && !empty($data['date_requette']) ? Carbon::createFromFormat('d-m-Y', $data['date_requette']) : null;
                $certificatCelibat->date_mariage = isset($data['date_mariage']) && !empty($data['date_mariage']) ? Carbon::createFromFormat('d-m-Y', $data['date_mariage']) : null;
                $certificatCelibat->updated_by = Auth::user()->id;
                $certificatCelibat->save();
                $jsonData["data"] = json_decode($certificatCelibat);
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
     * @param  \App\CertificatCelibat  $certificatCelibat
     * @return Response
     */
    public function destroy(CertificatCelibat $certificatCelibat)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($certificatCelibat){
                try {
                    $certificatCelibat->update(['deleted_by' => Auth::user()->id]);
                    $certificatCelibat->delete();
                    $jsonData["data"] = json_decode($certificatCelibat);
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
    
    //Etat
    public function ficheCertificatCelibatPdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheCertificatCelibat($id));
        return $pdf->stream('fiche_celibat'.$id.'_'.date('d/m/Y').'.pdf');
    }

    public function ficheCertificatCelibat($id){
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
                    line-height:1.8;
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
        $infos = CertificatCelibat::where([['certificat_celibats.deleted_at', NULL],['certificat_celibats.id',$id]]) 
                        ->select('certificat_celibats.*',DB::raw('DATE_FORMAT(certificat_celibats.date_mariage, "%d/%m/%Y") as date_mariages'),DB::raw('DATE_FORMAT(certificat_celibats.date_requette, "%d/%m/%Y") as date_requettes'))
                        ->orderBy('certificat_celibats.id', 'DESC')
                        ->first();
        
        $month = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
       
        $dateNaissance = $infos->date_naissance;
        
        $infos->nom_pere!=null ? $nom_pere = $infos->nom_pere : $nom_pere =".......................................";
        $infos->nom_mere!=null ? $nom_mere = $infos->nom_mere : $nom_mere =".......................................";
        
        if($infos->numero_requette!=null){
            $numero_requette = $infos->numero_requette;
        }else{
            $numero_requette = "..........................................";
        }
        
        if($infos->date_requettes!=null){
            $date_requette = $infos->date_requettes;
        }else{
            $date_requette = "..........................................";
        }
        
        if($infos->date_mariages!=null){
            $date_mariage = $infos->date_mariages;
        }else{
            $date_mariage = "..........................................";
        }
        
        if($infos->raison_disolution_mariage!=null){
            $raison_disolution_mariage = $infos->raison_disolution_mariage;
        }else{
            $raison_disolution_mariage = "..........................................";
        }
        
        if($infos->tribunal!=null){
            $tribunal = $infos->tribunal;
        }else{
            $tribunal = "..........................................";
        }
       
        $dayNaissance = date('d', strtotime($dateNaissance));
        $montNaissance = date('m', strtotime($dateNaissance));
        $anNaissance = date('Y', strtotime($dateNaissance));
        
        if($infos->civilite == "Mlle"){
            $civilite = "Mademoiselle";
            $fils_fille ="fille";
            $ne ="née";
        } 
        if($infos->civilite == "Mme"){
            $civilite = "Madame";
            $fils_fille ="fille";
            $ne ="née";
        }
        if($infos->civilite == "M"){
            $civilite = "Monsieur";
            $fils_fille ="fils";
            $ne="né";
        }
                
        $dayNaissance == 01 ? $jourNaissance = 'premier' : $jourNaissance = NumberToLetter(number_format($dayNaissance));
    
    if($infos->type == "celibat"){
        $content = "<div class='fixed-content'> <br/>
                       <p style='float:left;'>N°________/CBF/SG/SA/EC</p><br/><br/>
                       <p style='text-align:center; font-size:25px;'><b><u>CERTIFICAT DE CELIBAT</u></b></p><br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Le Maire, de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b>, soussigné, certifie que<br/> 
                       ".$civilite." <b>".$infos->concerne."</b> ".$ne." le <b>".$jourNaissance." ".$month[$montNaissance]." ".NumberToLetter($anNaissance)."</b> 
                       à <b>".$infos->lieu_naissance."</b>, ".$fils_fille." de <b>".$nom_pere."</b> et de <b>".$nom_mere."</b> n'a jamais contracté de mariage
                       après les recherches infructueuses sur nos registres de mariage de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b> et conformement
                       aux indications portées sur son acte de naissance <b>N° ".$infos->numero_act_naissance." DU ".date_format($infos->date_dresser,"d/m/Y")."</b> de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b>
                       vit bel et bien dans le célibat.</p>
                       <p>En foi de quoi, le présent certificat lui est délivré pour servir et valoir ce que de droit.</p><br/>
                       <p style='float:right;bottom:0;'>".$this->infosConfig()->commune.", le ".date("d/m/Y")."<br/><br/> L'Officier de l'Etat-Civil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><br/><br/>
                   </div>";
    }
    if($infos->type == "divorcer"){
        $content = "<div class='fixed-content'> <br/>
                       <p style='float:left;'>N°________/CBF/SG/SA/EC</p><br/><br/>
                       <p style='text-align:center; font-size:25px;'><b><u>CERTIFICAT DE CELIBAT</u></b></p><br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Le Maire, de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b>, soussigné, certifie que<br/> 
                       ".$civilite." <b>".$infos->concerne."</b> ".$ne." le <b>".$jourNaissance." ".$month[$montNaissance]." ".NumberToLetter($anNaissance)."</b> 
                       à <b>".$infos->lieu_naissance."</b>, ".$fils_fille." de <b>".$nom_pere."</b> et de <b>".$nom_mere."</b>, a contracté mariage le ".$date_mariage." à ".$infos->lieu_mariage."
                       avec ".$infos->conjoint." et disous par ".$raison_disolution_mariage."</b> sur requête <b>N° ".$numero_requette." du ".$date_requette."</b> par <b>".$tribunal."</b>.<br/>
                       Conformément aux indications portées sur son acte de naissance <b>N° ".$infos->numero_act_naissance." DU ".date_format($infos->date_dresser,"d/m/Y")."</b> de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b>
                       vit bel et bien dans le célibat.</p>
                       <p>En foi de quoi, le présent certificat lui est délivré pour servir et valoir ce que de droit.</p><br/>
                       <p style='float:right;bottom:0;'>".$this->infosConfig()->commune.", le ".date("d/m/Y")."<br/><br/> L'Officier de l'Etat-Civil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><br/><br/>
                   </div>";
    }
    
    if($infos->type == "non_divorcer"){
        $content = "<div class='fixed-content'> <br/>
                       <p style='float:left;'>N°________/CBF/SG/SA/EC</p><br/><br/>
                       <p style='text-align:center; font-size:25px;'><b><u>CERTIFICAT DE CELIBAT</u></b></p><br/>
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Le Maire, de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b>, soussigné, certifie que<br/> 
                       ".$civilite." <b>".$infos->concerne."</b> ".$ne." le <b>".$jourNaissance." ".$month[$montNaissance]." ".NumberToLetter($anNaissance)."</b> 
                       à <b>".$infos->lieu_naissance."</b>, ".$fils_fille." de <b>".$nom_pere."</b> et de <b>".$nom_mere."</b>, a contracté mariage le ".date_format($infos->date_mariage,"d/m/Y")." à ".$infos->lieu_mariage."
                       avec ".$infos->conjoint.".<br/>
                       Conformément aux indications portées sur son acte de naissance <b>N° ".$infos->numero_act_naissance." DU ".date_format($infos->date_dresser,"d/m/Y")."</b> de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b>
                       vit bel et bien dans le célibat.</p>
                       <p>En foi de quoi, le présent certificat lui est délivré pour servir et valoir ce que de droit.</p><br/>
                       <p style='float:right;bottom:0;'>".$this->infosConfig()->commune.", le ".date("d/m/Y")."<br/><br/> L'Officier de l'Etat-Civil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><br/><br/>
                   </div>";
    }
       
        return $content;
    }
}
