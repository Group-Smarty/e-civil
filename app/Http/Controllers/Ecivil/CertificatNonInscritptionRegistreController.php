<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\CertificatNonInscritptionRegistre;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TheSeer\Tokenizer\Exception;

class CertificatNonInscritptionRegistreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Demande de certificat de non inscription sur registre de décès";
       $btnModalAjout = "TRUE";
       return view('ecivil.certificat-non-inscription-deces.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeCertificatNonInscritptionRegistre()
    {
       $certificats = CertificatNonInscritptionRegistre::where('certificat_non_inscritption_registres.deleted_at', NULL) 
                ->select('certificat_non_inscritption_registres.*',DB::raw('DATE_FORMAT(certificat_non_inscritption_registres.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(certificat_non_inscritption_registres.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                ->orderBy('certificat_non_inscritption_registres.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatNonInscritptionRegistreByName($name)
    {
       $certificats = CertificatNonInscritptionRegistre::where([['certificat_non_inscritption_registres.deleted_at', NULL],['certificat_non_inscritption_registres.nom_complet_decede','like','%'.$name.'%']]) 
                 ->select('certificat_non_inscritption_registres.*',DB::raw('DATE_FORMAT(certificat_non_inscritption_registres.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(certificat_non_inscritption_registres.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                ->orderBy('certificat_non_inscritption_registres.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatNonInscritptionRegistreByPiece($piece){
        $certificats = CertificatNonInscritptionRegistre::where([['certificat_non_inscritption_registres.deleted_at', NULL],['certificat_non_inscritption_registres.numero_piece_demandeur','like','%'.$piece.'%']]) 
                 ->select('certificat_non_inscritption_registres.*',DB::raw('DATE_FORMAT(certificat_non_inscritption_registres.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(certificat_non_inscritption_registres.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                ->orderBy('certificat_non_inscritption_registres.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatNonInscritptionRegistreByDate($dates){
        $date = Carbon::createFromFormat('d-m-Y', $dates);
        $certificats = CertificatNonInscritptionRegistre::where('certificat_non_inscritption_registres.deleted_at', NULL) 
                ->whereDate('certificat_non_inscritption_registres.date_demande_certificat',$date)
                 ->select('certificat_non_inscritption_registres.*',DB::raw('DATE_FORMAT(certificat_non_inscritption_registres.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(certificat_non_inscritption_registres.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                ->orderBy('certificat_non_inscritption_registres.id', 'DESC')
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
        if ($request->isMethod('post') && $request->input('nom_complet_decede')) {
            $data = $request->all(); 
        try{
                
                //Enregistrement 
                $certificatNonInscritptionRegistre = new CertificatNonInscritptionRegistre;
                $certificatNonInscritptionRegistre->nom_complet_decede = $data['nom_complet_decede'];
                $certificatNonInscritptionRegistre->date_deces = Carbon::createFromFormat('d-m-Y', $data['date_deces']);
                $certificatNonInscritptionRegistre->lieu_deces = $data['lieu_deces'];
                $certificatNonInscritptionRegistre->adresse_demandeur = $data['adresse_demandeur'];
                $certificatNonInscritptionRegistre->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur']:null;
                $certificatNonInscritptionRegistre->numero_piece_demandeur = isset($data['numero_piece_demandeur']) && !empty($data['numero_piece_demandeur']) ? $data['numero_piece_demandeur']:null;
                $certificatNonInscritptionRegistre->nom_complet_pere = isset($data['nom_complet_pere']) && !empty($data['nom_complet_pere'])?$data['nom_complet_pere']:null;
                $certificatNonInscritptionRegistre->nom_complet_mere = isset($data['nom_complet_mere']) && !empty($data['nom_complet_mere'])?$data['nom_complet_mere']:null;
                $certificatNonInscritptionRegistre->date_demande_certificat = now();
                $certificatNonInscritptionRegistre->montant = $data['montant'];
                $certificatNonInscritptionRegistre->created_by = Auth::user()->id;
                $certificatNonInscritptionRegistre->save();
                
                $jsonData["data"] = json_decode($certificatNonInscritptionRegistre);
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
     * @param  \App\CertificatNonInscritptionRegistre  $certificatNonInscritptionRegistre
     * @return Response
     */
    public function update(Request $request, $id)
    {   $certificatNonInscritptionRegistre = CertificatNonInscritptionRegistre::find($id);
         $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if($certificatNonInscritptionRegistre){
            try{
                $data = $request->all(); 
                $certificatNonInscritptionRegistre->nom_complet_decede = $data['nom_complet_decede'];
                $certificatNonInscritptionRegistre->date_deces = Carbon::createFromFormat('d-m-Y', $data['date_deces']);
                $certificatNonInscritptionRegistre->lieu_deces = $data['lieu_deces'];
                $certificatNonInscritptionRegistre->adresse_demandeur = $data['adresse_demandeur'];
                $certificatNonInscritptionRegistre->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur']:null;
                $certificatNonInscritptionRegistre->numero_piece_demandeur = isset($data['numero_piece_demandeur']) && !empty($data['numero_piece_demandeur']) ? $data['numero_piece_demandeur']:null;
                $certificatNonInscritptionRegistre->nom_complet_pere = isset($data['nom_complet_pere']) && !empty($data['nom_complet_pere'])?$data['nom_complet_pere']:null;
                $certificatNonInscritptionRegistre->nom_complet_mere = isset($data['nom_complet_mere']) && !empty($data['nom_complet_mere'])?$data['nom_complet_mere']:null;
                $certificatNonInscritptionRegistre->date_demande_certificat = now();
                $certificatNonInscritptionRegistre->montant = $data['montant'];
                $certificatNonInscritptionRegistre->updated_by = Auth::user()->id;
                $certificatNonInscritptionRegistre->save();
                $jsonData["data"] = json_decode($certificatNonInscritptionRegistre);
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
     * @param  \App\CertificatNonInscritptionRegistre  $certificatNonInscritptionRegistre
     * @return Response
     */
    public function destroy($id)
    {     $certificatNonInscritptionRegistre = CertificatNonInscritptionRegistre::find($id);
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($certificatNonInscritptionRegistre){
                try {
                    $certificatNonInscritptionRegistre->update(['deleted_by' => Auth::user()->id]);
                    $certificatNonInscritptionRegistre->delete();
                    $jsonData["data"] = json_decode($certificatNonInscritptionRegistre);
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
    public function certificatNonInscritptionRegistrePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->certificatNonInscritptionRegistre($id));
        return $pdf->stream('certificat_non_inscription_registre.pdf');
    }
    
    public function certificatNonInscritptionRegistre($id){
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
                    height:30%;
                    text-align:center;
                    position: absolute; 
                    float: left;
                }
                .fixed-header-right{
                    width: 45%;
                    height:30%;
                    text-align:center;
                    position: absolute;
                    float: right;
                }
               
                .fixed-content{
                    margin:320px 0;
                     position: absolute;
                }
                .fixed-footer{
                    position: fixed; 
                    bottom: -20; 
                    float: right;
                    left: 0px; 
                    right: 0px;
                    height: 100px; 
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
        $header .= "<br/> 
                    N°_________________/CBF/SA/EC
                </div>
                <div class='fixed-header-right'>
                   <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                    Union-Discipline-Travail<hr width='50'/></b>
                </div>";
        return $header;
    }
    
    public function content($id){
        $infos = CertificatNonInscritptionRegistre::where([['certificat_non_inscritption_registres.deleted_at', NULL],['certificat_non_inscritption_registres.id',$id]]) 
                ->select('certificat_non_inscritption_registres.*',DB::raw('DATE_FORMAT(certificat_non_inscritption_registres.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(certificat_non_inscritption_registres.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'))
                 ->first();

        $content = "<div class='fixed-content'> 
                        <p style='text-align:center; font-size:25px;'><b><u>CERTIFICAT DE NON INSCRIPTION SUR LES REGISTRES DE DECES</u></b></p>
                        <p>Le maire, de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b> Officier de l'Etat-Civil certifie que le</p>  
                        <p>Décès de <b>".$infos->nom_complet_decede."</b></p>  
                        <p>Fils/Fille de <b>".$infos->nom_complet_pere."</b></p>  
                        <p>et de <b>".$infos->nom_complet_mere."</b></p> 
                        <p>Qui serait survenu le <b>".$infos->date_decess."</b> à <b>".$infos->lieu_deces."</b></p>  
                        <p>N'a pas été inscrit sur les registres de déclaration de décès de l'Etat-Civil de la Commune</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;En fois de quoi, nous lui délivrons le présent certificat pour servir et valoir ce que de droit.</p><br/> 
                        <span style='float:right;'>".$this->infosConfig()->commune.", le ".date("d/m/Y")."</span><br/><br/>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <b><u>Le Maire</u></b></span>
                   </div>";
        return $content;
    }
}
