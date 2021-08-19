<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/plateforme-e-civil', function () {
   return view('auth.login');
});

Route::get('/', 'SiteController@index');
Route::get('/demande-extrait-naissance', 'SiteController@demandeExtraitNaissance')->name('demande-extrait-naissance');
Route::get('/demande-extrait-deces', 'SiteController@demandeExtraiDeces')->name('demande-extrait-deces');
Route::get('/demande-extrait-mariage', 'SiteController@demandeExtraitMariage')->name('demande-extrait-mariage');
Route::post('/store-demande-en-ligne', 'SiteController@storeDemandeEnLigne')->name('store-demande-en-ligne');

Auth::routes(); 
Route::get('/confirmer_compte/{id}/{token}', 'Auth\RegisterController@confirmationCompte');
Route::post('/update_password', 'Auth\RegisterController@updatePassword')->name('update_password');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/configuration', 'ConfigurationController@index')->name('configuration')->middleware('auth');
Route::post('/configuration/store', 'ConfigurationController@store')->name('configuration.store')->middleware('auth');
Route::get('/configuration/infos-update', 'ConfigurationController@show')->name('configuration.infos-update')->middleware('auth');
Route::put('/configuration/update', 'ConfigurationController@update')->name('configuration.update')->middleware('auth');

//les routes du module Parametre 
Route::namespace('Parametre')->middleware('auth')->name('parametre.')->prefix('parametre')->group(function () {
    //Route resources 
    Route::resource('fonctions', 'FonctionController');
    Route::resource('type-contrats', 'TypeContratController');
    Route::resource('services', 'ServiceController');
    Route::resource('communes', 'CommuneController');
    Route::resource('type-pieces', 'TypePieceController');
    Route::resource('mode-travails', 'ModeTravailController');
    Route::resource('type-courriers', 'TypeCourrierController');
    Route::resource('type-societes', 'TypeSocieteController');
    Route::resource('secteurs', 'SecteurController');
    Route::resource('nations', 'NationController');
    Route::resource('regimes', 'RegimeController');
    
    
    //Route pour les listes dans boostrap table 
    Route::get('liste-fonctions', 'FonctionController@listeFonction')->name('liste-fonctions');
    Route::get('liste-type-contrats', 'TypeContratController@listeTypeContrat')->name('liste-type-contrats');
    Route::get('liste-services', 'ServiceController@listeService')->name('liste-services');
    Route::get('liste-communes', 'CommuneController@listeCommune')->name('liste-communes');
    Route::get('liste-type-pieces', 'TypePieceController@listeTypePiece')->name('liste-type-pieces');
    Route::get('liste-type-courriers', 'TypeCourrierController@listeTypeCourrier')->name('liste-type-courriers');
    Route::get('liste-type-societes', 'TypeSocieteController@listeTypeSociete')->name('liste-type-societes');
    Route::get('liste-secteurs', 'SecteurController@listeSecteur')->name('liste-secteurs');
    Route::get('liste-mode-travails', 'ModeTravailController@listeModeTravail')->name('liste-mode-travails');
    Route::get('liste-nations', 'NationController@listeNation')->name('liste-nations');
    Route::get('liste-regimes', 'RegimeController@listeRegime')->name('liste-regimes');
});

//les routes du module Recrutement 
Route::namespace('Recrutement')->middleware('auth')->name('recrutement.')->prefix('recrutement')->group(function () {
    //Route resources
    Route::resource('agents', 'AgentController');
    Route::resource('contrats', 'ContratController');
    
    //Route pour les listes dans boostrap table
    Route::get('liste-agents', 'AgentController@listeAgent')->name('liste-agents');
    Route::get('liste-contrats', 'ContratController@listeContrat')->name('liste-contrats');
    
    //Route particulier
      Route::post('update-contrat', 'ContratController@updateContrat')->name('update-contrat');

    //Liste routes parametrées 
      //**Agent
    Route::get('liste-agents-by-name/{name}', 'AgentController@listeAgentsByName');
    Route::get('liste-agents-by-service/{service}', 'AgentController@listeAgentsByService');
    Route::get('liste-agents-by-fonction/{fonction}', 'AgentController@listeAgentsByFonction');
    Route::get('liste-agents-by-sexe/{sexe}', 'AgentController@listeAgentsBySexe');
    Route::get('liste-agents-by-service-fonction/{service}/{fonction}', 'AgentController@listeAgentsByServiceFonction');
    Route::get('find-agent-by-id/{id}', 'AgentController@findOneAgent');
    Route::get('find-agent-by-id-for-contrat/{id}', 'AgentController@findOneAgentForContrat'); 
      //**Contrat 
    Route::get('liste-contrats-by-name/{name}', 'ContratController@listeContratsByName'); 
    Route::get('liste-contrats-by-type/{type}', 'ContratController@listeContratsByType'); 
    Route::get('liste-contrats-by-mode/{mode}', 'ContratController@listeContratsByModeTravail'); 
    Route::get('liste-contrats-by-sexe/{sexe}', 'ContratController@listeContratsBySexe'); 
});

//les routes du module Etat civil 
Route::namespace('Ecivil')->middleware('auth')->name('e-civil.')->prefix('e-civil')->group(function () {
    //Route resources
    Route::resource('declarations', 'DeclarationController');
    Route::resource('naissances', 'NaissanceController');
    Route::resource('demandes', 'DemandeController');
    Route::resource('mariages', 'MariageController');
    Route::resource('decedes', 'DecedeController');
    Route::resource('inhumations', 'InhumationController');
    Route::resource('certificat-vie', 'CertificatVieController');
    Route::resource('certificat-vie-entretien', 'CertificatVieEntretienController');
    Route::resource('enfant-en-charge', 'EnfantEnChargeController');
    Route::resource('non-inscritption-registres-deces', 'CertificatNonInscritptionRegistreController');
    Route::resource('certificat-non-remargiages', 'CertificatNonRemargiageController');
    Route::resource('certificat-infructueuses', 'CertificatRechercheInfructueuseController');
    Route::resource('certificat-concubinages', 'CertificatConcubinageController');
    Route::resource('certificat-non-divorces', 'CertificatNonDivorceController');
    Route::resource('certificat-non-naissances', 'CertificatNonNaissanceController');
    Route::resource('certificat-non-separation-corps', 'CertificatNonSeparationCorpsController');
    Route::resource('soit-transmis', 'SoitTransmiController');
    Route::resource('certificat-celibats', 'CertificatCelibatController');
    Route::resource('certificat-celebrations', 'CertficatCelebrationController');
    
    //Routes pour la gestion des contenus du website
    Route::get('demandes-recues', 'DemandeController@demandeRecue')->name('demandes-recues');
    Route::get('liste-demandes-recues', 'DemandeController@listeDemandeRecue')->name('liste-demandes-recues');

    //Route pour les listes dans boostrap table 
    Route::get('liste-declarations', 'DeclarationController@listeDeclaration')->name('liste-declarations');
    Route::get('liste-naissances', 'NaissanceController@listeNaissance')->name('liste-naissances');
    Route::get('liste-mariages', 'MariageController@listeMariage')->name('liste-mariages');
    Route::get('liste-deces', 'DecedeController@listeDeces')->name('liste-deces');
    Route::get('liste-inhumations', 'InhumationController@listeInhumations')->name('liste-inhumations');
    Route::get('liste-certificat-vie', 'CertificatVieController@listeCertificatVie')->name('liste-certificat-vie');
    Route::get('liste-certificat-vie-entretien', 'CertificatVieEntretienController@listeCertificatVieEntretien')->name('liste-certificat-vie-entretien');
    Route::get('liste-demandes-acte-naissance', 'DemandeController@listeDemandeActeNaissance')->name('liste-demandes-acte-naissance');
    Route::get('liste-demandes-acte-mariage', 'DemandeController@listeDemandeActeMariage')->name('liste-demandes-acte-mariage');
    Route::get('liste-demandes-acte-deces', 'DemandeController@listeDemandeActeDeces')->name('liste-demandes-acte-deces');
    Route::get('liste-deces-by-mois', 'DecedeController@listeDecesByMois')->name('liste-deces-by-mois');
    Route::get('liste-deces-by-lieu', 'DecedeController@listeDecesByLieux')->name('liste-deces-by-lieu');
    Route::get('liste-deces-by-motif', 'DecedeController@listeDecesByMotif')->name('liste-deces-by-motif');
    Route::get('liste-naissance-by-mois', 'NaissanceController@listeNaissanceByMois')->name('liste-naissance-by-mois');
    Route::get('liste-naissance-by-secteur', 'NaissanceController@listeNaissanceBySecteur')->name('liste-naissance-by-secteur');
    Route::get('liste-nouveaux-majeurs', 'NaissanceController@listeNouveauxMajeurs')->name('liste-nouveaux-majeurs');
    Route::get('liste-prochains-mariages', 'MariageController@listeProchainMariages')->name('liste-prochains-mariages');
    Route::get('liste-non-registres-deces', 'CertificatNonInscritptionRegistreController@listeCertificatNonInscritptionRegistre')->name('liste-non-registres-deces');
    Route::get('liste-certificat-non-remargiages', 'CertificatNonRemargiageController@listeCertificatNonRemargiage')->name('liste-certificat-non-remargiages');
    Route::get('liste-certificat-infructueuses', 'CertificatRechercheInfructueuseController@listeCertificatRechercheInfructueuse')->name('liste-certificat-infructueuses');
    Route::get('liste-certificat-concubinages', 'CertificatConcubinageController@listeCertificatConcubinage')->name('liste-certificat-concubinages');
    Route::get('liste-certificat-non-divorces', 'CertificatNonDivorceController@listeCertificatNonDivorce')->name('liste-certificat-non-divorces');
    Route::get('liste-certificat-non-naissances', 'CertificatNonNaissanceController@listeCertificatNonNaissance')->name('liste-certificat-non-naissances');
    Route::get('liste-certificat-non-separation-corps', 'CertificatNonSeparationCorpsController@listeCertificatNonSeparationCorps')->name('liste-certificat-non-separation-corps');
    Route::get('liste-soit-transmis', 'SoitTransmiController@listeSoitTransmi')->name('liste-soit-transmis');
    Route::get('liste-certificat-celibats', 'CertificatCelibatController@listeCertificatCelibat')->name('liste-certificat-celibats');
    Route::get('liste-certificat-celebrations', 'CertficatCelebrationController@listeCertificatCelebration')->name('liste-certificat-celebrations');
    
    //Routes particulier  
    Route::post('update-naissances', 'NaissanceController@updateNaissance')->name('update-naissance');
    Route::post('update-mariages', 'MariageController@updateMariage')->name('update-mariage');
    Route::post('update-decedes', 'DecedeController@updateDecede')->name('update-decede');
    Route::post('update-inhumations', 'InhumationController@updateInhumation')->name('update-inhumation');
    Route::get('demande-copie-acte-naissance', 'DemandeController@vueDemandeCopieActeNaissance')->name('demande-copie-acte-naissance');
    Route::get('demande-copie-acte-mariage', 'DemandeController@vueDemandeCopieActeMariage')->name('demande-copie-acte-mariage');
    Route::get('demande-copie-acte-deces', 'DemandeController@vueDemandeCopieActeDeces')->name('demande-copie-acte-deces');
    Route::get('deces-par-mois', 'DecedeController@vueDecesParMois')->name('deces-par-mois');
    Route::get('deces-par-lieu', 'DecedeController@vueDecesParLieu')->name('deces-par-lieu');
    Route::get('deces-par-motif', 'DecedeController@vueDecesParMotif')->name('deces-par-motif');
    Route::get('naissance-by-mois', 'NaissanceController@vueNaissanceByMois')->name('naissance-by-mois');
    Route::get('naissance-by-secteur', 'NaissanceController@vueNaissanceBySecteur')->name('naissance-by-secteur');
    Route::get('nouveaux-majeurs', 'NaissanceController@vueNouveauxMajeurs')->name('nouveaux-majeurs');
    Route::get('prochains-mariages', 'MariageController@vueProchainMariage')->name('prochains-mariages');
     
    //Liste routes parametrées 

        //***Naissance 
    Route::get('liste-naissances-by-acte/{numero_acte}', 'NaissanceController@listeNaissancesByActe');
    Route::get('liste-naissances-by-name/{name}', 'NaissanceController@listeNaissancesByName');
    Route::get('liste-naissances-by-date/{date}', 'NaissanceController@listeNaissancesByDate');
    Route::get('liste-naissances-by-sexe/{sexe}', 'NaissanceController@listeNaissancesBySexe');
    Route::get('find-acte-naissance-by-id/{id}', 'NaissanceController@findActeNaissanceById');
    Route::get('liste-naissance-by-mois-annee/{annee}', 'NaissanceController@listeNaissanceByMoisAnnee');
    Route::get('liste-naissance-by-secteur-annee/{annee}', 'NaissanceController@listeNaissanceBySecteurAnnee');
    Route::get('liste-nouveaux-majeurs-periode/{debut}/{fin}', 'NaissanceController@listeNouveauxMajeursPeriode');

        //**Mariage 
    Route::get('liste-mariages-by-names/{name}', 'MariageController@listeMariagesByNames');
    Route::get('liste-mariages-by-numero-acte/{numero}', 'MariageController@listeMariagesByNumeroActe');
    Route::get('liste-mariages-by-date/{date}', 'MariageController@listeMariagesByDate');
    Route::get('find-acte-mariage-by-id/{id}', 'MariageController@findActeMariageById');
    Route::get('liste-prochains-mariages-par-mois/{mois}', 'MariageController@listeProchainMariagesParMois')->name('liste-prochains-mariages-par-mois');

        //**Deces  
    Route::get('liste-deces-by-name/{name}', 'DecedeController@listeDecedeByName');
    Route::get('liste-deces-by-numero-acte/{numero}', 'DecedeController@listeDecedeByNumeroActe');
    Route::get('liste-deces-by-date/{date}', 'DecedeController@listeDecedeByDate');
    Route::get('find-acte-deces-by-id/{id}', 'DecedeController@findActeDecesById');
    Route::get('liste-deces-by-mois-annee/{annee}', 'DecedeController@listeDecesByMoisAnnee')->name('liste-deces-by-mois-annee');
    Route::get('liste-deces-by-lieu-periode/{debut}/{find}', 'DecedeController@listeDecesByLieuPeriode')->name('liste-deces-by-lieu-periode');
    Route::get('liste-deces-by-motif-periode/{debut}/{find}', 'DecedeController@listeDecesByMotifPeriode')->name('liste-deces-by-motif-periode');
   
        //**Demande  
        Route::get('liste-demandes-by-numero/{numero}/{ecran}', 'DemandeController@listeDemandesByNumero');
        Route::get('liste-demandes-by-numero-acte/{numero_acte}/{ecran}', 'DemandeController@listeDemandesByNumeroActe');
        Route::get('liste-demandes-by-name/{name}/{ecran}', 'DemandeController@listeDemandesByName');
        Route::get('liste-demandes-by-date/{date}/{ecran}', 'DemandeController@listeDemandesByDate');
        
        //**Inhumation  
        Route::get('liste-inhumations-by-name/{name}', 'InhumationController@listeInhumationsByName');
        Route::get('liste-inhumations-by-date/{date}', 'InhumationController@listeInhumationsByDate');
        
        //**Certificats
        Route::get('liste-certificat-vie-by-name/{name}', 'CertificatVieController@listeCertificatVieByName');
        Route::get('liste-certificat-vie-by-piece-identite/{numero}', 'CertificatVieController@listeCertificatVieByPieceIdentite');
        Route::get('liste-certificat-vie-by-date/{date}', 'CertificatVieController@listeCertificatVieByDate');
       
        Route::get('liste-certificat-vie-entretien-by-name/{name}', 'CertificatVieEntretienController@listeCertificatVieEntretienByName');
        Route::get('liste-certificat-vie-entretien-by-piece-identite/{numero}', 'CertificatVieEntretienController@listeCertificatVieEntretienByPieceIdentite');
        Route::get('liste-certificat-vie-entretien-by-date/{date}', 'CertificatVieEntretienController@listeCertificatVieEntretienByDate');
        
        Route::get('liste-certificat-non-remargiage-by-name/{name}', 'CertificatNonRemargiageController@listeCertificatNonRemargiageByName');
        Route::get('liste-certificat-non-remargiages-by-piece-identite/{piece_identite}', 'CertificatNonRemargiageController@listeCertificatNonRemargiageByPiece');
        Route::get('liste-certificat-non-remargiages-by-date/{date}', 'CertificatNonRemargiageController@listeCertificatNonRemargiageByDate');
         
        Route::get('liste-non-registres-deces-by-name/{name}', 'CertificatNonInscritptionRegistreController@listeCertificatNonInscritptionRegistreByName');
        Route::get('liste-non-registres-deces-by-piece-identite/{piece_identite}', 'CertificatNonInscritptionRegistreController@listeCertificatNonInscritptionRegistreByPiece');
        Route::get('liste-non-registres-deces-by-date/{date}', 'CertificatNonInscritptionRegistreController@listeCertificatNonInscritptionRegistreByDate');
      
        Route::get('liste-certificat-infructueuses-by-name/{name}', 'CertificatRechercheInfructueuseController@listeCertificatRechercheInfructueuseByName');
        Route::get('liste-certificat-infructueuses-by-piece-identite/{piece_identite}', 'CertificatRechercheInfructueuseController@listeCertificatRechercheInfructueuseByPiece');
        Route::get('liste-certificat-infructueuses-by-date/{date}', 'CertificatRechercheInfructueuseController@listeCertificatRechercheInfructueuseByDate');
      
        Route::get('liste-certificat-concubinage-by-name/{name}', 'CertificatConcubinageController@listeCertificatConcubinageByName');
        Route::get('liste-certificat-concubinage-by-date/{date}', 'CertificatConcubinageController@listeCertificatConcubinageByDate');
      
        Route::get('liste-certificat-non-divorces-by-name/{name}', 'CertificatNonDivorceController@listeCertificatNonDivorceByName');
        Route::get('liste-certificat-non-divorces-by-date/{date}', 'CertificatNonDivorceController@listeCertificatNonDivorceByDate');
      
        Route::get('liste-certificat-non-naissances-by-name/{name}', 'CertificatNonNaissanceController@listeCertificatNonNaissanceByName');
        Route::get('liste-certificat-non-naissances-by-date/{date}', 'CertificatNonNaissanceController@listeCertificatNonNaissanceByDate');
      
        Route::get('liste-certificat-non-separation-corps-by-name/{name}', 'CertificatNonSeparationCorpsController@listeCertificatNonSeparationCorpsByName');
        Route::get('liste-certificat-non-separation-corps-by-date/{date}', 'CertificatNonSeparationCorpsController@listeCertificatNonSeparationCorpsByDate');
        
        Route::get('liste-soit-transmis-by-numero-acte/{numero_acte}', 'SoitTransmiController@listeSoitTransmiByNumeroActe');
        Route::get('liste-soit-transmis-by-nom/{nom}', 'SoitTransmiController@listeSoitTransmiByNom');
        Route::get('liste-soit-transmis-by-date/{date}', 'SoitTransmiController@listeSoitTransmiByDate');
      
        Route::get('liste-certificat-celibats-by-nom/{nom}', 'CertificatCelibatController@listeCertificatCelibatByNom');
        Route::get('liste-certificat-celibats-by-date/{date}', 'CertificatCelibatController@listeCertificatCelibatByDate');
        
        Route::get('liste-certificat-celebrations-by-nom/{nom}', 'CertficatCelebrationController@listeCertificatCelebrationByNom');
        Route::get('liste-certificat-celebrations-by-date/{date}', 'CertficatCelebrationController@listeCertificatCelebrationByDate');
        
        //**Enfant en charge 
        Route::get('liste-enfants-en-charge/{id_certificat_vie}', 'EnfantEnChargeController@listeEnfantsEnCharge');
        
        //**Etat 
        Route::get('recu-declaration-naissance/{id}', 'DeclarationController@recuDeclarationNaissancePdf');
        Route::get('recu-declaration-mariage/{id}', 'DeclarationController@recuDeclarationMariagePdf');
        Route::get('recu-declaration-deces/{id}', 'DeclarationController@recuDeclarationDecesPdf');
        Route::get('extrait-declaration-naissance/{id}', 'NaissanceController@extraitDeclarationNaissancePdf');
        Route::get('extrait-declaration-mariage/{id}', 'MariageController@extraitDeclarationMariagePdf');
        Route::get('extrait-declaration-deces/{id}', 'DecedeController@extraitDeclarationDecesPdf');
        Route::get('fiche-certificat-vie/{id}', 'CertificatVieController@ficheCertificatViePdf');
        Route::get('fiche-certificat-vie-entretien/{id}', 'CertificatVieEntretienController@ficheCertificatVieEntretienPdf');
        Route::get('recu-demande-copie-naissance/{id}', 'DemandeController@recuDemandeCopieNaissancePdf');
        Route::get('recu-demande-copie-mariage/{id}', 'DemandeController@recuDemandeCopieMariagePdf');
        Route::get('recu-demande-copie-deces/{id}', 'DemandeController@recuDemandeCopieDecesPdf');
        Route::get('fiche-deces-lieux', 'DecedeController@ficheDecesLieuxPdf')->name('fiche-deces-lieux');
        Route::get('fiche-deces-motif', 'DecedeController@ficheDecesMotifPdf')->name('fiche-deces-motif');
        Route::get('fiche-deces-par-an', 'DecedeController@ficheDecesParAnPdf')->name('fiche-deces-par-an');
        Route::get('fiche-deces-par-mois', 'DecedeController@ficheDecesParMoisPdf')->name('fiche-deces-par-mois');
        Route::get('fiche-deces-par-mois-annee/{annee}', 'DecedeController@ficheDecesParMoisAnneePdf')->name('fiche-deces-par-mois-annee');
        Route::get('fiche-naissance-par-annnee', 'NaissanceController@ficheNaissanceParAnnneePdf')->name('fiche-naissance-par-annnee');
        Route::get('fiche-naissance-par-mois', 'NaissanceController@ficheNaissanceParMoisPdf')->name('fiche-naissance-par-mois');
        Route::get('fiche-naissance-par-mois-annnee/{annee}', 'NaissanceController@ficheNaissanceParMoisAnnneePdf')->name('fiche-naissance-par-mois-annnee');
        Route::get('fiche-naissance-par-secteur', 'NaissanceController@ficheNaissanceParSecteurPdf')->name('fiche-naissance-par-secteur');
        Route::get('fiche-naissance-par-secteur-annnee/{annee}', 'NaissanceController@ficheNaissanceParSecteurAnnneePdf')->name('fiche-naissance-par-secteur-annnee');
        Route::get('fiche-prochains-mariages', 'MariageController@ficheProchainsMariagesPdf')->name('fiche-prochains-mariages');
        Route::get('fiche-prochains-mariages-par-mois/{mois}', 'MariageController@ficheProchainsMariagesParMoisPdf')->name('fiche-prochains-mariages-par-mois');
        Route::get('fiche-sans-demandes', 'DemandeController@ficheSansDemandePdf')->name('fiche-sans-demandes');
        Route::get('fiche-nouveaux-majeurs', 'NaissanceController@ficheNouveauxMajeursPdf')->name('fiche-nouveaux-majeurs');
        Route::get('fiche-deces-par-lieu-periode/{debut}/{fin}', 'DecedeController@ficheDecesParLieuPeriodePdf')->name('fiche-deces-par-lieu-periode');
        Route::get('fiche-nouveaux-majeurs-periode/{debut}/{fin}', 'NaissanceController@ficheNouveauxMajeursPeriodePdf')->name('fiche-nouveaux-majeurs-periode');
        Route::get('fiche-deces-par-motif-periode/{debut}/{fin}', 'DecedeController@ficheDecesParMotifPeriodePdf')->name('fiche-deces-par-motif-periode');
        Route::get('extrait-copie-integrale/{id}', 'NaissanceController@extraitCopieIntegralePdf');
        Route::get('extrait-declaration-copie-integrale/{id}', 'DecedeController@extraitCopieIntegralePdf');
        Route::get('extrait-mariage-copie-integrale/{id}', 'MariageController@extraitCopieIntegralePdf');
        Route::get('certificat-non-mariage-pdf/{id}', 'CertificatNonRemargiageController@certificatNonRemariagePdf');
        Route::get('certificat-non-inscritption-registre-pdf/{id}', 'CertificatNonInscritptionRegistreController@certificatNonInscritptionRegistrePdf');
        Route::get('certificat-recherche-infructueuse-pdf/{id}', 'CertificatRechercheInfructueuseController@certificatRechercheInfructueusePdf');
        Route::get('certificat-concubinages-pdf/{id}', 'CertificatConcubinageController@certificatConcubinagePdf');
        Route::get('certificat-non-divorces-pdf/{id}', 'CertificatNonDivorceController@certificatNonDivorcePdf');
        Route::get('certificat-non-naissances-pdf/{id}', 'CertificatNonNaissanceController@certificatNonNaissancePdf');
        Route::get('certificat-non-separation-corps-pdf/{id}', 'CertificatNonSeparationCorpsController@certificatNonSeparationCorpsPdf');
        Route::get('fiche-soit-transmis-pdf/{id}', 'SoitTransmiController@ficheSoitTransmisPdf');
        Route::get('fiche-certificat-celibats-pdf/{id}', 'CertificatCelibatController@ficheCertificatCelibatPdf');
        Route::get('fiche-certificat-celebration-pdf/{id}', 'CertficatCelebrationController@ficheCertificatCelebrationPdf');
});

//les routes du module Recrutement 
Route::namespace('Etat')->middleware('auth')->name('etat.')->prefix('etat')->group(function () {
    //Route pour les vues Etat
    Route::get('etat-naissances', 'EtatController@vueEtatNaissance')->name('etat-naissances');
    Route::get('etat-deces', 'EtatController@vueEtatDeces')->name('etat-deces');
    Route::get('etat-mariages', 'EtatController@vueEtatMariage')->name('etat-mariages');
    
    //Liste dans boostrap table 
    Route::get('liste-naissances', 'EtatController@listeNaissance')->name('liste-naissances');
    Route::get('liste-mariages', 'EtatController@listeMariage')->name('liste-mariages');
    Route::get('liste-deces', 'EtatController@listeDeces')->name('liste-deces');
    
    //Routes parametrées 
        //Naissances
    Route::get('liste-naissances-by-periode/{debut}/{fin}', 'EtatController@listeNaissanceByPeriode');
    Route::get('liste-naissances-by-sexe/{sexe}', 'EtatController@listeNaissanceBySexe');
    Route::get('liste-naissances-by-sexe-periode/{debut}/{fin}/{sexe}', 'EtatController@listeNaissanceByPeriodeSexe');
        //Mariages
    Route::get('liste-mariages-by-periode/{debut}/{fin}', 'EtatController@listeMariageByPeriode');
    Route::get('liste-mariages-by-regime/{regime}', 'EtatController@listeMariageByRegime');
    Route::get('liste-mariages-by-regime-periode/{debut}/{fin}/{regime}', 'EtatController@listeMariageByRegimePeriode');
        //Décès
    Route::get('liste-deces-by-periode/{debut}/{fin}', 'EtatController@listeDecesByPeriode');
    Route::get('liste-deces-by-sexe/{sexe}', 'EtatController@listeDecesBySexe');
    Route::get('liste-deces-by-sexe-periode/{debut}/{fin}/{sexe}', 'EtatController@listeDecesByPeriodeSexe');
    
    //*** Etats ***// 
        //Naissance
    Route::get('liste-naissances-pdf', 'EtatController@listeNaissancePdf');
    Route::get('liste-naissances-by-periode-pdf/{debut}/{fin}', 'EtatController@listeNaissanceByPeriodePdf');
    Route::get('liste-naissances-by-sexe-pdf/{sexe}', 'EtatController@listeNaissanceBySexePdf');
    Route::get('liste-naissances-by-sexe-periode-pdf/{debut}/{fin}/{sexe}', 'EtatController@listeNaissanceBySexePeriodePdf');
         //Mariage
    Route::get('liste-mariages-pdf', 'EtatController@listeMariagePdf');
    Route::get('liste-mariages-by-periode-pdf/{debut}/{fin}', 'EtatController@listeMariageByPeriodePdf');
    Route::get('liste-mariages-by-regime-pdf/{regime}', 'EtatController@listeMariageByRegimePdf');
    Route::get('liste-mariages-by-regime-periode-pdf/{debut}/{fin}/{regime}', 'EtatController@listeMariageByRegimePeriodePdf');
        //Décès
    Route::get('liste-deces-pdf', 'EtatController@listeDecesPdf');
    Route::get('liste-deces-by-periode-pdf/{debut}/{fin}', 'EtatController@listeDecesByPeriodePdf');
    Route::get('liste-deces-by-sexe-pdf/{sexe}', 'EtatController@listeDecesBySexePdf');
    Route::get('liste-deces-by-sexe-periode-pdf/{debut}/{fin}/{sexe}', 'EtatController@listeDecesBySexePeriodePdf');
});

//les routes du module Courrier 
Route::namespace('Courrier')->middleware('auth')->name('courrier.')->prefix('courrier')->group(function () {
    //Route resources
    Route::resource('annuaires', 'AnnuaireController');
    Route::resource('courriers', 'CourrierController');
    
    //Route pour les vues
    Route::get('courriers-emis', 'CourrierController@vueCourrierEmis')->name('courriers-emis');
    Route::get('courriers-recus', 'CourrierController@vueCourrierRecu')->name('courriers-recus');
    
    //Routes particulier  
    Route::post('update-courriers', 'CourrierController@updateCourrier')->name('update-courrier');
    Route::get('liste-annuaires-last', 'AnnuaireController@listeAnnuaireLast');
    
    //Route pour les listes dans boostrap table 
    Route::get('liste-annuaires', 'AnnuaireController@listeAnnuaire')->name('liste-annuaires');
    Route::get('liste-courriers', 'CourrierController@listeCourriers')->name('liste-courriers');
    Route::get('liste-courriers-emis', 'CourrierController@listeCourrierEmis')->name('liste-courriers-emis');
    Route::get('liste-courriers-recus', 'CourrierController@listeCourrierRecus')->name('liste-courriers-recus');
    
    //Routes parametrées 
    Route::get('find-annuaire-by-id/{id}', 'AnnuaireController@findAnnuaireById');
    Route::get('liste-annuaires-by-name/{name}', 'AnnuaireController@listeAnnuaireByName');
    Route::get('liste-annuaires-by-secteur/{secteur}', 'AnnuaireController@listeAnnuaireBySecteur');
    Route::get('liste-annuaires-by-type-societe/{type}', 'AnnuaireController@listeAnnuaireByTypeSociete');
    Route::get('liste-annuaires-by-contact/{contact}', 'AnnuaireController@listeAnnuaireByContact');
    Route::get('liste-courriers-by-date/{contact}/{ecran}', 'CourrierController@listeCourrierByDate');
    Route::get('liste-courriers-by-objet/{objet}/{ecran}', 'CourrierController@listeCourrierByObjet');
    Route::get('liste-courriers-by-societe/{societe}/{ecran}', 'CourrierController@listeCourrierBySociete');
    Route::get('liste-courriers-by-type/{type}/{ecran}', 'CourrierController@listeCourrierByType');
    Route::get('liste-courriers-by-service/{service}', 'CourrierController@listeCourrierByService');
});

//les routes du module Taxe 
Route::namespace('Taxe')->middleware('auth')->name('taxe.')->prefix('taxe')->group(function () {
    //Route resources
    Route::resource('type-taxes', 'TypeTaxeController');
    Route::resource('localites', 'LocaliteController');
    Route::resource('contribuables', 'ContribuableController');
    Route::resource('declaration-activites', 'DeclarationActiviteController');
    Route::resource('payement-taxes', 'PayementTaxeController');
    Route::resource('timbres', 'TimbreController');
    Route::resource('caisses', 'CaisseController');
    Route::resource('caisse-ouverte', 'CaisseOuverteController');
    Route::resource('billetages', 'BilletageController');
    
    //Route pour les listes dans boostrap table 
    Route::get('liste-type-taxes', 'TypeTaxeController@listeTypeTaxe')->name('liste-type-taxes');
    Route::get('liste-localites', 'LocaliteController@listeLocalite')->name('liste-localites');
    Route::get('liste-contribuables', 'ContribuableController@listeContribuable')->name('liste-contribuables');
    Route::get('liste-declaration-activites', 'DeclarationActiviteController@listeDeclarationActivite')->name('liste-declaration-activites');
    Route::get('liste-caisses', 'CaisseController@listeCaisse')->name('liste-caisses');
    Route::get('liste-timbres', 'TimbreController@listeTimbre')->name('liste-timbres');
    Route::get('liste-caisses-ouvertes', 'CaisseOuverteController@listeCaisseOuverte')->name('liste-caisses-ouvertes');
    Route::get('liste-payements-taxes/{caisse}', 'PayementTaxeController@listPayementTaxe')->name('liste-payements-taxes');
    Route::get('liste-billetages', 'BilletageController@listBilletage')->name('liste-billetages');
    
    //Route particulière  
    Route::post('point-caisse', 'PayementTaxeController@pointCaisse')->name('point-caisse');
    Route::get('point-caisse-caissier', 'PayementTaxeController@pointCaisseCaissier')->name('point-caisse-caissier');
    Route::post('femeture-caisse', 'CaisseOuverteController@femetureCaisse')->name('femeture-caisse');
    
    //Route particulière 
    Route::get('details-contribuables/{id}', 'ContribuableController@vueDetail');
    Route::get('historique-taxes', 'PayementTaxeController@historiqueTaxe')->name('historique-taxes');
    Route::get('liste-taxes-payes', 'PayementTaxeController@listeTaxesPayes');
    
    //Liste routes parametrées 
    
       //**Caisses et Caisse ouverte
    Route::get('liste-caisses-fermees', 'CaisseController@listeCaissesFermees');
    Route::get('find-caisse-by-id/{id}', 'CaisseController@findCaisseById');
    Route::get('get-caisse-ouverte/{caisse}', 'CaisseOuverteController@getCaisseOuverte');
    Route::get('billetage-pdf/{caisse_ouverte}', 'BilletageController@billetagePdf');
    
      //**Contribuable 
    Route::get('liste-contribuables-by-name/{name}', 'ContribuableController@listeContribuableByName');
    Route::get('liste-contribuables-by-sexe/{sexe}', 'ContribuableController@listeContribuableBySexe');
    Route::get('liste-contribuables-by-nation/{nation}', 'ContribuableController@listeContribuableByNation');
    Route::get('liste-contribuables-by-numero/{numero}', 'ContribuableController@listeContribuableByNumero');
    Route::get('gett-all-payements-taxes/{contribuable}', 'ContribuableController@getAllPayementTaxe');
    
    //**Déclaration activité 
    Route::get('liste-activites-by-contribuable/{contribuable}', 'DeclarationActiviteController@listeDeclarationActiviteByContribuable');
    Route::get('liste-activites-by-date/{date}', 'DeclarationActiviteController@listeDeclarationActiviteByDate');
    Route::get('liste-activites-by-localite/{localite}', 'DeclarationActiviteController@listeDeclarationActiviteByLocalite');
    Route::get('liste-activites-by-numero/{numero}', 'DeclarationActiviteController@listeDeclarationActiviteByNumero');
    Route::get('liste-activites-by-localite-contribuable/{localite}/{contribuable}', 'DeclarationActiviteController@listeDeclarationActiviteByLocaliteContribuable');
    Route::get('get-activite-by-id/{id}', 'DeclarationActiviteController@listeDeclarationActiviteById');
    Route::get('get-contribuable-by-activite/{activite}', 'ContribuableController@getContribuableByActivite');
    
    //**Payement taxe 
    Route::get('liste-payements-taxes-by-facture/{numero}/{caisse?}', 'PayementTaxeController@listPayementTaxeByFacture');
    Route::get('liste-payements-taxes-by-contribuable/{contribuable}/{caisse?}', 'PayementTaxeController@listePayementTaxeByContribuable');
    Route::get('liste-payements-taxes-by-periode/{debut}/{fin}', 'PayementTaxeController@listePayementTaxeByPeriode');
    Route::get('liste-payements-taxes-by-contribuable-periode/{contribuable}/{debut}/{fin}', 'PayementTaxeController@listePayementTaxeByContribuablePeriode');
    Route::get('facture-pdf/{id}', 'PayementTaxeController@facturePdf');
    
    //**Billetages
    Route::get('liste-billetages-by-caisse/{caisse}', 'BilletageController@listeBilletageByCaisse');
    Route::get('liste-billetages-by-caissier/{caissier}', 'BilletageController@listeBilletageByCaissier');


    //Etats//

    //* Contribuables *//
    Route::get('liste-contribuables-pdf', 'ContribuableController@listeContribuablePdf');
    Route::get('liste-contribuables-by-nation-pdf/{nation}', 'ContribuableController@listeContribuableByNationPdf');
    Route::get('liste-contribuables-by-sexe-pdf/{sexe}', 'ContribuableController@listeContribuableBySexePdf');

    //* Activité déclarées *//
    Route::get('liste-activites-pdf', 'DeclarationActiviteController@listeActivitePdf');
    Route::get('liste-activites-by-date-pdf/{date}', 'DeclarationActiviteController@listeActiviteByDatePdf');
    Route::get('liste-activites-by-contribuables-pdf/{contribuable}', 'DeclarationActiviteController@listeActiviteByContribuablePdf');
    Route::get('liste-activites-by-localites-pdf/{localite}', 'DeclarationActiviteController@listeActiviteByLocalitePdf');
    Route::get('liste-activites-by-contribuable-localite-pdf/{contribuable}/{localite}', 'DeclarationActiviteController@listeActiviteByContribuableLocalitePdf');

    //* Historique des caisses *//
    //Route::get('liste-caisses-pdf', 'DeclarationActiviteController@listeCaissePdf');

    //* Taxes *//
     //Route::get('liste-caisses-pdf', 'DeclarationActiviteController@listeCaissePdf');
});


//les routes du module Auth 
Route::namespace('Auth')->middleware('auth')->name('auth.')->prefix('auth')->group(function () {
    //Route resources
    Route::resource('users', 'UserController');
    Route::resource('restaurages', 'RestaurageDataController');
    
    //Route pour les listes dans boostrap table
    Route::get('liste-users', 'UserController@listeUser')->name('liste-users');
    Route::get('liste_all_tables', 'RestaurageDataController@listeAllTable')->name('liste_all_tables');
    
    //Routes pour le profil
    Route::get('profil-informations', 'UserController@profil')->name('profil-informations');
    Route::get('infos-profil-to-update', 'UserController@infosProfiTolUpdate')->name('infos-profil-to-update');
    Route::put('update-profil/{id}', 'UserController@updateProfil');
    Route::get('update-password-page', 'UserController@updatePasswordPage');
    Route::post('update-password', 'UserController@updatePasswordProfil')->name('update-password');

    //Réinitialisation du mot de passe manuellement par l'administrateur 
    Route::delete('/reset-password-manualy/{user}', 'UserController@resetPasswordManualy');
    
    //Routes avec parametre 
    Route::get('one_table/{table}', 'RestaurageDataController@oneTable');
    Route::get('liste_content_one_table/{table}', 'RestaurageDataController@listeContentOneTable')->name('liste_content_one_table');
    Route::post('restaurage', 'RestaurageDataController@restaurage')->name('restaurage');
});


