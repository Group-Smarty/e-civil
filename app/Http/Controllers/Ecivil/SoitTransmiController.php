<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\SoitTransmi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
include_once(app_path ()."/number-to-letters/nombre_en_lettre.php");

class SoitTransmiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Soit transmis";
       $btnModalAjout = "TRUE";
       return view('ecivil.soit-transmis.index',compact('btnModalAjout', 'menuPrincipal', 'titleControlleur')); 

    }

    public function listeSoitTransmi()
    {
        $certificats = SoitTransmi::where('soit_transmis.deleted_at', NULL) 
                        ->select('soit_transmis.*',DB::raw('DATE_FORMAT(soit_transmis.date_mariage, "%d-%m-%Y") as date_mariages'),DB::raw('DATE_FORMAT(soit_transmis.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(soit_transmis.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(soit_transmis.date_demande, "%d-%m-%Y") as date_demandes'))
                        ->orderBy('soit_transmis.id', 'DESC')
                        ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeSoitTransmiByNumeroActe($numero_acte)
    {
        $certificats = SoitTransmi::where([['soit_transmis.deleted_at', NULL],['soit_transmis.numero_acte_mariage','like','%'.$numero_acte.'%']]) 
                        ->select('soit_transmis.*',DB::raw('DATE_FORMAT(soit_transmis.date_mariage, "%d-%m-%Y") as date_mariages'),DB::raw('DATE_FORMAT(soit_transmis.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(soit_transmis.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(soit_transmis.date_demande, "%d-%m-%Y") as date_demandes'))
                        ->orderBy('soit_transmis.id', 'DESC')
                        ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
    public function listeSoitTransmiByNom($nom)
    {
        $certificats = SoitTransmi::where([['soit_transmis.deleted_at', NULL],['soit_transmis.concerne','like','%'.$nom.'%']]) 
                        ->orWhere([['soit_transmis.deleted_at', NULL],['soit_transmis.conjoint','like','%'.$nom.'%']]) 
                        ->select('soit_transmis.*',DB::raw('DATE_FORMAT(soit_transmis.date_mariage, "%d-%m-%Y") as date_mariages'),DB::raw('DATE_FORMAT(soit_transmis.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(soit_transmis.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(soit_transmis.date_demande, "%d-%m-%Y") as date_demandes'))
                        ->orderBy('soit_transmis.id', 'DESC')
                        ->get();
       $jsonData["rows"] = $certificats->toArray();
       $jsonData["total"] = $certificats->count();
       return response()->json($jsonData);
    }
    
     public function listeSoitTransmiByDate($dates)
    {
        $date = Carbon::createFromFormat('d-m-Y', $dates);
        $certificats = SoitTransmi::where('soit_transmis.deleted_at', NULL) 
                        ->whereDate('soit_transmis.date_demande','=',$date) 
                        ->orWhereDate('soit_transmis.date_mariage','=',$date) 
                        ->select('soit_transmis.*',DB::raw('DATE_FORMAT(soit_transmis.date_mariage, "%d-%m-%Y") as date_mariages'),DB::raw('DATE_FORMAT(soit_transmis.date_deces, "%d-%m-%Y") as date_decess'),DB::raw('DATE_FORMAT(soit_transmis.date_dresser, "%d-%m-%Y") as date_dressers'),DB::raw('DATE_FORMAT(soit_transmis.date_demande, "%d-%m-%Y") as date_demandes'))
                        ->orderBy('soit_transmis.id', 'DESC')
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
                $soitTransmi = new SoitTransmi;
                $soitTransmi->commune_destination = $data['commune_destination'];
                $soitTransmi->numero_acte = $data['numero_acte'];
                $soitTransmi->concerne = $data['concerne'];
                $soitTransmi->nombre = $data['nombre'];
                $soitTransmi->mention = $data['mention'];
                $soitTransmi->conjoint = isset($data['conjoint']) && !empty($data['conjoint']) ? $data['conjoint'] : null;
                $soitTransmi->date_dresser = Carbon::createFromFormat('d-m-Y', $data['date_dresser']);
                $soitTransmi->date_mariage = isset($data['date_mariage']) && !empty($data['date_mariage']) ? Carbon::createFromFormat('d-m-Y', $data['date_mariage']) : null;
                $soitTransmi->date_deces = isset($data['date_deces']) && !empty($data['date_deces']) ? Carbon::createFromFormat('d-m-Y', $data['date_deces']) : null;
                $soitTransmi->date_demande = now();
                $soitTransmi->created_by = Auth::user()->id;
                $soitTransmi->save();
                
                $jsonData["data"] = json_decode($soitTransmi);
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
     * @param  \App\SoitTransmi  $soitTransmi
     * @return Response
     */
    public function update(Request $request, SoitTransmi $soitTransmi)
    {
        
       $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if($soitTransmi){
            try{
                $data = $request->all(); 
                $soitTransmi->commune_destination = $data['commune_destination'];
                $soitTransmi->numero_acte = $data['numero_acte'];
                $soitTransmi->concerne = $data['concerne'];
                $soitTransmi->nombre = $data['nombre'];
                $soitTransmi->mention = $data['mention'];
                $soitTransmi->conjoint = isset($data['conjoint']) && !empty($data['conjoint']) ? $data['conjoint'] : null;
                $soitTransmi->date_dresser = Carbon::createFromFormat('d-m-Y', $data['date_dresser']);
                $soitTransmi->date_mariage = isset($data['date_mariage']) && !empty($data['date_mariage']) ? Carbon::createFromFormat('d-m-Y', $data['date_mariage']) : null;
                $soitTransmi->date_deces = isset($data['date_deces']) && !empty($data['date_deces']) ? Carbon::createFromFormat('d-m-Y', $data['date_deces']) : null;
                $soitTransmi->updated_by = Auth::user()->id;
                $soitTransmi->save();
                $jsonData["data"] = json_decode($soitTransmi);
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
     * @param  \App\SoitTransmi  $soitTransmi
     * @return Response
     */
    public function destroy(SoitTransmi $soitTransmi)
    {
        
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($soitTransmi){
                try {
                    $soitTransmi->update(['deleted_by' => Auth::user()->id]);
                    $soitTransmi->delete();
                    $jsonData["data"] = json_decode($soitTransmi);
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
    public function ficheSoitTransmisPdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->ficheSoitTransmis($id));
        return $pdf->stream('fiche_soit_transmis'.$id.'_'.date('d/m/Y').'.pdf');
    }

    public function ficheSoitTransmis($id){
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
                     font-size:20px;
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
        $infos = SoitTransmi::where([['soit_transmis.deleted_at', NULL],['soit_transmis.id',$id]]) 
                            ->select('soit_transmis.*')
                            ->first();
        
        $month = ['01'=>'Janvier','02'=>'Février','03'=>'Mars','04'=>'Avril','05'=>'Mai','06'=>'Juin','07'=>'Juillet','08'=>'Août','09'=>'Septembre','10'=>'Octobre','11'=>'Novembre','12'=>'Decembre'];
       
        $dateDresser = $infos->date_dresser;
        $dateMariage = $infos->date_mariage;
        $dateDeces = $infos->date_deces;
        
        $dayDresser = date('d', strtotime($dateDresser));
        $montDresser = date('m', strtotime($dateDresser));
        $anDresser = date('Y', strtotime($dateDresser));
        
        $dayMariage = date('d', strtotime($dateMariage));
        $montMariage = date('m', strtotime($dateMariage));
        $anMariage = date('Y', strtotime($dateMariage));
        
        $dayDeces = date('d', strtotime($dateDeces));
        $montDeces= date('m', strtotime($dateDeces));
        $anDeces = date('Y', strtotime($dateDeces));
        
        $dayDresser == 01 ? $jourDresser = 'premier' : $jourDresser = NumberToLetter(number_format($dayDresser));
        $dayMariage == 01 ? $jourMariage = 'premier' : $jourMariage = NumberToLetter(number_format($dayMariage));
        $dayDeces == 01 ? $jourDeces = 'premier' : $jourDeces = NumberToLetter(number_format($dayDeces));
    
        if($infos->mention == "mention_marginale"){
            $mention = "Pour mention marginal";
            $recepice = "";
        }else{
            $mention = "En retour après objet rempli";
            $recepice = "récépissé d'";
        }  
                
        if($infos->nombre==1 && $infos->date_deces==null){
             $content = "<div class='fixed-content'> <br/>
                       <p style='float:left;'>N°________/CBF/SG/SA/EC</p><br/><br/>
                       <p style='text-align:center; font-size:25px;'><b><u>SOIT TRANSMIS</u></b></p>
                       <p style='text-align:center;'>A Monsieur le Maire<br/> de la commune <b>".$infos->commune_destination."</b></p><br/>
                       <p>".ucwords(NumberToLetter(number_format($infos->nombre)))." (".$infos->nombre.") ".$recepice."avis de mention de mariage <b> N° ".$infos->numero_acte." du ".$jourDresser." ".$month[$montDresser]." ".NumberToLetter($anDresser)."</b></p>
                       <p>Concernant: <b>".$infos->concerne."</b></p>
                       <p>Marié(e) le <b>".$jourMariage." ".$month[$montMariage]." ".NumberToLetter($anMariage)."</b> avec <b>".$infos->conjoint."</b></p><br/>
                       <p style='text-align:center; font-size:25px;'>OBSERVATION</p><br/>
                       <p>(".$mention.")</p>
                       <p style='float:right;bottom:0;'>".$this->infosConfig()->commune.", le ".date("d/m/Y")."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><br/><br/>
                       <p style='float:right;'>L'Officier de l'Etat-Civil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                   </div>";
        }
        if($infos->nombre==2 && $infos->date_deces==null){
        $content = "<div class='fixed-content'> <br/>
                       <p style='float:left;'>N°________/CBF/SG/SA/EC</p><br/><br/>
                       <p style='text-align:center; font-size:25px;'><b><u>SOIT TRANSMIS</u></b></p>
                       <p style='text-align:center;'>A Monsieur le Maire<br/> de la commune <b>".$infos->commune_destination."</b></p><br/>
                       <p>".ucwords(NumberToLetter(number_format($infos->nombre)))." (".$infos->nombre.") ".$recepice."avis de mention de mariage <b> N° ".$infos->numero_acte." du ".$jourDresser." ".$month[$montDresser]." ".NumberToLetter($anDresser)."</b></p>
                       <p>Concernant: <b>".$infos->concerne."</b></p>";
                if($infos->nombre==2 && $infos->date_deces==null){
                    $content.="<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       <b>".$infos->conjoint."</b></p>";
                }
          $content.="<p>Mariés le <b>".$jourMariage." ".$month[$montMariage]." ".NumberToLetter($anMariage)."</b> en notre mairie</p><br/>
                       <p style='text-align:center; font-size:25px;'>OBSERVATION</p><br/>
                       <p>(".$mention.")</p>
                       <p style='float:right;bottom:0;'>".$this->infosConfig()->commune.", le ".date("d/m/Y")."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><br/><br/>
                       <p style='float:right;'>L'Officier de l'Etat-Civil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                   </div>";
        }
        if($infos->date_deces!=null){
             $content = "<div class='fixed-content'> <br/>
                       <p style='float:left;'>N°________/CBF/SG/SA/EC</p><br/><br/>
                       <p style='text-align:center; font-size:25px;'><b><u>SOIT TRANSMIS</u></b></p>
                       <p style='text-align:center;'>A Monsieur le Maire<br/> de la commune <b>".$infos->commune_destination."</b></p><br/>
                       <p>".ucwords(NumberToLetter(number_format($infos->nombre)))." (".$infos->nombre.") ".$recepice."avis de mention de décès <b> N° ".$infos->numero_acte." du ".$jourDresser." ".$month[$montDresser]." ".NumberToLetter($anDresser)."</b></p>
                       <p>Concernant: <b>".$infos->concerne."</b></p>
                       <p>Décédé le <b>".$jourDeces." ".$month[$montDeces]." ".NumberToLetter($anDeces)."</b></p><br/>
                       <p style='text-align:center; font-size:25px;'>OBSERVATION</p><br/>
                       <p>(".$mention.")</p>
                       <p style='float:right;bottom:0;'>".$this->infosConfig()->commune.", le ".date("d/m/Y")."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><br/><br/>
                       <p style='float:right;'>L'Officier de l'Etat-Civil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                   </div>";
        }
        return $content;
    }
}
