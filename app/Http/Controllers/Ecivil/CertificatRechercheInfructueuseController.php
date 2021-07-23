<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\CertificatRechercheInfructueuse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CertificatRechercheInfructueuseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Demande de certificat recherches infructueuses";
       $btnModalAjout = "TRUE";
       return view('ecivil.certificat-infructueuses.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    public function listeCertificatRechercheInfructueuse()
    {
       $certificats = CertificatRechercheInfructueuse::where('certificat_recherche_infructueuses.deleted_at', NULL) 
                ->select('certificat_recherche_infructueuses.*',DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_evenement, "%d-%m-%Y") as date_evenements'),DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_certificat_medical, "%d-%m-%Y") as date_certificat_medicals'))
                ->orderBy('certificat_recherche_infructueuses.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    public function listeCertificatRechercheInfructueuseByName($name)
    {
       $certificats = CertificatRechercheInfructueuse::where([['certificat_recherche_infructueuses.deleted_at', NULL],['certificat_recherche_infructueuses.nom_complet_demandeur','like','%'.$name.'%']])->orWhere([['certificat_recherche_infructueuses.deleted_at', NULL],['certificat_recherche_infructueuses.nom_complet_concerne','like','%'.$name.'%']])
                ->select('certificat_recherche_infructueuses.*',DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_evenement, "%d-%m-%Y") as date_evenements'),DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_certificat_medical, "%d-%m-%Y") as date_certificat_medicals'))
                ->orderBy('certificat_recherche_infructueuses.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    public function listeCertificatRechercheInfructueuseByPiece($numero_piece)
    {
       $certificats = CertificatRechercheInfructueuse::where([['certificat_recherche_infructueuses.deleted_at', NULL],['certificat_recherche_infructueuses.numero_piece_demandeur','like','%'.$numero_piece.'%']])
                ->select('certificat_recherche_infructueuses.*',DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_evenement, "%d-%m-%Y") as date_evenements'),DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_certificat_medical, "%d-%m-%Y") as date_certificat_medicals'))
                ->orderBy('certificat_recherche_infructueuses.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
     public function listeCertificatRechercheInfructueuseByDate($dates)
    {
       $date = Carbon::createFromFormat('d-m-Y', $dates);
       $certificats = CertificatRechercheInfructueuse::where('certificat_recherche_infructueuses.deleted_at', NULL)
                ->select('certificat_recherche_infructueuses.*',DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_demande_certificat, "%d-%m-%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_evenement, "%d-%m-%Y") as date_evenements'),DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_certificat_medical, "%d-%m-%Y") as date_certificat_medicals'))
                ->whereDate('certificat_recherche_infructueuses.date_demande_certificat',$date)
                ->orderBy('certificat_recherche_infructueuses.id', 'DESC')
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
        if ($request->isMethod('post') && $request->input('nom_complet_concerne')) {
            $data = $request->all(); 
        try{
                //Enregistrement 
                $certificatRechercheInfructueuse = new CertificatRechercheInfructueuse;
                $certificatRechercheInfructueuse->nom_complet_concerne = $data['nom_complet_concerne'];
                $certificatRechercheInfructueuse->nom_complet_demandeur = $data['nom_complet_demandeur'];
                $certificatRechercheInfructueuse->numero_certificat_medical = $data['numero_certificat_medical'];
                $certificatRechercheInfructueuse->lieu_certificat_medical = $data['lieu_certificat_medical'];
                $certificatRechercheInfructueuse->lieu_evenement = $data['lieu_evenement'];
                $certificatRechercheInfructueuse->date_certificat_medical = Carbon::createFromFormat('d-m-Y', $data['date_certificat_medical']);
                $certificatRechercheInfructueuse->date_evenement = Carbon::createFromFormat('d-m-Y', $data['date_evenement']);
                $certificatRechercheInfructueuse->adresse_demandeur = $data['adresse_demandeur'];
                $certificatRechercheInfructueuse->date_demande_certificat = Carbon::createFromFormat('d-m-Y', $data['date_demande_certificat']);
                $certificatRechercheInfructueuse->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur'])?$data['contact_demandeur']:null;
                $certificatRechercheInfructueuse->numero_piece_demandeur = isset($data['numero_piece_demandeur']) && !empty($data['numero_piece_demandeur'])?$data['numero_piece_demandeur']:null;
                $certificatRechercheInfructueuse->montant = $data['montant'];
                $certificatRechercheInfructueuse->created_by = Auth::user()->id;
                $certificatRechercheInfructueuse->save();
                $jsonData["data"] = json_decode($certificatRechercheInfructueuse);
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
     * @param  \App\CertificatRechercheInfructueuse  $certificatRechercheInfructueuse
     * @return Response
     */
    public function update(Request $request, $id)
    {
         $certificatRechercheInfructueuse = CertificatRechercheInfructueuse::find($id);
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if($certificatRechercheInfructueuse){
            try{
                $data = $request->all(); 
                $certificatRechercheInfructueuse->nom_complet_concerne = $data['nom_complet_concerne'];
                $certificatRechercheInfructueuse->nom_complet_demandeur = $data['nom_complet_demandeur'];
                $certificatRechercheInfructueuse->numero_certificat_medical = $data['numero_certificat_medical'];
                $certificatRechercheInfructueuse->lieu_certificat_medical = $data['lieu_certificat_medical'];
                $certificatRechercheInfructueuse->lieu_evenement = $data['lieu_evenement'];
                $certificatRechercheInfructueuse->date_certificat_medical = Carbon::createFromFormat('d-m-Y', $data['date_certificat_medical']);
                $certificatRechercheInfructueuse->date_evenement = Carbon::createFromFormat('d-m-Y', $data['date_evenement']);
                $certificatRechercheInfructueuse->adresse_demandeur = $data['adresse_demandeur'];
                $certificatRechercheInfructueuse->date_demande_certificat = Carbon::createFromFormat('d-m-Y', $data['date_demande_certificat']);
                $certificatRechercheInfructueuse->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur'])?$data['contact_demandeur']:null;
                $certificatRechercheInfructueuse->numero_piece_demandeur = isset($data['numero_piece_demandeur']) && !empty($data['numero_piece_demandeur'])?$data['numero_piece_demandeur']:null;
                $certificatRechercheInfructueuse->montant = $data['montant'];
                $certificatRechercheInfructueuse->updated_by = Auth::user()->id;
                $certificatRechercheInfructueuse->save();
                $jsonData["data"] = json_decode($certificatRechercheInfructueuse);
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
     * @param  \App\CertificatRechercheInfructueuse  $certificatRechercheInfructueuse
     * @return Response
     */
    public function destroy($id)
    {
        $certificatRechercheInfructueuse = CertificatRechercheInfructueuse::find($id);
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($certificatRechercheInfructueuse){
                try {
                    $certificatRechercheInfructueuse->update(['deleted_by' => Auth::user()->id]);
                    $certificatRechercheInfructueuse->delete();
                    $jsonData["data"] = json_decode($certificatRechercheInfructueuse);
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
    
    //Etata
    public function certificatRechercheInfructueusePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->certificatRechercheInfructueuse($id));
        return $pdf->stream('certificat_recherche_infructueuse.pdf');
    }
    
    public function certificatRechercheInfructueuse($id){
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
                    N°_________________/CBF/SG/SA
                </div>
                <div class='fixed-header-right'>
                   <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                    Union-Discipline-Travail<hr width='50'/></b>
                </div>";
        return $header;
    }
    
    public function content($id){
        $infos = CertificatRechercheInfructueuse::where([['certificat_recherche_infructueuses.deleted_at', NULL],['certificat_recherche_infructueuses.id',$id]]) 
                ->select('certificat_recherche_infructueuses.*',DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_demande_certificat, "%d/%m/%Y") as date_demande_certificats'),DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_evenement, "%d/%m/%Y") as date_evenements'),DB::raw('DATE_FORMAT(certificat_recherche_infructueuses.date_certificat_medical, "%d/%m/%Y") as date_certificat_medicals'))
                ->orderBy('certificat_recherche_infructueuses.id', 'DESC')
                ->first();
      
        $content = "<div class='fixed-content'> 
                         <p style='text-align:center; font-size:25px;'><b><u>CERTIFICAT DE RECHERCHES INFRUCTUEUSE</u></b></p>
                      <p style='text-align:center;'><b>Le maire, de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</b>, Officier de l'Etat Civil,</b></p>  
                      <p>Vue la demande en date du <b>".$infos->date_demande_certificats."</b></p>  
                      <p>Présentée par <b>".$infos->nom_complet_demandeur."</b></p>  
                      <p>Tendant à obtenir un certificat de non inscription dans les registres de l’Etat civil de la commune ".$this->premierLetre()."<b>".$this->infosConfig()->commune."</p> 
                      <p>du décès, de la naissance de <b>".$infos->nom_complet_concerne."</b></p>  
                      <p>Qui serait survenu selon les dires, le certificat médical (d’âge apparent/ de genre de mort) N° <b>".$infos->numero_certificat_medical."</b></p> 
                      <p>DU <b>".$infos->date_certificat_medicals."</b> DE <b>".$infos->lieu_certificat_medical."</b> le <b>".$infos->date_evenements."</b></p> 
                      <p>A <b>".$infos->lieu_evenement."</b> et qui n’aurait jamais fait objet de la déclaration prévue par la loi relative à l’Etat civil.</p>
                      <p>Certifie que les recherches effectuées dans nos registres à la date indiquée et au cours du délai réglementaire de la quinzaine, se sont révélées infructueuses</p>
                      <p>En foi de quoi nous lui délivrons le présent certificat sous toutes réserves pour servir et valoir ce que de droit</p><br/> 
                     <span style='float:right;'>Fait à ".$this->infosConfig()->commune.", le ".date("d/m/Y")."</span><br/> <br/> <br/> 
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <b><u>Le Maire</u></b></span>
                   </div>";
        return $content;
    }
}
