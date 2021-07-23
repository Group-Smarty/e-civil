@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur')
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
       <input type="text" class="form-control" id="searchByName" placeholder="Rechercher par nom du demandeur">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByDate" placeholder="Rechercher par date de demande">
    </div>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-certificat-concubinages'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="date_demande_certificats">Date demande </th>
            <th data-field="nom_complet_homme">Concubin </th>
            <th data-field="nom_complet_femme">Concubine </th>
            <th data-field="adresse_commune">Adresse du couple</th>
            <th data-field="nom_complet_temoins_1">T&eacute;moin 1 </th>
            <th data-field="nom_complet_temoins_2">T&eacute;moin 2 </th>
            <th data-field="nom_complet_demandeur">Demandeur</th>
            <th data-field="adresse_demandeur">Adresse demandeur</th>
            <th data-field="contact_demandeur">Contact demandeur</th>
            <th data-field="montant" data-formatter="montantFormatter" data-align="center">Montant</th>
            <th data-field="id" data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="fa fa-wrench"></i></th>
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
                        <i class="fa fa-cubes fa-2x"></i>
                        Gestion des demandes de certificat de concubinage
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idCertificatConcubinageModifier" ng-hide="true" ng-model="certificatConcubinage.id"/>
                    @csrf
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#infos_generales" data-toggle="tab" aria-expanded="true">Informations g&eacute;n&eacute;rales</a>
                            </li>
                            <li class="">
                                <a href="#infos_temoin_1" data-toggle="tab" aria-expanded="true">T&eacute;moin 1</a>
                            </li>
                            <li class="">
                                <a href="#infos_temoin_2" data-toggle="tab" aria-expanded="true">T&eacute;moin 2</a>
                            </li>
                        </ul> 
                        <div class="tab-content">  
                            <div class="tab-pane active" id="infos_generales">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Demandeur *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.nom_complet_demandeur" id="nom_complet_demandeur" name="nom_complet_demandeur" placeholder="Nom et prénom du demandeur" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Contact </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-mobile-phone"></i>
                                                </div>
                                                <input type="text" class="form-control bfh-phone" ng-model="certificatConcubinage.contact_demandeur" id="contact_demandeur" name="contact_demandeur" data-format="(dd) dd-dd-dd-dd" placeholder="Contact du demandeur">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Adresse du domicile *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.adresse_demandeur" id="adresse_demandeur" name="adresse_demandeur" placeholder="Adresse du demandeur" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Montant de la demande *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-money"></i>
                                                </div>
                                                <input type="number" min="0" class="form-control" ng-model="certificatConcubinage.montant" id="montant" name="montant" placeholder="Montant de la demande" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>   
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nom du monsieur *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.nom_complet_homme" id="nom_complet_homme" name="nom_complet_homme" placeholder="Nom et prénom(s) de l'homme" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Adresse du monsieur *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.adresse_homme" id="adresse_homme" name="adresse_homme" placeholder="Adresse de l'homme" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fonction du monsieur *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-circle-o"></i>
                                                </div>
                                                <select name="profession_homme" id="profession_homme" class="form-control" required>
                                                    <option value="" ng-show="false">-- Selectionner la fonction --</option>
                                                    @foreach($fonctions as $profession_homme)
                                                    <option value="{{$profession_homme->id}}"> {{$profession_homme->libelle_fonction}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date naissance du monsieur *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="certificatConcubinage.date_naissance_hommes" id="date_naissance_homme" name="date_naissance_homme" placeholder="Ex :01-01-1994" required>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                 <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nom du demoiselle *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.nom_complet_femme" id="nom_complet_femme" name="nom_complet_femme" placeholder="Nom et prénom(s) de la femme" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Adresse du demoiselle *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.adresse_femme" id="adresse_femme" name="adresse_femme" placeholder="Adresse de la femme" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fonction du demoiselle *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-circle-o"></i>
                                    </div>
                                    <select name="profession_femme" id="profession_femme" class="form-control" required>
                                        <option value="" ng-show="false">-- Selectionner la fonction --</option>
                                        @foreach($fonctions as $profession_femme)
                                        <option value="{{$profession_femme->id}}"> {{$profession_femme->libelle_fonction}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date naissance du demoiselle *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="certificatConcubinage.date_naissance_femmes" id="date_naissance_femme" name="date_naissance_femme" placeholder="Ex :01-01-1994" required>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date du mariage coutumier *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="certificatConcubinage.date_mariage_coutumiers" id="date_mariage_coutumier" name="date_mariage_coutumier" placeholder="Ex : 01-01-1994" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Lieu du mariage coutumier *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.lieu_mariage_coutumier" id="lieu_mariage_coutumier" name="lieu_mariage_coutumier" placeholder="Adresse de célébration"required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Adresse du couple *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.adresse_commune" id="adresse_commune" name="adresse_commune" placeholder="Adresse du domicile du couple" required>
                                </div>
                            </div>
                        </div>
                    </div> 
                            </div>
                            <div class="tab-pane" id="infos_temoin_1">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nom du t&eacute;moin 1</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.nom_complet_temoins_1" id="nom_complet_temoins_1" name="nom_complet_temoins_1" placeholder="Nom et prénom(s) du témoin 1">
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
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.adresse_temoins_1" id="adresse_temoins_1" name="adresse_temoins_1" placeholder="Adresse du témoin 1">
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
                                                <select name="profession_temoins_1" id="profession_temoins_1" class="form-control">
                                                    <option value="" ng-show="false">-- Selectionner la fonction --</option>
                                                    @foreach($fonctions as $profession_temoins_1)
                                                    <option value="{{$profession_temoins_1->id}}"> {{$profession_temoins_1->libelle_fonction}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>N° pi&egrave;ce du t&eacute;moin 1</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="certificatConcubinage.numero_piece_temoins_1" id="numero_piece_temoins_1" name="numero_piece_temoins_1" placeholder="N° pièce d'identité">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date d'&eacute;tablissement de pi&egrave;ce </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="certificatConcubinage.date_etablisssement_piece_temoins_1s" id="date_etablisssement_piece_temoins_1" name="date_etablisssement_piece_temoins_1" placeholder="Ex :01-01-1994">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Lieu d'&eacute;tablissement de pi&egrave;ce</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.lieu_etablisssement_piece_temoins_1" id="lieu_etablisssement_piece_temoins_1" name="lieu_etablisssement_piece_temoins_1" placeholder="Lieu de production de la pièce">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Pi&eacute;ce d&eacute;livr&eacute;e par </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-institution"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.piece_temoins_1_delivre_par" id="piece_temoins_1_delivre_par" name="piece_temoins_1_delivre_par" placeholder="Institution de délivrance la pièce d'identité">
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="tab-pane" id="infos_temoin_2">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nom du t&eacute;moin 2</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.nom_complet_temoins_2" id="nom_complet_temoins_2" name="nom_complet_temoins_2" placeholder="Nom et prénom(s) du témoin 2">
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
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.adresse_temoins_2" id="adresse_temoins_2" name="adresse_temoins_2" placeholder="Adresse du témoin 2">
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
                                                <select name="profession_temoins_2" id="profession_temoins_2" class="form-control">
                                                    <option value="" ng-show="false">-- Selectionner la fonction --</option>
                                                    @foreach($fonctions as $profession_temoins_2)
                                                    <option value="{{$profession_temoins_2->id}}"> {{$profession_temoins_2->libelle_fonction}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>N° pi&egrave;ce du t&eacute;moin 2</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="certificatConcubinage.numero_piece_temoins_2" id="numero_piece_temoins_2" name="numero_piece_temoins_2" placeholder="N° pièce d'identité">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date d'&eacute;tablissement de pi&egrave;ce </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="certificatConcubinage.date_etablisssement_piece_temoins_2s" id="date_etablisssement_piece_temoins_2" name="date_etablisssement_piece_temoins_2" placeholder="Ex :01-01-1994">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Lieu d'&eacute;tablissement de pi&egrave;ce</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.lieu_etablisssement_piece_temoins_2" id="lieu_etablisssement_piece_temoins_2" name="lieu_etablisssement_piece_temoins_2" placeholder="Lieu de production de la pièce">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Pi&eacute;ce d&eacute;livr&eacute;e par </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-institution"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatConcubinage.piece_temoins_2_delivre_par" id="piece_temoins_2_delivre_par" name="piece_temoins_2_delivre_par" placeholder="Institution de délivrance la pièc.">
                                            </div>
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
                    <input type="text" class="hidden" id="idCertificatConcubinageSupprimer"  ng-model="certificatConcubinage.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer la demande de <br/><b>@{{certificatConcubinage.nom_complet_demandeur}}</b></div>
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
        $scope.populateForm = function (certificatConcubinage) {
        $scope.certificatConcubinage = certificatConcubinage;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.certificatConcubinage = {};
        };
    }); 
    
    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (certificatConcubinage) {
        $scope.certificatConcubinage = certificatConcubinage;
        };
        $scope.initForm = function () {
        $scope.certificatConcubinage = {};
        };
    });
    
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        $("#profession_homme, #profession_femme, #profession_temoins_1, #profession_temoins_2").select2({width: '100%', allowClear: true});
        $("#searchByName").keyup(function (e) { 
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-concubinages'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-concubinage-by-name/' + name});
            }
        });
      
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ""){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-concubinages'])}}"});
            }else{
               $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-concubinage-by-date/' + date});
            }
        });

        $('#searchByDate,#date_naissance_homme,#date_naissance_femme,#date_mariage_coutumier,#date_etablisssement_piece_temoins_1,#date_etablisssement_piece_temoins_2').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            maxDate:new Date()
        });
        
         $("#btnModalAjout").on("click", function () {
                $("#profession_homme").select2("val", "");
                $("#profession_femme").select2("val", "");
                $("#profession_temoins_1").select2("val", "");
                $("#profession_temoins_2").select2("val", "");
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
                var url = "{{route('e-civil.certificat-concubinages.store')}}";
             }else{
                var id = $("#idCertificatConcubinageModifier").val();
                var methode = 'PUT';
                var url = 'certificat-concubinages/' + id;
             }
            editerCertificatConcubinageAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });
       
        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idCertificatConcubinageSupprimer").val();
            var formData = $(this).serialize();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('certificat-concubinages/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });
    
    function updateRow(idCertificatConcubinage) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var certificatConcubinage =_.findWhere(rows, {id: idCertificatConcubinage});
         $scope.$apply(function () {
            $scope.populateForm(certificatConcubinage);
        });
        certificatConcubinage.profession_homme!=null ?  $('#profession_homme').select2("val", certificatConcubinage.profession_homme):$('#profession_homme').select2("val", ""); 
        certificatConcubinage.profession_femme!=null ? $('#profession_femme').select2("val", certificatConcubinage.profession_femme):$('#profession_femme').select2("val", ""); 
        certificatConcubinage.profession_temoins_1!=null ? $('#profession_temoins_1').select2("val", certificatConcubinage.profession_temoins_1):$('#profession_temoins_1').select2("val", ""); 
        certificatConcubinage.profession_temoins_2!=null ? $('#profession_temoins_2').select2("val", certificatConcubinage.profession_temoins_2):$('#profession_temoins_2').select2("val", ""); 
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idCertificatConcubinage) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var certificatConcubinage =_.findWhere(rows, {id: idCertificatConcubinage});
           $scope.$apply(function () {
              $scope.populateForm(certificatConcubinage);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function pdfRow(idCertificat){
        window.open("../e-civil/certificat-concubinages-pdf/" + idCertificat ,'_blank')
    }

    function montantFormatter(montant){
        return '<span class="text-bold">' + $.number(montant)+ '</span>';
    }
    
    function optionFormatter(id, row) { 
            return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                    <button class="btn btn-xs btn-default" data-placement="left" data-toggle="tooltip" title="Imprimer la fiche" onClick="javascript:pdfRow(' + id + ');"><i class="fa fa-print"></i></button>\n\
                    <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    
     function editerCertificatConcubinageAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
    jQuery.ajax({
        type: methode,
        url: url,
        cache: false,
        data: formData,
        success:function (reponse, textStatus, xhr){
            if (reponse.code === 1) {
                var $scope = angular.element($formObject).scope();
                $scope.$apply(function () {
                    $scope.initForm();
                });
                if (ajout) { //creation
                    $table.bootstrapTable('refresh');
                    $("#profession_homme").select2("val", "");
                    $("#profession_femme").select2("val", "");
                    $("#profession_temoins_1").select2("val", "");
                    $("#profession_temoins_2").select2("val", "");
                } else { //Modification
                    $table.bootstrapTable('updateByUniqueId', {
                        id: reponse.data.id,
                        row: reponse.data
                    });
                    $table.bootstrapTable('refresh');
                    $(".bs-modal-ajout").modal("hide");
                }
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


