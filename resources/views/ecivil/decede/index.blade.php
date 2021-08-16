@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Operatrice')
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
<script src="{{asset('assets/js/underscore-min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
<script src="{{asset('assets/js/fonction_crude.js')}}"></script>
<script src="{{asset('assets/js/jquery.number.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{asset('assets/plugins/Bootstrap-form-helpers/js/bootstrap-formhelpers-phone.js')}}"></script>
<script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByName" placeholder="Rechercher par nom">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByActe" placeholder="Rechercher par N° de l'acte de décès">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByDate" placeholder="Rechercher par date de décès">
    </div>
</div>

<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-deces'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="true">
    <thead>
        <tr>
            <th data-field="id" data-formatter="recuFormatter" data-width="50px" data-align="center">Re&ccedil;u</th>
            <th data-formatter="numeroActeDecesFormatter">N° de l'acte  </th>
            <th data-formatter="dateDecesFormatter">Date du d&eacute;c&egrave;s </th>
            <th data-field="nom_complet_decede">Nom complet</th>
            <th data-field="sexe">Sexe</th>
            <th data-field="fonction.libelle_fonction">Profession </th>
            <th data-field="lieu_deces">Lieu du d&eacute;c&egrave;s </th>
            <th data-field="nom_complet_pere" data-visible="false">P&egrave;re</th>
            <th data-field="nom_complet_mere" data-visible="false">M&egrave;re</th>
            <th data-field="nombre_copie" data-align="center" data-visible="false">Copie</th>
            <th data-field="montant_declaration" data-formatter="montantFormatter" data-align="center" data-visible="false">Montant</th>
            <th data-field="date_retraits">Date retrait</th>
            <th data-field="motif_deces" data-visible="false">Motif</th>
            <th data-formatter="imageFormatter" data-visible="false" data-align="center">PV du m&eacute;decin</th>
            <th data-field="id" data-formatter="optionFormatter" data-width="120px" data-align="center"><i class="fa fa-wrench"></i></th>
        </tr>
    </thead>
</table>

<!-- Modal ajout et modification -->
<div class="modal fade bs-modal-ajout" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 75%">
        <form id="formAjout" ng-controller="formAjoutCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-times fa-2x"></i>
                        Gestion des d&eacute;clarations des d&eacute;c&egrave;s
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" name="idDecede" ng-hide="true" ng-model="decede.id"/>
                    @csrf
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#declaration_info" data-toggle="tab" aria-expanded="true">D&eacute;claration</a>
                            </li>
                            <li class="">
                                <a href="#autres_info" data-toggle="tab" aria-expanded="true">Autres informations</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="declaration_info">
                                <div class="row"> 
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Registre *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" ng-model="decede.registre" id="registre" name="registre" value="<?= date('Y'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>N° de l'acte du d&eacute;c&egrave;s *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-edit"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="decede.numero_acte_deces" id="numero_acte_deces" name="numero_acte_deces" placeholder="N° acte décès" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Date du dresser *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="decede.date_dressers" id="date_dresser" name="date_dresser" placeholder="01-01-1994" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Date du d&eacute;c&egrave;s *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="decede.date_decess" id="date_deces" name="date_deces" placeholder="Date du décès" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Heure du d&eacute;c&egrave;s </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-hourglass"></i>
                                                </div>
                                                <input type="time" class="form-control" ng-model="decede.heure_deces" id="heure_deces" name="heure_deces" placeholder="Heure du décès">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Lieu du d&eacute;c&egrave;s </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.lieu_deces" id="lieu_deces" name="lieu_deces" placeholder="Lieu du décès">
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nom complet du d&eacute;funt *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.nom_complet_decede" id="nom_complet_decede" name="nom_complet_decede" placeholder="Nom et prénom(s) du défunt" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Sexe *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-genderless"></i>
                                                </div>
                                                <select id="sexe" name="sexe" ng-model="decede.sexe" ng-init="decede.sexe='Masculin'" class="form-control" required>
                                                    <option value="Masculin">Masculin</option>
                                                    <option value="Feminin">Feminin</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Profession du d&eacute;funt </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-circle-o"></i>
                                                </div>
                                                <select name="fonction_id" id="fonction_id" class="form-control">
                                                    <option value="">-- Selectionner la profession --</option>
                                                    @foreach($fonctions as $fonction)
                                                    <option value="{{$fonction->id}}"> {{$fonction->libelle_fonction}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Adresse du domicile</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.adresse_decede" id="adresse_decede" name="adresse_decede" placeholder="Adresse du domicile du défunt">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nationalit&eacute; </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map"></i>
                                                </div>
                                                <select name="nationalite" id="nationalite" class="form-control">
                                                    <option value="" >-- Selectionner le pays --</option>
                                                    @foreach($nations as $nation)
                                                    <option value="{{$nation->id}}"> {{$nation->libelle_nation}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>N° d'acte de naissance du d&eacute;funt</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list"></i>
                                                </div>
                                                <input type="text" id="numero_acte_naissance_decede" class="form-control" ng-model="decede.numero_acte_naissance_decede" name="numero_acte_naissance_decede" placeholder="N° acte naissance du défunt"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date de naissance du d&eacute;funt </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="decede.date_naissance_decedes" id="date_naissance_decede" name="date_naissance_decede" placeholder="Ex : 01-01-1994">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Lieu de naissance </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.lieu_naissance_decede" id="lieu_naissance_decede" name="lieu_naissance_decede" placeholder="Lieu de naissance du défunt">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Date d&eacute;claration *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="decede.date_declarations" id="date_declaration" name="date_declaration" placeholder="Ex: 01-01-1994" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Montant d&eacute;claration </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-money"></i>
                                                </div>
                                                <input type="text" pattern="[0-9]*"  class="form-control" ng-model="decede.montant_declaration" id="montant_declaration" name="montant_declaration" placeholder="Ex: 500">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Nbre de copies </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-copy"></i>
                                                </div>
                                                <input type="number" min="1" class="form-control" ng-model="decede.nombre_copie" id="nombre_copie" name="nombre_copie" placeholder="Ex: 2">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Date de retrait *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="decede.date_retraits" id="date_retrait" name="date_retrait" placeholder="Ex: 01-01-1994" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Adresse du d&eacute;clarant</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.adresse_declarant" id="adresse_declarant" name="adresse_declarant" placeholder="Adresse du domicile">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>D&eacute;clarant (Nom complet )*</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.nom_complet_declarant" id="nom_complet_declarant" name="nom_complet_declarant" placeholder="Nom et prénom(s) du déclarant" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Contact du d&eacute;clarant </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-mobile-phone"></i>
                                                </div>
                                                <input type="text" class="form-control bfh-phone" ng-model="decede.contact_declarant" id="contact_declarant" name="contact_declarant" data-format="(dd) dd-dd-dd-dd" placeholder="Numéro mobile">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date de naissance du d&eacute;clarant</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="decede.date_naissance_declarants" id="date_naissance_declarant" name="date_naissance_declarant" placeholder="Date de naissance du déclarant">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fonction du d&eacute;clarant</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-circle-o"></i>
                                                </div>
                                                <select name="fonction_declarant" id="fonction_declarant" class="form-control">
                                                    <option value="">-- Selectionner la fonction --</option>
                                                    @foreach($fonctions as $fonction_declarant)
                                                    <option value="{{$fonction_declarant->id}}"> {{$fonction_declarant->libelle_fonction}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="autres_info">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nom complet du p&egrave;re </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-male"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.nom_complet_pere" id="nom_complet_pere" name="nom_complet_pere" placeholder="Nom et prénom(s) du père">
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Adresse du p&egrave;re </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.adresse_pere" id="adresse_pere" name="adresse_pere" placeholder="Adresse du père">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nom complet de la m&egrave;re </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-female"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.nom_complet_mere" id="nom_complet_mere" name="nom_complet_mere" placeholder="Nom et prénom(s) de la mère">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Adresse de la m&egrave;re </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.adresse_mere" id="adresse_mere" name="adresse_mere" placeholder="Adresse de la mère">
                                            </div>
                                        </div>
                                    </div> 
                                </div> 
                                <div class="row"> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Motif du d&eacute;c&egrave;s </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-text-height"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.motif_deces" id="motif_deces" name="motif_deces" placeholder="Motif du décès">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>PV du m&eacute;decin ou de la police </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-file"></i>
                                                </div>
                                                <input type="file" class="form-control" name="scanne_pv">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Langue de r&eacute;ception </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.langue_reception" id="langue_reception" name="langue_reception" placeholder="Langue de réception">
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Traducteur</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.traducteur" id="traducteur" name="traducteur" placeholder="Nom du traducteur">
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Dress&eacute; par </label>
                                                <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="decede.dressant" id="dressant" name="dressant" placeholder="Par nous...." autocomplete="on"></textarea>
                                        </div>
                                    </div> 
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-send"><span class="overlay loader-overlay"> <i class="fa fa-refresh fa-spin"></i> </span>Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal déclarant -->
<div class="modal fade bs-modal-declarant" id="formDeclarant" ng-controller="formDeclarantCtrl" category="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-orange">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Informations du d&eacute;clarant
            </div>
            @csrf
            <div class="modal-body ">
                <ul class="nav nav-stacked">
                    <li><a>Nom et pr&eacute;nom(s) : &nbsp;&nbsp;<b>@{{decede.nom_complet_declarant}}</b></a></li>
                    <li><a>Date de d&eacute;claration : &nbsp;&nbsp;<b>@{{decede.date_declarations}}</b></a></li>
                    <li><a>Contact : &nbsp;&nbsp;<b>@{{decede.contact_declarant}}</b></a></li>
                    <li><a>Adresse : &nbsp;&nbsp;<b>@{{decede.adresse_declarant}}</b></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal suppresion -->
<div class="modal fade bs-modal-suppression" category="dialog" data-backdrop="static">
    <div class="modal-dialog ">
        <form id="formSupprimer" ng-controller="formSupprimerCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Confimation de la suppression
                </div>
                @csrf
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idDecedeSupprimer"  ng-model="decede.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer l'acte de d&eacute;c&egrave;s N° <br/><b>@{{decede.numero_acte_deces + ' DU ' + decede.date_dressers}}</b></div>
                        <div class="text-center vertical processing">Suppression en cours</div>
                        <div class="pull-right">
                            <button type="button" data-dismiss="modal" class="btn btn-default btn-sm">Non</button>
                            <button type="submit" class="btn btn-danger btn-sm ">Oui</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    var ajout = false;
    var $table = jQuery("#table"), rows = [];
    
    appSmarty.controller('formAjoutCtrl', function ($scope) {
        $scope.populateForm = function (decede) {
        $scope.decede = decede;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.decede = {};
        };
    }); 
    
    appSmarty.controller('formDeclarantCtrl', function ($scope) {
        $scope.populateDeclarantForm = function (decede) {
        $scope.decede = decede;
        };
    });
    
    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (decede) {
        $scope.decede= decede;
        };
        $scope.initForm = function () {
        $scope.decede = {};
        };
    });
    
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        $("#nationalite, #fonction_declarant, #fonction_id").select2({width: '100%', allowClear: true});
        
        $("#searchByName").keyup(function (e) { 
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-deces'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-deces-by-name/' + name});
            }
        });
        $("#searchByActe").keyup(function (e) {
            var numero_acte = $("#searchByActe").val();
            if(numero_acte == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-deces'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-deces-by-numero-acte/' + numero_acte});
            }
        });
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ""){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-deces'])}}"});
            }else{
               $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-deces-by-date/' + date});
            }
        });
        $("#searchByParentName").keyup(function (e) { 
            var name = $("#searchByParentName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-deces'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-deces-by-parent-name/' + name});
            }
        });
        
        $('#searchByDate, #date_naissance_decede, #date_dresser, #date_naissance_declarant, #date_deces, #date_declaration').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            maxDate : new Date()
        }); 

        $('#date_retrait').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            minDate : new Date()
        });
        
        $("#btnModalAjout").on("click", function () {
            $("#nationalite, #fonction_declarant, #fonction_id").val('').trigger('change');
        });

        $("#formAjout").submit(function (e) {
            e.preventDefault();
            var $valid = $(this).valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            var $ajaxLoader = $("#formAjout .loader-overlay");

            if (ajout==true) {
                var methode = 'POST';
                var url = "{{route('e-civil.decedes.store')}}";
             }else{
                var methode = 'POST';
                var url = "{{route('e-civil.update-decede')}}";
             }
             var formData = new FormData($(this)[0]);
            editerDecedeAction(methode, url, $(this), formData, $ajaxLoader, $table, ajout);
        });
        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idDecedeSupprimer").val();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('decedes/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });
    
    function updateRow(idDecede) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var decede =_.findWhere(rows, {id: idDecede});
         $scope.$apply(function () {
            $scope.populateForm(decede);
        });
        decede.fonction_declarant != null ?  $('#fonction_declarant').select2("val", decede.fonction_declarant.id) : $('#fonction_declarant').select2("val", ""); 
        decede.fonction_id != null ?  $('#fonction_id').select2("val", decede.fonction_id) : $('#fonction_id').select2("val", ""); 
        decede.nationalite != null ?  $('#nationalite').select2("val", decede.nationalite) : $('#nationalite').select2("val", ""); 
        $(".bs-modal-ajout").modal("show");
    }

    function declarantRow(idDecede) {
          var $scope = angular.element($("#formDeclarant")).scope();
          var decede =_.findWhere(rows, {id: idDecede});
           $scope.$apply(function () {
              $scope.populateDeclarantForm(decede);
          });
       $(".bs-modal-declarant").modal("show");
    }
    
    function deleteRow(idDecede) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var decede =_.findWhere(rows, {id: idDecede});
           $scope.$apply(function () {
              $scope.populateForm(decede);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function extraiRow(idDeces){
        window.open("../e-civil/extrait-declaration-deces/" + idDeces ,'_blank')
    }
    function recuRow(idDemande){
        window.open("recu-declaration-deces/" + idDemande ,'_blank')
    }
    function recuFormatter(id, row){
        return '<button class="btn btn-xs btn-info" data-placement="left" data-toggle="tooltip" title="Reçu" onClick="javascript:recuRow(' + id + ');"><i class="fa fa-file-text-o"></i></button>';
    }
    function imageFormatter(id, row) { 
          return row.scanne_pv ? "<a target='_blank' href='" + '../' + row.scanne_pv + "'>Voir le document</a>" : "";
    }
    function montantFormatter(montant){
        return '<span class="text-bold">' + $.number(montant)+ '</span>';
    }
    function numeroActeDecesFormatter(id, row){
        return row.numero_acte_deces + ' DU ' + row.date_dressers;
    }
    function dateDecesFormatter(id, row){
        if(row.heure_deces!=null){
           return row.date_decess + ' à ' + row.heure_deces; 
        }else{
            return row.date_decess
        }
    }
    function optionFormatter(id, row) { 
            return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                    <button class="btn btn-xs btn-warning" data-placement="left" data-toggle="tooltip" title="Déclarant" onClick="javascript:declarantRow(' + id + ');"><i class="fa fa-list"></i></button>\n\
                    <button class="btn btn-xs btn-default" data-placement="left" data-toggle="tooltip" title="Imprimer la fiche" onClick="javascript:extraiRow(' + id + ');"><i class="fa fa-print"></i></button>\n\
                    <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    
    function editerDecedeAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
    jQuery.ajax({
        type: methode,
        url: url,
        cache: false,
        data: formData,
        contentType: false,
        processData: false,
        success:function (reponse, textStatus, xhr){
            if (reponse.code === 1) {
                var $scope = angular.element($formObject).scope();
                $scope.$apply(function () {
                    $scope.initForm();
                });
                if (ajout) { //creation
                    $table.bootstrapTable('refresh');
                } else { //Modification
                    $table.bootstrapTable('updateByUniqueId', {
                        id: reponse.data.id,
                        row: reponse.data
                    });
                    $table.bootstrapTable('refresh');
                    $(".bs-modal-ajout").modal("hide");
                }
                $formObject.trigger('eventAjouter', [reponse.data]);
                $("#nationalite, #fonction_declarant, #fonction_id").val('').trigger('change');
            }
            $.gritter.add({
                // heading of the notification
                title: "E-Civil",
                // the text inside the notification
                text: reponse.msg,
                sticky: false,
                image: basePath + "/assets/img/gritter/confirm.png",
            });
         },
          error: function (err) {
            var res = eval('('+err.responseText+')');
            var messageErreur = res.message;
            
            $.gritter.add({
                // heading of the notification
                title: "E-Civil",
                // the text inside the notification
                text: messageErreur,
                sticky: false,
                image: basePath + "/assets/img/gritter/confirm.png",
            });
            $formObject.removeAttr("disabled");
            $ajoutLoader.hide();
        },
         beforeSend: function () {
            $formObject.attr("disabled", true);
            $ajoutLoader.show();
        },
        complete: function () {
            $ajoutLoader.hide();
        },
    });
	};
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection


