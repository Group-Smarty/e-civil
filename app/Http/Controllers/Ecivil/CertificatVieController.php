<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\CertificatVie;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CertificatVieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $fonctions = DB::table('fonctions')->Where('deleted_at', NULL)->orderBy('libelle_fonction', 'asc')->get();
       $naissances = DB::table('naissances')->select('naissances.numero_acte_naissance','naissances.id',DB::raw('DATE_FORMAT(naissances.date_dresser, "%d-%m-%Y") as date_dressers'))->Where('deleted_at', NULL)->orderBy('id', 'desc')->get();
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Demande de certificat de vie";
       $btnModalAjout = "TRUE";
       return view('ecivil.certificat-vie.index',compact('fonctions','naissances', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 

    }

    public function listeCertificatVie()
    {
        $certificats = CertificatVie::with('fonction')
                ->Where('certificat_vies.deleted_at', NULL) 
                ->select('certificat_vies.*',DB::raw('DATE_FORMAT(certificat_vies.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(certificat_vies.date_demande_certificat, "%d-%m-%Y %H:%i") as date_demande_certificats'))
                ->orderBy('certificat_vies.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatVieByName($name){
        $certificats = CertificatVie::with('fonction')
                ->Where([['certificat_vies.deleted_at', NULL],['certificat_vies.nom_complet_naissance','like','%'.$name.'%']]) 
                ->orWhere([['certificat_vies.deleted_at', NULL],['certificat_vies.nom_complet_usage','like','%'.$name.'%']]) 
                ->select('certificat_vies.*',DB::raw('DATE_FORMAT(certificat_vies.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(certificat_vies.date_demande_certificat, "%d-%m-%Y %H:%i") as date_demande_certificats'))
                ->orderBy('certificat_vies.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatVieByPieceIdentite($numero){
        $certificats = CertificatVie::with('fonction')
                ->Where([['certificat_vies.deleted_at', NULL],['certificat_vies.numero_piece_demandeur','like','%'.$numero.'%']]) 
                ->select('certificat_vies.*',DB::raw('DATE_FORMAT(certificat_vies.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(certificat_vies.date_demande_certificat, "%d-%m-%Y %H:%i") as date_demande_certificats'))
                ->orderBy('certificat_vies.id', 'DESC')
                ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeCertificatVieByDate($dates){
        $date = Carbon::createFromFormat('d-m-Y', $dates);
         $certificats = CertificatVie::with('fonction')
                ->Where('certificat_vies.deleted_at', NULL) 
                ->whereDate('certificat_vies.date_demande_certificat','=', $date)
                ->orWhereDate('certificat_vies.date_naissance','=', $date)
                ->select('certificat_vies.*',DB::raw('DATE_FORMAT(certificat_vies.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(certificat_vies.date_demande_certificat, "%d-%m-%Y %H:%i") as date_demande_certificats'))
                ->orderBy('certificat_vies.id', 'DESC')
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
        if ($request->isMethod('post') && $request->input('nom_complet_naissance')) {
            $data = $request->all(); 
        try{
                
                //Enregistrement 
                $certificatVie = new CertificatVie;
                $certificatVie->nom_complet_naissance = $data['nom_complet_naissance'];
                $certificatVie->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur']) ? $data['contact_demandeur']:null;
                $certificatVie->adresse_demandeur = $data['adresse_demandeur'];
                $certificatVie->numero_piece_demandeur = isset($data['numero_piece_demandeur']) && !empty($data['numero_piece_demandeur'])?$data['numero_piece_demandeur']:null;
                $certificatVie->lieu_naissance = $data['lieu_naissance'];
                $certificatVie->date_demande_certificat = now();
                $certificatVie->montant = isset($data['montant'])&&!empty($data['montant'])?$data['montant']:0;
                $certificatVie->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $certificatVie->fonction_id = isset($data['fonction_id']) && !empty($data['fonction_id']) ? $data['fonction_id']: null; 
                $certificatVie->nom_complet_usage = isset($data['nom_complet_usage']) && !empty($data['nom_complet_usage']) ? $data['nom_complet_usage'] : Null;
                $certificatVie->numero_acte_naissance_demandeur = isset($data['numero_acte_naissance_demandeur']) && !empty($data['numero_acte_naissance_demandeur']) ? $data['numero_acte_naissance_demandeur'] : Null;
                $certificatVie->nom_complet_pere = isset($data['nom_complet_pere']) && !empty($data['nom_complet_pere']) ? $data['nom_complet_pere'] : Null;
                $certificatVie->nom_complet_mere = isset($data['nom_complet_mere']) && !empty($data['nom_complet_mere']) ? $data['nom_complet_mere'] : Null;
                $certificatVie->naissance_id = isset($data['naissance_id']) && !empty($data['naissance_id']) ? $data['naissance_id'] : Null;
                $certificatVie->etat_civil_naissance = isset($data['etat_civil_naissance']) && !empty($data['etat_civil_naissance']) ? TRUE: FALSE;
                $certificatVie->created_by = Auth::user()->id;
                $certificatVie->save();
                
                $jsonData["data"] = json_decode($certificatVie);
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
     * @param  \App\CertificatVie  $certificatVie
     * @return Response
     */
    public function update(Request $request, CertificatVie $certificatVie)
    {
        $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if($certificatVie){
            try{
                $data = $request->all(); 
                $certificatVie->nom_complet_naissance = $data['nom_complet_naissance'];
                $certificatVie->contact_demandeur = isset($data['contact_demandeur']) && !empty($data['contact_demandeur'])?$data['contact_demandeur']:null;
                $certificatVie->adresse_demandeur = $data['adresse_demandeur'];
                $certificatVie->numero_piece_demandeur = isset($data['numero_piece_demandeur']) && !empty($data['numero_piece_demandeur'])?$data['numero_piece_demandeur']:null;
                $certificatVie->lieu_naissance = $data['lieu_naissance'];
                $certificatVie->date_demande_certificat = now();
                $certificatVie->montant = isset($data['montant'])&&!empty($data['montant'])?$data['montant']:0;
                $certificatVie->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $certificatVie->fonction_id = isset($data['fonction_id']) && !empty($data['fonction_id']) ? $data['fonction_id']: null; 
                $certificatVie->nom_complet_usage = isset($data['nom_complet_usage']) && !empty($data['nom_complet_usage']) ? $data['nom_complet_usage'] : Null;
                $certificatVie->numero_acte_naissance_demandeur = isset($data['numero_acte_naissance_demandeur']) && !empty($data['numero_acte_naissance_demandeur']) ? $data['numero_acte_naissance_demandeur'] : Null;
                $certificatVie->nom_complet_pere = isset($data['nom_complet_pere']) && !empty($data['nom_complet_pere']) ? $data['nom_complet_pere'] : Null;
                $certificatVie->nom_complet_mere = isset($data['nom_complet_mere']) && !empty($data['nom_complet_mere']) ? $data['nom_complet_mere'] : Null;
                $certificatVie->naissance_id = isset($data['naissance_id']) && !empty($data['naissance_id']) ? $data['naissance_id'] : Null;
                $certificatVie->etat_civil_naissance = isset($data['etat_civil_naissance']) && !empty($data['etat_civil_naissance']) ? TRUE: FALSE;
                $certificatVie->updated_by = Auth::user()->id;
                $certificatVie->save();
                $jsonData["data"] = json_decode($certificatVie);
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
     * @param  \App\CertificatVie  $certificatVie
     * @return Response
     */
    public function destroy(CertificatVie $certificatVie)
    {
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($certificatVie){
                try {
                    $certificatVie->update(['deleted_by' => Auth::user()->id]);
                    $certificatVie->delete();
                    $jsonData["data"] = json_decode($certificatVie);
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
    public function ficheCertificatViePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheCertificatVie($id));
        return $pdf->stream('certificat_vie_'.$id.'_'.date('d/m/Y').'.pdf');
    }

    public function ficheCertificatVie($id){
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
        $infos = CertificatVie::where([['certificat_vies.deleted_at', NULL],['certificat_vies.id',$id]]) 
                ->join('fonctions','fonctions.id','=','certificat_vies.fonction_id')
                ->select('certificat_vies.*','fonctions.libelle_fonction',DB::raw('DATE_FORMAT(certificat_vies.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(certificat_vies.date_demande_certificat, "%d-%m-%Y %H:%i") as date_demande_certificats'))
                ->first();
        !empty($infos->nom_complet_usage) ? $nomUsage = 'Epouse <b>'.$infos->nom_complet_usage.'</b>' : $nomUsage = null;
        !empty($infos->nom_complet_pere) ? $nomPere = $infos->nom_complet_pere : $nomPere = "";
        !empty($infos->nom_complet_mere) ? $nomMere = $infos->nom_complet_mere : $nomMere = "";
    
        $content = "<div class='fixed-content'> 
                      <p style='text-align:center; font-size:25px;'><b><u>CERTIFICAT DE VIE</u></b></p>
                      <p>Le maire de la commune <b>".$this->premierLetre()."".$this->infosConfig()->commune."</b> soussigné certifie que <b>".$infos->nom_complet_naissance."</b> ".$nomUsage."</p>  
                      <p>Né(e) à <b>".$infos->lieu_naissance."</b> le <b>".$infos->date_naissances."</b></p>  
                      <p>Fils/Fille de <b>".$nomPere."</b> <br/><br/>et de <b>".$nomMere."</b></p>  
                      <p>Exerçant la profession de <b>".$infos->libelle_fonction."</b> <br/><br/>Domicilé à <b>".$infos->adresse_demandeur."</b></p>  
                      <p>est vivant(e) pour s'être présenté(e) devant nous ce jour.</p>  
                      <p>En foi de quoi, le présent certificat a été établi pour servir et valoir ce que de droit.</p><br/><br/><br/>
                      <p style='float:right;bottom:0;'>Fait à ".$this->infosConfig()->commune.", le ".date("d/m/Y")."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><br/><br/><br/>
                          <p style='float:right;'>P. Le Maire P.O.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                   </div>";
        return $content;
    }
    
}
