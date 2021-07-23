<?php

namespace App\Http\Controllers\Ecivil;

use App\Http\Controllers\Controller;
use App\Models\Ecivil\Decede;
use App\Models\Ecivil\Declaration;
use App\Models\Ecivil\Mariage;
use App\Models\Ecivil\Naissance;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DeclarationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $typeDeclarations = Declaration::where('declarations.deleted_at', NULL)
                            ->select('type_declaration')
                            ->groupBy('type_declaration')
                            ->get();
       $menuPrincipal = "Etat civil";
       $titleControlleur = "Liste de toutes les déclarations effectuées";
       $btnModalAjout = "FALSE";
       return view('ecivil.declaration.index',compact('typeDeclarations', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
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
    //Etats reçu de naissance
    public function recuDeclarationNaissancePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->recuDeclarationNaissance($id));
        return $pdf->stream('recu_'.$id.'_'.date('d/m/Y').'.pdf');
    }
    public function recuDeclarationNaissance($id){
        $outPut = $this->headerNaissance($id);
        $outPut.= $this->contentNaissance($id);
        $outPut.= $this->footer();
        return $outPut;
    }
    public function headerNaissance($id){
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);
        $recuInfo = Naissance::find($id);
        $header = "<html>
        <head>
            <meta charset='utf-8'>
            <style>
               @page { size: 17cm 21cm landscape; }
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
                    margin:270px 0;
                    position: absolute;
                }
                .fixed-footer{
                    position: fixed; 
                    bottom: -28; 
                    float: right;
                    left: 0px; 
                    right: 0px;
                    height: 50px; 
                    text-align:center;
                }  
              
            </style>
            <body>
                <div class='fixed-header-left'>";
             $header.="COMMUNE ".strtoupper($this->premierLetre()."".$commune)."<hr width='50'/></b>
                    <img src=".$this->infosConfig()->logo." width='150' height='150'><br/> 
                    <b> Mairie ".$this->premierLetre()."".$this->infosConfig()->commune."</b><br/>";
            if($this->infosConfig()->adresse_marie!=null){
                 $header.="Adresse: ".$this->infosConfig()->adresse_marie."<br/>";
            }
            if($this->infosConfig()->telephone_mairie!=null){
                $header.="Tel : ".$this->infosConfig()->telephone_mairie."<br/>"; 
            }           
            if($this->infosConfig()->fax_mairie!=null){
                $header.="Fax : ".$this->infosConfig()->fax_mairie."<br/>"; 
            }     
            if($this->infosConfig()->site_web_mairie!=null){
                  $header.="".$this->infosConfig()->site_web_mairie."<br/> ";
            }       
            $header.="</div>
                    <div class='fixed-header-right'>
                    <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                    Union-Discipline-Travail<hr width='50'/></b> 
                    <p><i>RECEPISSE N° <b>".$recuInfo->id."</b></i></p>
                </div>";
        return $header;
    }
    public function contentNaissance($id){
        $recu = Naissance::find($id);
        $content ="<div class='fixed-content'> 
                    <p style='text-align:center; font-size:25px;'><b><u>RECEPISSE DE DECLARATION DE NAISSANCE</u></b></p>
                    <p><i> Naissance de </i><b>".$recu->prenom_enfant." ".$recu->nom_enfant."</b> <i>le</i> <b>".date("d-m-Y", strtotime($recu->date_naissance_enfant))."</b><i> à </i><b>".$recu->lieu_naissance_enfant."</b></p>
                    <p><i> Référence </i><b>".$recu->numero_acte_naissance." du ".date("d-m-Y", strtotime($recu->date_dresser))."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Nombre de copies</i> <b>".$recu->nombre_copie."</p>
                    <p><i> Déclarée le </i><b>".date("d-m-Y", strtotime($recu->date_declaration))."</b> <i>par</i> <b>".$recu->nom_complet_declarant."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Date de retrait </i> <b>".date("d-m-Y", strtotime($recu->date_retrait))."</b></p>
                </div>";
        return $content;
    }
    
    
    //Etats reçu de mariage
    public function recuDeclarationMariagePdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->recuDeclarationMariage($id));
        return $pdf->stream('recu_'.$id.'_'.date('d/m/Y').'.pdf');
    }
    public function recuDeclarationMariage($id){
        $outPut = $this->headerMariage($id);
        $outPut.= $this->contentMariage($id);
        $outPut.= $this->footer();
        return $outPut;
    }
    public function headerMariage($id){
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);
        $recuInfo = Mariage::find($id);
        $header = "<html>
        <head>
            <meta charset='utf-8'>
            <style>
               @page { size: 17cm 21cm landscape; }
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
                    margin:270px 0;
                    position: absolute;
                }
                .fixed-footer{
                    position: fixed; 
                    bottom: -28; 
                    float: right;
                    left: 0px; 
                    right: 0px;
                    height: 50px; 
                    text-align:center;
                }  
              
            </style>
            <body>
                <div class='fixed-header-left'>";
             $header.="COMMUNE ".strtoupper($this->premierLetre()."".$commune)."<hr width='50'/></b>
                    <img src=".$this->infosConfig()->logo." width='150' height='150'><br/> 
                    <b> Mairie ".$this->premierLetre()."".$this->infosConfig()->commune."</b><br/>";
            if($this->infosConfig()->adresse_marie!=null){
                 $header.="Adresse: ".$this->infosConfig()->adresse_marie."<br/>";
            }
            if($this->infosConfig()->telephone_mairie!=null){
                $header.="Tel : ".$this->infosConfig()->telephone_mairie."<br/>"; 
            }           
            if($this->infosConfig()->fax_mairie!=null){
                $header.="Fax : ".$this->infosConfig()->fax_mairie."<br/>"; 
            }     
            if($this->infosConfig()->site_web_mairie!=null){
                  $header.="".$this->infosConfig()->site_web_mairie."<br/> ";
            }       
            $header.="</div>
                    <div class='fixed-header-right'>
                    <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                    Union-Discipline-Travail<hr width='50'/></b> 
                    <p><i>RECEPISSE N° <b>".$recuInfo->id."</b></i></p>
                </div>";
        return $header;
    }
    public function contentMariage($id){
        $recu = Mariage::where([['mariages.deleted_at', NULL],['mariages.id',$id]])
                ->select('mariages.*',DB::raw('DATE_FORMAT(mariages.date_retrait, "%d-%m-%Y") as date_retraits'),DB::raw('DATE_FORMAT(mariages.date_declaration, "%d-%m-%Y") as date_declarations'),DB::raw('DATE_FORMAT(mariages.date_mariage, "%d-%m-%Y à %H:%i") as date_mariages'),DB::raw('DATE_FORMAT(mariages.date_dresser, "%d-%m-%Y") as date_dressers'))
                ->first();
        
        $content ="<div class='fixed-content'> 
                    <p style='text-align:center; font-size:25px;'><b><u>RECEPISSE DE DECLARATION DE MARIAGE</u></b></p>
                    <p><i> Mariage entre </i><b>".$recu->nom_complet_homme."</b> <i>et</i> <b>".$recu->nom_complet_femme." </b><i>le </i><b>".$recu->date_mariages."</b></p>
                    <p><i> Référence </i><b>".$recu->numero_acte_mariage." DU ".$recu->date_dressers."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Nombre de copies</i> <b>".$recu->nombre_copie."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Montant</i> <b>".number_format($recu->montant,0, ',', ' ')." F CFA</b></p>
                    <p><i> Déclarée le </i><b>".$recu->date_declarations."</b> <i>par</i> <b>".$recu->nom_complet_declarant."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Date de retrait </i> <b>".$recu->date_retraits."</b></p>
                </div>";
        return $content;
    }
    
    //Etats reçu de deces
    public function recuDeclarationDecesPdf($id){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadHTML($this->recuDeclarationDeces($id));
        return $pdf->stream('recu_'.$id.'_'.date('d/m/Y').'.pdf');
    }
    public function recuDeclarationDeces($id){
        $outPut = $this->headerDeces($id);
        $outPut.= $this->contentDeces($id);
        $outPut.= $this->footer();
        return $outPut;
    }
    
    public function headerDeces($id){
        $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        $commune = str_replace($search, $replace, $this->infosConfig()->commune);
        $recuInfo = Decede::find($id);
       $header = "<html>
        <head>
            <meta charset='utf-8'>
            <style>
               @page { size: 17cm 21cm landscape; }
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
                    margin:270px 0;
                    position: absolute;
                }
                .fixed-footer{
                    position: fixed; 
                    bottom: -28; 
                    float: right;
                    left: 0px; 
                    right: 0px;
                    height: 50px; 
                    text-align:center;
                }  
              
            </style>
            <body>
                <div class='fixed-header-left'>";
             $header.="COMMUNE ".strtoupper($this->premierLetre()."".$commune)."<hr width='50'/></b>
                    <img src=".$this->infosConfig()->logo." width='150' height='150'><br/> 
                    <b> Mairie ".$this->premierLetre()."".$this->infosConfig()->commune."</b><br/>";
            if($this->infosConfig()->adresse_marie!=null){
                 $header.="Adresse: ".$this->infosConfig()->adresse_marie."<br/>";
            }
            if($this->infosConfig()->telephone_mairie!=null){
                $header.="Tel : ".$this->infosConfig()->telephone_mairie."<br/>"; 
            }           
            if($this->infosConfig()->fax_mairie!=null){
                $header.="Fax : ".$this->infosConfig()->fax_mairie."<br/>"; 
            }     
            if($this->infosConfig()->site_web_mairie!=null){
                  $header.="".$this->infosConfig()->site_web_mairie."<br/> ";
            }       
            $header.="</div>
                    <div class='fixed-header-right'>
                    <b> REPUBLIQUE DE COTE D'IVOIRE<br/> 
                    Union-Discipline-Travail<hr width='50'/></b> 
                    <p><i>RECEPISSE N° <b>".$recuInfo->id."</b></i></p>
                </div>";
        return $header;
    }
    public function contentDeces($id){
        $recu = Decede::where([['decedes.deleted_at', NULL],['decedes.id',$id]]) 
                ->select('decedes.*')
                 ->first();
        $content ="<div class='fixed-content'> 
                    <p style='text-align:center; font-size:25px;'><b><u>RECEPISSE DE DECLARATION DE DECES</u></b></p>
                    <p><i> Décès de </i><b>".$recu->nom_complet_decede."</b> <i>le</i> <b>".date("d-m-Y", strtotime($recu->date_deces))."</b></p>
                    <p><i> Référence </i><b>".$recu->numero_acte_deces." DU ".date("d-m-Y", strtotime($recu->date_deces))."</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Nombre de copies</i> <b>".$recu->nombre_copie."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Montant</i> <b>".number_format($recu->montant_declaration,0, ',', ' ')." F CFA</b></p>
                    <p><i> Déclaré le </i><b>".date("d-m-Y", strtotime($recu->date_declaration))."</b> <i>par</i> <b>".$recu->nom_complet_declarant."</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Date de retrait </i> <b>".date("d-m-Y", strtotime($recu->date_retrait))."</b></p>
                </div>";
        return $content;
    }
    
    public function footer(){
        $footer =  '<div class="fixed-footer">
                        <i>Document établi le <b>'.date("d/m/Y").' </b></i>     
                    </div>
                </body></html>';
        return $footer;
    }
}
