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
       <input type="text" class="form-control" id="searchByActe" placeholder="Rechercher par N° de l'acte">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByName" placeholder="Rechercher par nom ou prénom(s)">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByDate" placeholder="Rechercher par date de naissance">
    </div>
</div>
<div class="col-md-2">
    <div class="form-group">
        <select id="searchBySexe"  class="form-control">
            <option value="tous">--- Tous les sexes ---</option>
            <option value="Masculin">Masculin</option>
            <option value="Feminin">Feminin</option>
        </select>
    </div>
</div>

<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-naissances'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="true">
    <thead>
        <tr>
            <th data-field="id" data-formatter="recuFormatter" data-width="50px" data-align="center">Re&ccedil;u</th>
            <th data-formatter="numeroActeNaissanceFormatter">N° de l'acte  </th>
            <th data-field="nom_enfant" data-sortable="true">Nom  </th>
            <th data-field="prenom_enfant" data-sortable="true">Pr&eacute;nom(s)  </th>
            <th data-field="sexe">Sexe </th>
            <th data-field="lieu_naissance_enfant">Lieu de naissance</th>
            <th data-formatter="dateNaissanceFormatter">Date de naissance</th>
            <th data-field="nom_complet_pere">P&egrave;re </th>
            <th data-field="nom_complet_mere">M&egrave;re </th>
            <th data-field="registre" data-align="center">Registre</th>
            <th data-field="nombre_copie" data-align="center" data-visible="false">Copie</th>
            <th data-field="date_retraits">Date retrait</th>
            <th data-field="date_declarations" data-visible="false">Date d&eacute;claration</th>
            <th data-field="numero_jugement_supletif" data-visible="false" data-align="center">N° du jugement suppl&eacute;tif</th>
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
                        <i class="fa fa-birthday-cake fa-2x"></i>
                        Gestion des d&eacute;clarations de naissance
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" name="idNaissance" ng-hide="true" ng-model="naissance.id"/>
                    @csrf
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#declaration_info" data-toggle="tab" aria-expanded="true">D&eacute;claration</a>
                            </li>
                            <li class="">
                                <a href="#parent_info" data-toggle="tab" aria-expanded="true">Parents</a>
                            </li>
                            <li class="">
                                <a href="#event_infos" data-toggle="tab" aria-expanded="true">Mentions event.</a>
                            </li>
                            <li class="">
                                <a href="#temoin_infos" data-toggle="tab" aria-expanded="true">T&eacute;moins et autres</a>
                            </li>
                            <li class="">
                                <a href="#mentions_infos" data-toggle="tab" aria-expanded="true">Mentions</a>
                            </li>
                        </ul> 
                        <div class="tab-content">  
                            <div class="tab-pane active" id="declaration_info">
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Registre *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" ng-model="naissance.registre" id="registre" name="registre" value="<?= date("Y"); ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>N° de l'acte *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-edit"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="naissance.numero_acte_naissance" id="numero_acte_naissance" name="numero_acte_naissance" placeholder="Ex: 5040" required>
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
                                                <input type="text" class="form-control" ng-model="naissance.date_dressers" id="date_dresser" name="date_dresser" placeholder="Ex: 01-01-1994" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nom *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.nom_enfant" id="nom_enfant" name="nom_enfant" placeholder="Nom de la personne déclarée" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Pr&eacute;nom(s) *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.prenom_enfant" id="prenom_enfant" name="prenom_enfant" placeholder="Prénom(s) de la personne déclarée" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Date de naissance *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="naissance.date_naissance_enfants" id="date_naissance_enfant" name="date_naissance_enfant" placeholder="Ex: 01-01-1994" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Heure </label>
                                            <div class="input-group">
                                             <div class="input-group-addon">
                                                    <i class="fa fa-hourglass"></i>
                                                </div>
                                            <input type="time" class="form-control" ng-model="naissance.heure_naissance_enfant" id="heure_naissance_enfant" name="heure_naissance_enfant" placeholder="00:00">
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Lieu de naissance *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.lieu_naissance_enfant" id="lieu_naissance_enfant" name="lieu_naissance_enfant" placeholder="Lieu de naissance" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Sexe *</label>
                                            <div class="input-group">
                                                <select name="sexe" id="sexe" ng-model="naissance.sexe" ng-init="naissance.sexe='Masculin'" class="form-control" required>
                                                    <option value="Masculin">M</option>
                                                    <option value="Feminin">F</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Date de r&eacute;quisition </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="naissance.date_requisitions" id="date_requisition" name="date_requisition" placeholder="Ex: 01-01-1994">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>N° r&eacute;quisition </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-edit"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="naissance.numero_requisition" id="numero_requisition" name="numero_requisition" placeholder="N° réquisition">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Loi HS </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-edit"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="naissance.loi" id="loi" name="loi" placeholder="Loi HS">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tribunal </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-institution"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.tribunale" id="tribunale" name="tribunale" placeholder="Par la section de tribunal de.............">
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>N° du jugement suppl&eacute;tif </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-edit"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="naissance.numero_jugement_supletif" id="numero_jugement_supletif" name="numero_jugement_supletif" placeholder="N° du jugement supplétif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Date d&eacute;claration *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="naissance.date_declarations" id="date_declaration" name="date_declaration" placeholder="Ex: 01-01-1994" required>
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
                                                <input type="text" pattern="[0-9]*"  class="form-control" ng-model="naissance.montant_declaration" id="montant_declaration" name="montant_declaration" placeholder="Ex: 500">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Nbre de copies *</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-copy"></i>
                                                </div>
                                                <input type="number" min="1" class="form-control" ng-model="naissance.nombre_copie" id="nombre_copie" name="nombre_copie" placeholder="Ex: 2" required>
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
                                                <input type="text" class="form-control" ng-model="naissance.date_retraits" id="date_retrait" name="date_retrait" placeholder="Ex: 01-01-1994" required>
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
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.adresse_declarant" id="adresse_declarant" name="adresse_declarant" placeholder="Adresse du domicile">
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
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.nom_complet_declarant" id="nom_complet_declarant" name="nom_complet_declarant" placeholder="Nom et prénom(s) du déclarant" required>
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
                                                <input type="text" class="form-control bfh-phone" ng-model="naissance.contact_declarant" id="contact_declarant" name="contact_declarant" data-format="(dd) dd-dd-dd-dd" placeholder="Numéro mobile">
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
                                                <input type="text" class="form-control" ng-model="naissance.date_naissance_declarants" id="date_naissance_declarant" name="date_naissance_declarant" placeholder="Date de naissance du déclarant">
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
                            <div class="tab-pane" id="parent_info">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="text-bold text-green">P&egrave;re</h5>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Nom complet du p&egrave;re </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-male"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.nom_complet_pere" id="nom_complet_pere" name="nom_complet_pere" placeholder="Nom et prénom(s) du père">
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>N° de la pi&egrave;ce d'identit&eacute; </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-list"></i>
                                                        </div>
                                                        <input type="text" class="form-control" ng-model="naissance.numero_piece_identite_pere" id="numero_piece_identite_pere" name="numero_piece_identite_pere" placeholder="N° de la pièce d'identité">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Adresse du domicile</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.adresse_pere" id="adresse_pere" name="adresse_pere" placeholder="Adresse du domicile">
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date de naissance</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control" ng-model="naissance.date_naissance_peres" id="date_naissance_pere" name="date_naissance_pere" placeholder="Ex: 01-01-1994">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Lieu de naissance </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.lieu_naissance_pere" id="lieu_naissance_pere" name="lieu_naissance_pere" placeholder="Lieu de naissance">
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nationalit&eacute; </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-map"></i>
                                                        </div>
                                                        <select name="nationalite_pere" id="nationalite_pere" class="form-control">
                                                            <option value="" >-- Selectionner la nation --</option>
                                                            @foreach($nations as $nation)
                                                            <option value="{{$nation->id}}"> {{$nation->libelle_nation}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Fonction </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-circle-o"></i>
                                                        </div>
                                                        <select name="fonction_pere" id="fonction_pere" class="form-control">
                                                            <option value=''>-- Selectionner la fonction --</option>
                                                            @foreach($fonctions as $fonction)
                                                            <option value="{{$fonction->id}}"> {{$fonction->libelle_fonction}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <h5 class="text-bold text-green">M&egrave;re</h5>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Nom complet de la m&egrave;re </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-male"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.nom_complet_mere" id="nom_complet_mere" name="nom_complet_mere" placeholder="Nom et prénom(s) de la mère">
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>N° de la pi&egrave;ce d'identit&eacute; </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-list"></i>
                                                        </div>
                                                        <input type="text" class="form-control" ng-model="naissance.numero_piece_identite_mere" id="numero_piece_identite_mere" name="numero_piece_identite_mere" placeholder="N° de la pièce d'identité">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Adresse du domicile</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.adresse_mere" id="adresse_mere" name="adresse_mere" placeholder="Adresse du domicile">
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Date de naissance</label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                        <input type="text" class="form-control" ng-model="naissance.date_naissance_meres" id="date_naissance_mere" name="date_naissance_mere" placeholder="Ex: 01-01-1994">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Lieu de naissance </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-map-marker"></i>
                                                        </div>
                                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.lieu_naissance_mere" id="lieu_naissance_mere" name="lieu_naissance_mere" placeholder="Lieu de naissance">
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nationalit&eacute; </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-map"></i>
                                                        </div>
                                                        <select name="nationalite_mere" id="nationalite_mere" class="form-control">
                                                            <option value="" >-- Selectionner la nation --</option>
                                                            @foreach($nations as $nation)
                                                            <option value="{{$nation->id}}"> {{$nation->libelle_nation}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Fonction </label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-circle-o"></i>
                                                        </div>
                                                        <select name="fonction_mere" id="fonction_mere" class="form-control">
                                                            <option value=''>-- Selectionner la fonction --</option>
                                                            @foreach($fonctions as $fonction)
                                                            <option value="{{$fonction->id}}"> {{$fonction->libelle_fonction}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row"> 
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Situation des parents</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-list"></i>
                                                </div>
                                                <select name="situation_parents" id="situation_parents" ng-model="naissance.situation_parents" ng-init="naissance.situation_parents='Autres'" class="form-control">
                                                    <option value="Mariés">Mari&eacute;s</option>
                                                    <option value="Divorcés">Divorc&eacute;s</option>
                                                    <option value="Autres">Autres</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                            <div class="tab-pane" id="event_infos">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Mari&eacute;(e) le </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="naissance.mention_date_mariages" id="mention_date_mariage" name="mention_date_mariage" placeholder="date du mariage">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Mari&eacute;(e) &agrave; </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.mention_lieu_mariage" id="mention_lieu_mariage" name="mention_lieu_mariage" placeholder="Lieu du mariage">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Mari&eacute;(e) avec </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-female"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.mention_conjoint" id="mention_conjoint" name="mention_conjoint" placeholder="Conjoint ou conjointe">
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Divorc&eacute; le </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="naissance.mention_date_divorces" id="mention_date_divorce" name="mention_date_divorce" placeholder="date du divorce">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Date du d&eacute;c&egrave;s </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="naissance.mention_date_decess" id="mention_date_deces" name="mention_date_deces" placeholder="Date du décès">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Lieu du d&eacute;c&egrave;s </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.mention_lieu_deces" id="mention_lieu_deces" name="mention_lieu_deces" placeholder="Lieu du décès">
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </div> 
                            <div class="tab-pane" id="temoin_infos"> 
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nom complet t&eacute;moin 1 </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" class="form-control" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" ng-model="naissance.nom_temoin_1" id="nom_temoin_1" name="nom_temoin_1" placeholder="Nom complet du témoin 1">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Date de naissance</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="naissance.date_naissance_temoin_1s" id="date_naissance_temoin_1" name="date_naissance_temoin_1" placeholder="Ex : 01-01-1994">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fonction </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-circle-o"></i>
                                                </div>
                                                <select name="fonction_temoin_1" id="fonction_temoin_1" class="form-control">
                                                    <option value="">-- Selectionner la fonction --</option>
                                                    @foreach($fonctions as $fonction_temoin_1)
                                                    <option value="{{$fonction_temoin_1->id}}"> {{$fonction_temoin_1->libelle_fonction}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Adresse </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.adresse_temoins_1" id="adresse_temoins_1" name="adresse_temoins_1" placeholder="Adresse témoin 1">
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nom complet t&eacute;moin 2 </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" class="form-control" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" ng-model="naissance.nom_temoin_2" id="nom_temoin_2" name="nom_temoin_2" placeholder="Nom complet du témoin 2">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Date de naissance</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control" ng-model="naissance.date_naissance_temoin_2s" id="date_naissance_temoin_2" name="date_naissance_temoin_2" placeholder="Ex : 01-01-1994">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fonction </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-circle-o"></i>
                                                </div>
                                                <select name="fonction_temoin_2" id="fonction_temoin_2" class="form-control">
                                                    <option value="">-- Selectionner la fonction --</option>
                                                    @foreach($fonctions as $fonction_temoin_1)
                                                    <option value="{{$fonction_temoin_1->id}}"> {{$fonction_temoin_1->libelle_fonction}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Adresse </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.adresse_temoins_2" id="adresse_temoins_2" name="adresse_temoins_2" placeholder="Adresse témoin 2">
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nous avons signé avec </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.signataire" id="signataire" name="signataire" placeholder="Nous avons signé avec..............">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Langue de r&eacute;ception de la d&eacute;claration </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-edit"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.langue_reception" id="langue_reception" name="langue_reception" placeholder="Langue">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Traducteur </label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-edit"></i>
                                                </div>
                                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.traducteur" id="traducteur" name="traducteur" placeholder="Traducteur">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Dress&eacute; par </label>
                                            <textarea rows="4" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.dressant" id="dressant" name="dressant" placeholder="Dresser par nous..................." autocomplete="on"></textarea>
                                        </div>
                                    </div> 
                                </div> 
                            </div> 
                            <div class="tab-pane" id="mentions_infos"> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 1</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.mention_1" id="mention_1" name="mention_1" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions rectification et en vue de mariage</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.mention_2" id="mention_2" name="mention_2" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                </div> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 3</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.mention_3" id="mention_3" name="mention_3" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 4</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.mention_4" id="mention_4" name="mention_4" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                </div> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 5</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.mention_5" id="mention_5" name="mention_5" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 6</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.mention_6" id="mention_6" name="mention_6" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                </div> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 7</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.mention_7" id="mention_7" name="mention_7" placeholder="Votre text"></textarea>
                                        </div>
                                    </div> 
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mentions 8</label>
                                            <textarea rows="3" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="naissance.mention_8" id="mention_8" name="mention_8" placeholder="Votre text"></textarea>
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
                Informations d&eacute;claration
            </div>
            <div class="modal-body">
                <ul class="nav nav-stacked">
                    <li><a>Nom et pr&eacute;nom(s) du d&eacute;clarant: &nbsp;&nbsp;<b>@{{naissance.nom_complet_declarant}}</b></a></li>
                    <li><a>Date de d&eacute;claration : &nbsp;&nbsp;<b>@{{naissance.date_declarations}}</b></a></li>
                    <li><a>Contact : &nbsp;&nbsp;<b>@{{naissance.contact_declarant}}</b></a></li>
                    <li><a>Adresse : &nbsp;&nbsp;<b>@{{naissance.adresse_declarant}}</b></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal parent -->
<div class="modal fade bs-modal-parent" category="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 70%">
        <form id="formParent" ng-controller="formParentCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Infrmations des parents
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-bold text-green">P&egrave;re</h5>
                            <ul class="nav nav-stacked">
                                <li><a>Nom et pr&eacute;nom(s) : &nbsp;&nbsp;<b>@{{naissance.nom_complet_pere}}</b></a></li>
                                <li><a>N° pi&eacute;ce d'identit&eacute; : &nbsp;&nbsp;<b>@{{naissance.numero_piece_identite_pere}}</b></a></li>
                                <li><a>Date de naissance : &nbsp;&nbsp;<b>@{{naissance.date_naissance_peres}}</b></a></li>
                                <li><a>Lieu de naissance : &nbsp;&nbsp;<b>@{{naissance.lieu_naissance_pere}}</b></a></li>
                                <li><a>Adresse : &nbsp;&nbsp;<b>@{{naissance.adresse_pere}}</b></a></li>
                                <li><a>Nationalit&eacute; : &nbsp;&nbsp;<b>@{{naissance.nationalite_pere.libelle_nation}}</b></a></li>
                                <li><a>Fonction : &nbsp;&nbsp;<b>@{{naissance.fonction_mere.libelle_fonction}}</b></a></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-bold text-green">M&egrave;re</h5>
                            <ul class="nav nav-stacked">
                                <li><a>Nom et pr&eacute;nom(s) : &nbsp;&nbsp;<b>@{{naissance.nom_complet_mere}}</b></a></li>
                                <li><a>N° pi&eacute;ce d'identit&eacute; : &nbsp;&nbsp;<b>@{{naissance.numero_piece_identite_mere}}</b></a></li>
                                <li><a>Date de naissance : &nbsp;&nbsp;<b>@{{naissance.date_naissance_meres}}</b></a></li>
                                <li><a>Lieu de naissance : &nbsp;&nbsp;<b>@{{naissance.lieu_naissance_mere}}</b></a></li>
                                <li><a>Adresse : &nbsp;&nbsp;<b>@{{naissance.adresse_pere}}</b></a></li>
                                <li><a>Nationalit&eacute; : &nbsp;&nbsp;<b>@{{naissance.nationalite_mere.libelle_nation}}</b></a></li>
                                <li><a>Fonction : &nbsp;&nbsp;<b>@{{naissance.fonction_mere.libelle_fonction}}</b></a></li>
                            </ul>
                        </div>
                    </div>
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
                    <input type="text" class="hidden" id="idNaissanceSupprimer"  ng-model="naissance.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer l'extrait de naissance N° <br/><b>@{{naissance.numero_acte_naissance + ' DU ' + naissance.date_dressers}}</b></div>
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
        $scope.populateForm = function (naissance) {
        $scope.naissance = naissance;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.naissance = {};
        };
    }); 
    
    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (naissance) {
        $scope.naissance = naissance;
        };
        $scope.initForm = function () {
        $scope.naissance = {};
        };
    });

    appSmarty.controller('formParentCtrl', function ($scope) {
        $scope.populateParentForm = function (naissance) {
        $scope.naissance = naissance;
        };
    });
    
    appSmarty.controller('formDeclarantCtrl', function ($scope) {
        $scope.populateDeclarantForm = function (naissance) {
        $scope.naissance = naissance;
        };
    });

    $(function () {
    	$table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        
        $("#fonction_declarant, #nationalite_pere, #nationalite_mere, #fonction_pere, #fonction_mere, #fonction_temoin_1, #fonction_temoin_2").select2({width: '100%', allowClear: true});
        $('#date_declaration,#date_naissance_enfant,#searchByDate,#date_naissance_temoin_1,#date_naissance_temoin_2, #date_naissance_pere,#date_naissance_declarant, #date_naissance_mere, #date_dresser, #date_requisition, #mention_date_mariage, #mention_date_deces, #mention_date_divorce').datetimepicker({
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
            $("#fonction_declarant, #nationalite_pere, #nationalite_mere, #fonction_pere, #fonction_mere, #fonction_temoin_1, #fonction_temoin_2").val('').trigger('change');
        });
        
        $("#searchByActe").keyup(function (e) { 
            var numero_acte = $("#searchByActe").val();
            if(numero_acte == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-naissances'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-naissances-by-acte/' + numero_acte});
            }
        });
        $("#searchByName").keyup(function (e) {
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-naissances'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-naissances-by-name/' + name});
            }
        });
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ""){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-naissances'])}}"});
            }else{
               $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-naissances-by-date/' + date});
            }
        });
        $("#searchBySexe").change(function (e) {
            var sexe = $("#searchBySexe").val();
            if(sexe == 'tous'){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-naissances'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-naissances-by-sexe/' + sexe});
            }
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
                var url = "{{route('e-civil.naissances.store')}}";
             }else{
                var methode = 'POST';
                var url = "{{route('e-civil.update-naissance')}}";
             }
             var formData = new FormData($(this)[0]);
            editerNaissanceAction(methode, url, $(this), formData, $ajaxLoader, $table, ajout);
        });
        
        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idNaissanceSupprimer").val();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('naissances/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });

    function updateRow(idNaissance) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var naissance =_.findWhere(rows, {id: idNaissance});
         $scope.$apply(function () {
            $scope.populateForm(naissance);
        });
        naissance.fonction_declarant != null ?  $('#fonction_declarant').select2("val", naissance.fonction_declarant.id) : $('#fonction_declarant').select2("val", "");
        naissance.nationalite_mere != null ?  $('#nationalite_mere').select2("val", naissance.nationalite_mere.id) : $('#nationalite_mere').select2("val", "");
        naissance.nationalite_pere != null ?  $('#nationalite_pere').select2("val", naissance.nationalite_pere.id) : $('#nationalite_pere').select2("val", "");
        naissance.fonction_pere != null ?  $('#fonction_pere').select2("val", naissance.fonction_pere.id) : $('#fonction_pere').select2("val", ""); 
        naissance.fonction_mere != null ?  $('#fonction_mere').select2("val", naissance.fonction_mere.id) : $('#fonction_mere').select2("val", ""); 
        naissance.situation_parents != null ?  $('#situation_parents').val(naissance.situation_parents) : $('#situation_parents').val("Autres"); 
        naissance.fonction_temoin_1 != null ?  $('#fonction_temoin_1').select2("val", naissance.fonction_temoin_1.id) : $('#fonction_declarant').select2("val", ""); 
        naissance.fonction_temoin_2 != null ?  $('#fonction_temoin_2').select2("val", naissance.fonction_temoin_2.id) : $('#fonction_declarant').select2("val", ""); 
        $(".bs-modal-ajout").modal("show");
    }
    function deleteRow(idNaissance) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var naissance =_.findWhere(rows, {id: idNaissance});
           $scope.$apply(function () {
              $scope.populateForm(naissance);
          });
       $(".bs-modal-suppression").modal("show");
    }
    function parentRow(idNaissance) {
        var $scope = angular.element($("#formParent")).scope();
        var naissance =_.findWhere(rows, {id: idNaissance});
        $scope.$apply(function () {
            $scope.populateParentForm(naissance);
        });
       $(".bs-modal-parent").modal("show");
    }
    
    function declarantRow(idNaissance) {
          var $scope = angular.element($("#formDeclarant")).scope();
          var naissance =_.findWhere(rows, {id: idNaissance});
           $scope.$apply(function () {
              $scope.populateDeclarantForm(naissance);
          });
       $(".bs-modal-declarant").modal("show");
    }
    
    function recuRow(idNaissance){
        window.open("recu-declaration-naissance/" + idNaissance ,'_blank')
    }
    
    function extraiRow(idNaissance){
        window.open("extrait-declaration-naissance/" + idNaissance ,'_blank')
    }
    
    function optionFormatter(id, row) { 
        if(row.nom_complet_pere || row.nom_complet_mere){
            return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-success" data-placement="left" data-toggle="tooltip" title="Parents" onClick="javascript:parentRow(' + id + ');"><i class="fa fa-venus-double"></i></button>\n\
                <button class="btn btn-xs btn-warning" data-placement="left" data-toggle="tooltip" title="Déclarant" onClick="javascript:declarantRow(' + id + ');"><i class="fa fa-list"></i></button>\n\
                <button class="btn btn-xs btn-default" data-placement="left" data-toggle="tooltip" title="Imprimer la fiche" onClick="javascript:extraiRow(' + id + ');"><i class="fa fa-print"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
        }else{
            return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-warning" data-placement="left" data-toggle="tooltip" title="Déclarant" onClick="javascript:declarantRow(' + id + ');"><i class="fa fa-list"></i></button>\n\
                <button class="btn btn-xs btn-default" data-placement="left" data-toggle="tooltip" title="Imprimer la fiche" onClick="javascript:extraiRow(' + id + ');"><i class="fa fa-print"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
        }
    }

    function recuFormatter(id, row){
        return '<button class="btn btn-xs btn-info" data-placement="left" data-toggle="tooltip" title="Reçu" onClick="javascript:recuRow(' + id + ');"><i class="fa fa-file-text-o"></i></button>';
    }
    function numeroActeNaissanceFormatter(id,row){
        return row.numero_acte_naissance + ' DU ' + row.date_dressers;
    }
    function dateNaissanceFormatter(id, row){
        if(row.heure_naissance_enfant!=null){
            return row.date_naissance_enfants + ' à ' + row.heure_naissance_enfant;
        }else{
            return row.date_naissance_enfants;
        }
    }
    
    function editerNaissanceAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
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
                $("#fonction_declarant, #nationalite_pere, #nationalite_mere, #fonction_pere, #fonction_mere, #fonction_temoin_1, #fonction_temoin_2").val('').trigger('change');
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