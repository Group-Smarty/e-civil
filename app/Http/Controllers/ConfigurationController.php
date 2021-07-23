<?php

namespace App\Http\Controllers;

use App\Configuration;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $infoConfig = Configuration::find(1);
       $menuPrincipal = "Configuration";
       $titleControlleur = "Configuration des paramètres";
       $btnModalAjout = "FALSE";
       return view('configuration.index',compact('infoConfig', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
       $data = $request->all(); 
       
            $request->validate([
                    'commune' => 'required',
                    'nom_responsable' => 'required',
                    'contact_responsable' => 'required',
                    'service_responsable' => 'required',
                    'post_responsable' => 'required',
                    'logo' => 'mimes:jpeg,jpg,png,gif',
            ]);
                
                $configuration = new Configuration();
                $configuration->commune = $data['commune'];
                $configuration->nom_responsable = $data['nom_responsable'];
                $configuration->contact_responsable = $data['contact_responsable'];
                $configuration->service_responsable = $data['service_responsable'];
                $configuration->post_responsable = $data['post_responsable'];
                $configuration->fax_mairie = isset($data['fax_mairie']) && !empty($data['fax_mairie']) ? $data['fax_mairie']:null;
                $configuration->telephone_mairie = isset($data['telephone_mairie']) && !empty($data['telephone_mairie']) ? $data['telephone_mairie']:null;
                $configuration->site_web_mairie = isset($data['site_web_mairie']) && !empty($data['site_web_mairie']) ? $data['site_web_mairie']:null;
                $configuration->adresse_marie = isset($data['adresse_marie']) && !empty($data['adresse_marie']) ? $data['adresse_marie']:null;

                //Insertion de l'image du logo
                if(isset($data['logo']) && !empty($data['logo'])){
                    $logo = request()->file('logo');
                    $file_name = str_replace(' ', '_', $logo->getClientOriginalName());
                    $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
                    $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
                    $new_file_name = str_replace($search, $replace, $file_name);

                    $path = public_path().'/images/';
                    $logo->move($path,$new_file_name);
                    $configuration->logo = 'images/'.$new_file_name;
                }
         
                $configuration->created_by = Auth::user()->id;
                $configuration->save();
                return redirect()->route('configuration');
    }

    /**
     * Display the specified resource.
     *
     * @param  Configuration  $configuration
     * @return Response
     */
    public function show()
    {
       $configuration = Configuration::find(1);
       $menuPrincipal = "Configuration";
       $titleControlleur = "Modification des informations du paramètre";
       $btnModalAjout = "FALSE";
       return view('configuration.infos-update',compact('configuration', 'btnModalAjout', 'menuPrincipal', 'titleControlleur')); 
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Configuration  $configuration
     * @return Response
     */
    public function update(Request $request,Configuration  $configuration)
    {
        $Configuration = Configuration::find(1);
        if($Configuration){
            $data = $request->all(); 
           
            $request->validate([
                    'commune' => 'required',
                    'nom_responsable' => 'required',
                    'contact_responsable' => 'required',
                    'service_responsable' => 'required',
                    'post_responsable' => 'required',
                    'logo' => 'mimes:jpeg,jpg,png,gif',
            ]);
                
                $Configuration->commune = $data['commune'];
                $Configuration->nom_responsable = $data['nom_responsable'];
                $Configuration->contact_responsable = $data['contact_responsable'];
                $Configuration->service_responsable = $data['service_responsable'];
                $Configuration->post_responsable = $data['post_responsable'];
                $Configuration->fax_mairie = isset($data['fax_mairie']) && !empty($data['fax_mairie']) ? $data['fax_mairie']:null;
                $Configuration->telephone_mairie = isset($data['telephone_mairie']) && !empty($data['telephone_mairie']) ? $data['telephone_mairie']:null;
                $Configuration->site_web_mairie = isset($data['site_web_mairie']) && !empty($data['site_web_mairie']) ? $data['site_web_mairie']:null;
                $Configuration->adresse_marie = isset($data['adresse_marie']) && !empty($data['adresse_marie']) ? $data['adresse_marie']:null;
                 
                //Insertion de l'image du logo 
                if(isset($data['logo']) && !empty($data['logo'])){
                    $logo = request()->file('logo');
                    $file_name = str_replace(' ', '_', $logo->getClientOriginalName());
                    $search  = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
                    $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
                    $new_file_name = str_replace($search, $replace, $file_name);

                    $path = public_path().'/images/';
                    $logo->move($path,$new_file_name);
                    $Configuration->logo = 'images/'.$new_file_name;
                }
          
                $Configuration->updated_by = Auth::user()->id;
                $Configuration->save();
                return redirect()->route('configuration');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Configuration  $configuration
     * @return Response
     */
    public function destroy(Configuration $configuration)
    {
        //
    }
}
