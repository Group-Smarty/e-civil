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
<div class="col-md-4">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByName" placeholder="Rechercher par nom de l'epoux ou de l'epouse">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByActe" placeholder="Rechercher par N° de l'acte de mariage">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByDate" placeholder="Rechercher par date de mariage">
    </div>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-mariages'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="true">
    <thead>
        <tr>
            <th data-field="id" data-formatter="recuFormatter" data-width="60px" data-align="center">Imprimer</th>
            <th data-formatter="numeroActeMariageFormatter">N° de l'acte  </th>
            <th data-field="date_mariages">Date de mariage </th>
            <th data-field="regime.libelle_regime" data-sortable="true">R&eacute;gime</th>
            <th data-field="nom_complet_homme">Epoux </th>
            <th data-field="nom_complet_femme">Epouse </th>
            <th data-field="nombre_copie" data-align="center">Copie</th>
            <th data-field="montant_declaration" data-formatter="montantFormatter" data-align="center">Montant</th>
            <th data-field="date_declarations" data-visible="true">Date d&eacute;claration</th>
            <th data-field="date_retraits" data-visible="true">Date retrait</th>
            <th data-field="id" data-formatter="optionFormatter" data-width="150px" data-align="center"><i class="fa fa-wrench"></i></th>
        </tr>
    </thead>
</table>

<!-- Modal ajout et modification -->
<div class="modal fade bs-modal-ajout" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 80%">
        <form id="formAjout" ng-controller="formAjoutCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-venus-double fa-2x"></i>
                        Gestion des d&eacute;clarations de mariage
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" name="idMariage" ng-hide="true" ng-model="mariage.id"/>
                    @csrf
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#declaration_info" data-toggle="tab" aria-expanded="true">D&eacute;claration</a>
                            </li>
                            <li class="">
                                <a href="#homme_info" data-toggle="tab" aria-expanded="true">Informations Epoux</a>
                            </li>
                            <li class="">
                                <a href="#femme_info" data-toggle="tab" aria-expanded="true">Informations Epouse</a>
                            </li>
                            <li class="">
                                <a href="#temoins_info" data-toggle="tab" aria-expanded="true">Informations des t&eacute;moins et autres</a>
                            </li>
                            <li class="">
                                <a href="#mentions_info" data-toggle="tab" aria-expanded="true">Mentions</a>
                            </li>
                        </ul> 
                        <div class="tab-content">  
                            <div class="tab-pane active" id="declaration_info">
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Registre *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" ng-model="mariage.registre" id="registre" name="registre" value="<?=date('Y');?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>N° acte *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-edit"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="mariage.numero_acte_mariage" id="numero_acte_mariage" name="numero_acte_mariage" placeholder="Ex: 9800" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Dresser *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="mariage.date_dressers" id="date_dresser" name="date_dresser" placeholder="Ex: 01-01-1994" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date de mariage *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="mariage.date_mariages" id="date_mariage" name="date_mariage" placeholder="Ex: 01-01-1994 14:00" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>R&eacute;gime *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-circle"></i>
                                                </div>
                                                <select name="regime_id" id="regime_id" ng-model="mariage.regime_id" ng-init="mariage.regime_id=''" class="form-control" required>
                                                    <option value="" ng-show="false">-- Selectionner le regime --</option>
                                                    @foreach($regimes as $regime)
                                                    <option value="{{$regime->id}}"> {{$regime->libelle_regime}}</option>
                                                    @endforeach
                                                </select>
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
                                                <input type="text" class="form-control" ng-model="mariage.date_declarations" id="date_declaration" name="date_declaration" placeholder="Ex: 01-01-1994" required>
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
                                                <input type="number" min="0" class="form-control" ng-model="mariage.montant_declaration" id="montant_declaration" name="montant_declaration" placeholder="Ex: 500">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Nombre de copies </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-copy"></i>
                                                </div>
                                                <input type="number" min="1" class="form-control" ng-model="mariage.nombre_copie" id="nombre_copie" name="nombre_copie" placeholder="Ex: 5">
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
                                                <input type="text" class="form-control" ng-model="mariage.date_retraits" id="date_retrait" name="date_retrait" placeholder="Ex: 01-01-1994" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>D&eacute;clarant (Nom complet ) *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.nom_complet_declarant" id="nom_complet_declarant" name="nom_complet_declarant" placeholder="Nom et prénom(s) du déclarant" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Date de naissance d&eacute;cl.</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-language"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="mariage.date_naissance_declarants" id="date_naissance_declarant" name="date_naissance_declarant" placeholder="Ex: 01-01-1994">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Contact t&eacute;l&eacute;phonique d&eacute;clarant</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-mobile-phone"></i>
                                                </div>
                                                <input type="text" class="form-control bfh-phone" ng-model="mariage.contact_declarant" id="contact_declarant" name="contact_declarant" data-format="(dd) dd-dd-dd-dd" placeholder="Numéro mobile">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Adresse du domicile d&eacute;clarant</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.adresse_declarant" id="adresse_declarant" name="adresse_declarant" placeholder="Adresse du domicile">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Fonction du d&eacute;clarant </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-circle-o"></i>
                                                </div>
                                                <select name="fonction_declarant" id="fonction_declarant" class="form-control">
                                                    <option value="" >-- Selectionner la fonction --</option>
                                                    @foreach($fonctions as $fonction)
                                                    <option value="{{$fonction->id}}"> {{$fonction->libelle_fonction}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>D&eacute;cret d'autorisation de mariage pour l'homme </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-edit"></i>
                                                </div>
                                                <input type="text" class="form-control" name="decret_autorisation_homme" ng-model="mariage.decret_autorisation_homme" id="decret_autorisation_homme" placeholder="Numéro du loi ou du décret">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>D&eacute;cret d'autorisation de mariage pour la femme</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-edit"></i>
                                                </div>
                                                <input type="text" class="form-control" name="decret_autorisation_femme" ng-model="mariage.decret_autorisation_femme" id="decret_autorisation_femme" placeholder="Numéro du loi ou du décret">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="homme_info">
                                <div class="row"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nom complet *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.nom_complet_homme" id="nom_complet_homme" name="nom_complet_homme" placeholder="Nom et prénom(s)" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Adresse du domicile</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.adresse_domicile_homme" id="adresse_domicile_homme" name="adresse_domicile_homme" placeholder="Adresse du domicile">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Fonction </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-circle-o"></i>
                                                </div>
                                                <select name="fonction_homme" id="fonction_homme" class="form-control">
                                                    <option value="" >-- Selectionner la fonction --</option>
                                                    @foreach($fonctions as $fonction)
                                                    <option value="{{$fonction->id}}"> {{$fonction->libelle_fonction}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>N° d'acte de naissance </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list"></i>
                                                </div>
                                                <input type="text" id="numero_acte_naissance_homme" class="form-control" ng-model="mariage.numero_acte_naissance_homme" name="numero_acte_naissance_homme" placeholder="Ex: 6666 DU 12-02-1998"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date de naissance </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="mariage.date_naissance_hommes" id="date_naissance_homme" name="date_naissance_homme" placeholder="Ex : 01-01-1994">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Lieu de naissance </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.lieu_naissance_homme" id="lieu_naissance_homme" name="lieu_naissance_homme" placeholder="Lieu de naissance">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="text-bold text-green">P&egrave;re</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nom complet du p&egrave;re </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-male"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.nom_complet_pere_homme" id="nom_complet_pere_homme" name="nom_complet_pere_homme" placeholder="Nom et prénom(s) du père">
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Adresse du domicile </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.adresse_pere_homme" id="adresse_pere_homme" name="adresse_pere_homme" placeholder="Adresse du domicile">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <h5 class="text-bold text-green">M&egrave;re</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nom complet de la m&egrave;re </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-female"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.nom_complet_mere_homme" id="nom_complet_mere_homme" name="nom_complet_mere_homme" placeholder="Nom et prénom(s) de la mère">
                                                    </div>
                                                </div>
                                            </div>  
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Adresse du domicile </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.adresse_mere_homme" id="adresse_mere_homme" name="adresse_mere_homme" placeholder="Adresse du domicile">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div> 
                            </div>
                            <div class="tab-pane" id="femme_info">
                                <div class="row"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nom complet *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.nom_complet_femme" id="nom_complet_femme" name="nom_complet_femme" placeholder="Nom et prénom(s)" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Adresse du domicile</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.adresse_domicile_femme" id="adresse_domicile_femme" name="adresse_domicile_femme" placeholder="Adresse du domicile">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Fonction </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-circle-o"></i>
                                                </div>
                                                <select name="fonction_femme" id="fonction_femme" class="form-control">
                                                    <option value="" >-- Selectionner la fonction --</option>
                                                    @foreach($fonctions as $fonction)
                                                    <option value="{{$fonction->id}}"> {{$fonction->libelle_fonction}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>N° d'acte de naissance </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list"></i>
                                                </div>
                                                <input type="text" id="numero_acte_naissance_femme" class="form-control" ng-model="mariage.numero_acte_naissance_femme" name="numero_acte_naissance_femme" placeholder="Ex: 6666 DU 12-02-1998"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date de naissance </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="mariage.date_naissance_femmes" id="date_naissance_femme" name="date_naissance_femme" placeholder="Ex : 01-01-1994">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Lieu de naissance </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.lieu_naissance_femme" id="lieu_naissance_femme" name="lieu_naissance_femme" placeholder="Lieu de naissance">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="text-bold text-green">P&egrave;re</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nom complet du p&egrave;re </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-male"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.nom_complet_pere_femme" id="nom_complet_pere_femme" name="nom_complet_pere_femme" placeholder="Nom et prénom(s) du père">
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Adresse du domicile </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.adresse_pere_femme" id="adresse_pere_femme" name="adresse_pere_femme" placeholder="Adresse du domicile">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <h5 class="text-bold text-green">M&egrave;re</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nom complet de la m&egrave;re </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-female"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.nom_complet_mere_femme" id="nom_complet_mere_femme" name="nom_complet_mere_femme" placeholder="Nom et prénom(s) de la mère">
                                                    </div>
                                                </div>
                                            </div>  
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Adresse du domicile </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.adresse_mere_femme" id="adresse_mere_femme" name="adresse_mere_femme" placeholder="Adresse du domicile">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div> 
                            </div>
                            <div class="tab-pane" id="temoins_info">
                                <div class="row"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nom complet du t&eacute;moin 1</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.nom_complet_temoin_1" id="nom_complet_temoin_1" name="nom_complet_temoin_1" placeholder="Nom et prénom du témoin 1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Adresse du t&eacute;moin 1</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.adresse_temoin_1" id="adresse_temoin_1" name="adresse_temoin_1" placeholder="Adresse du témoin 1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Fonction du t&eacute;moin 1</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-circle-o"></i>
                                                </div>
                                                <select name="fonction_temoin_1" id="fonction_temoin_1" class="form-control">
                                                    <option value="">-- Selectionner la fonction --</option>
                                                    @foreach($fonctions as $fonction)
                                                    <option value="{{$fonction->id}}"> {{$fonction->libelle_fonction}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nom complet du t&eacute;moin 2</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.nom_complet_temoin_2" id="nom_complet_temoin_2" name="nom_complet_temoin_2" placeholder="Nom et prénom du témoin 2">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Adresse du t&eacute;moin 2</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.adresse_temoin_2" id="adresse_temoin_2" name="adresse_temoin_2" placeholder="Adresse du témoin 2">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Fonction du t&eacute;moin 2</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-circle-o"></i>
                                                </div>
                                                <select name="fonction_temoin_2" id="fonction_temoin_2" class="form-control">
                                                    <option value="">-- Selectionner la profession --</option>
                                                    @foreach($fonctions as $fonction)
                                                    <option value="{{$fonction->id}}"> {{$fonction->libelle_fonction}} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Signataire</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.signataire" id="signataire" name="signataire" placeholder="Dévant nous....">
                                            </div>                                       
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Langue de r&eacute;ception </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-edit"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="langue_reception" ng-model="mariage.langue_reception" id="langue_reception" placeholder="Langue de réception">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Traduire par </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-edit"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" name="traducteur" ng-model="mariage.traducteur" id="traducteur" placeholder="Traducteur">
                                            </div>
                                        </div>
                                    </div>
                                </div>
<!--                                <div class="row"> 
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Dress&eacute; par </label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.dressant" id="dressant" name="dressant" placeholder="Par nous...."></textarea>
                                        </div>
                                    </div>
                                </div>-->
                            </div>
                            <div class="tab-pane" id="mentions_info">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 1</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.mention_1" id="mention_1" name="mention_1" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 2</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.mention_2" id="mention_2" name="mention_2" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                </div> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 3</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.mention_3" id="mention_3" name="mention_3" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 4</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.mention_4" id="mention_4" name="mention_4" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                </div> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 5</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.mention_5" id="mention_5" name="mention_5" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 6</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.mention_6" id="mention_6" name="mention_6" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                </div> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 7</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.mention_7" id="mention_7" name="mention_7" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 8</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="mariage.mention_8" id="mention_8" name="mention_8" placeholder="Votre text"></textarea>
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

<!-- Modal couple -->
<div class="modal fade bs-modal-couple" category="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 70%">
        <form id="formCouple" ng-controller="formCoupleCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Infrmations du couple
                </div>
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-bold text-green">Epoux</h5>
                            <ul class="nav nav-stacked">
                                <li><a>Nom et pr&eacute;nom(s) : &nbsp;&nbsp;<b>@{{mariage.nom_complet_homme}}</b></a></li>
                                <li><a>Date de naissance : &nbsp;&nbsp;<b>@{{mariage.date_naissance_hommes}}</b></a></li>
                                <li><a>Lieu de naissance : &nbsp;&nbsp;<b>@{{mariage.lieu_naissance_homme}}</b></a></li>
                                <li><a>Adresse : &nbsp;&nbsp;<b>@{{mariage.adresse_domicile_homme}}</b></a></li>
                                <li><a>Fonction : &nbsp;&nbsp;<b>@{{mariage.fonction_homme.libelle_fonction}}</b></a></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-bold text-green">Epouse</h5>
                             <ul class="nav nav-stacked">
                                <li><a>Nom et pr&eacute;nom(s) : &nbsp;&nbsp;<b>@{{mariage.nom_complet_femme}}</b></a></li>
                                <li><a>Date de naissance : &nbsp;&nbsp;<b>@{{mariage.date_naissance_femmes}}</b></a></li>
                                <li><a>Lieu de naissance : &nbsp;&nbsp;<b>@{{mariage.lieu_naissance_femme}}</b></a></li>
                                <li><a>Adresse : &nbsp;&nbsp;<b>@{{mariage.adresse_domicile_homme}}</b></a></li>
                                <li><a>Fonction : &nbsp;&nbsp;<b>@{{mariage.fonction_femme.libelle_fonction}}</b></a></li>
                            </ul>
                        </div>
                    </div>
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
                    <li><a>Nom et pr&eacute;nom(s) : &nbsp;&nbsp;<b>@{{mariage.nom_complet_declarant}}</b></a></li>
                    <li><a>Date de d&eacute;claration : &nbsp;&nbsp;<b>@{{mariage.date_declarations}}</b></a></li>
                    <li><a>Contact : &nbsp;&nbsp;<b>@{{mariage.contact_declarant}}</b></a></li>
                    <li><a>Adresse : &nbsp;&nbsp;<b>@{{mariage.adresse_declarant}}</b></a></li>
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
                    <input type="text" class="hidden" id="idMariageSupprimer"  ng-model="mariage.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer l&acte de mariage N° <br/><b>@{{mariage.numero_acte_mariage + ' DU ' + mariage.date_dressers}}</b></div>
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
        $scope.populateForm = function (mariage) {
        $scope.mariage = mariage;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.mariage = {};
        };
    }); 
    
    appSmarty.controller('formCoupleCtrl', function ($scope) {
        $scope.populateCoupleForm = function (mariage) {
        $scope.mariage = mariage;
        };
    });
    
    appSmarty.controller('formDeclarantCtrl', function ($scope) {
        $scope.populateDeclarantForm = function (mariage) {
        $scope.mariage = mariage;
        };
    });
    
    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (mariage) {
        $scope.mariage = mariage;
        };
        $scope.initForm = function () {
        $scope.mariage = {};
        };
    });
    
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        
        $("#searchByName").keyup(function (e) { 
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-mariages'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-mariages-by-names/' + name});
            }
        });
        $("#searchByActe").keyup(function (e) {
            var numero_acte = $("#searchByActe").val();
            if(numero_acte == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-mariages'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-mariages-by-numero-acte/' + numero_acte});
            }
        });
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ""){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-mariages'])}}"});
            }else{
               $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-mariages-by-date/' + date});
            }
        });
       
        $('#searchByDate').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr'
        }); 
        
        $('#date_mariage').datetimepicker({
            timepicker: true,
            formatTime: 'H:i',
            formatDate: 'd-m-Y',
            format: 'd-m-Y H:i',
            local : 'fr'
        }); 
        
        $('#date_naissance_homme, #date_naissance_femme, #date_dresser, #date_declaration').datetimepicker({
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
 
        $("#fonction_declarant, #fonction_homme, #fonction_femme, #fonction_temoin_1, #fonction_temoin_2").select2({width: '100%', allowClear: true});
        
        $("#btnModalAjout").on("click", function () {
            $("#fonction_declarant, #fonction_homme, #fonction_femme, #fonction_temoin_1, #fonction_temoin_2").val('').trigger('change');
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
                var url = "{{route('e-civil.mariages.store')}}";
             }else{
                var methode = 'POST';
                var url = "{{route('e-civil.update-mariage')}}";
             }
             var formData = new FormData($(this)[0]);
            editerMariageAction(methode, url, $(this), formData, $ajaxLoader, $table, ajout);
        });
        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idMariageSupprimer").val();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('mariages/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });
    
    function updateRow(idMariage) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var mariage =_.findWhere(rows, {id: idMariage});
         $scope.$apply(function () {
            $scope.populateForm(mariage);
        });
    
        mariage.fonction_homme!=null? $("#fonction_homme").select2("val", mariage.fonction_homme.id):$("#fonction_homme").select2("val","");
        mariage.fonction_femme!=null? $("#fonction_femme").select2("val", mariage.fonction_femme.id):$("#fonction_femme").select2("val","");
        mariage.fonction_declarant!=null? $("#fonction_declarant").select2("val", mariage.fonction_declarant.id):$("#fonction_declarant").select2("val","");
        mariage.fonction_temoin_1!=null? $("#fonction_temoin_1").select2("val", mariage.fonction_temoin_1.id):$("#fonction_temoin_1").select2("val","");
        mariage.fonction_temoin_2!=null? $("#fonction_temoin_2").select2("val", mariage.fonction_temoin_2.id):$("#fonction_temoin_2").select2("val","");
        
        $(".bs-modal-ajout").modal("show");
    }

    
    function coupleRow(idMariage) {
        var $scope = angular.element($("#formCouple")).scope();
        var mariage =_.findWhere(rows, {id: idMariage});
        $scope.$apply(function () {
            $scope.populateCoupleForm(mariage);
        });
       $(".bs-modal-couple").modal("show");
    }
    
    function declarantRow(idMariage) {
          var $scope = angular.element($("#formDeclarant")).scope();
          var mariage =_.findWhere(rows, {id: idMariage});
           $scope.$apply(function () {
              $scope.populateDeclarantForm(mariage);
          });
       $(".bs-modal-declarant").modal("show");
    }
    
    function deleteRow(idMariage) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var mariage =_.findWhere(rows, {id: idMariage});
           $scope.$apply(function () {
              $scope.populateForm(mariage);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function numeroActeMariageFormatter(id,row){
        return row.numero_acte_mariage + ' DU ' + row.date_dressers;
    }
    function montantFormatter(montant){
        return '<span class="text-bold">' + $.number(montant)+ '</span>';
    }
    
    function recuRow(idMariage){
        window.open("../e-civil/recu-declaration-mariage/" + idMariage ,'_blank')
    }
    
    function extraiRow(idMariage){
        window.open("../e-civil/extrait-declaration-mariage/" + idMariage ,'_blank')
    }
    function optionFormatter(id, row) { 
            return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                    <button class="btn btn-xs btn-success" data-placement="left" data-toggle="tooltip" title="Informations du couple" onClick="javascript:coupleRow(' + id + ');"><i class="fa fa-venus-double"></i></button>\n\
                    <button class="btn btn-xs btn-warning" data-placement="left" data-toggle="tooltip" title="Déclarant" onClick="javascript:declarantRow(' + id + ');"><i class="fa fa-list"></i></button>\n\
                    <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
   
    function recuFormatter(id, row){
        return '<button class="btn btn-xs btn-info" data-placement="left" data-toggle="tooltip" title="Reçu" onClick="javascript:recuRow(' + id + ');"><i class="fa fa-file-text-o"></i></button>\n\
                <button class="btn btn-xs btn-default" data-placement="left" data-toggle="tooltip" title="Imprimer la fiche" onClick="javascript:extraiRow(' + id + ');"><i class="fa fa-print"></i></button>';
    }
    
    function editerMariageAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
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
                $("#fonction_declarant, #fonction_homme, #fonction_femme, #fonction_temoin_1, #fonction_temoin_2").val('').trigger('change');
                $formObject.trigger('eventAjouter', [reponse.data]);
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


