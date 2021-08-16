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
       <input type="text" class="form-control" id="searchByNumeroPiece" placeholder="Rechercher par N° de piéce d'identité">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByDate" placeholder="Rechercher par date de naissance ou demande">
    </div>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-certificat-vie-entretien'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="date_demande_certificats">Date de la demande </th>
            <th data-field="nom_complet_personne">Concern&eacute; </th>
            <th data-field="adresse_personne">Adresse </th>
            <th data-field="contact_personne">Contact </th>
            <th data-field="fonction.libelle_fonction" data-visible="true">Profession </th>
            <th data-field="date_naissances">Date de naissance</th>
            <th data-field="lieu_naissance">Lieu de naissance</th>
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
                        <i class="fa fa-group fa-2x"></i>
                        Gestion des demandes de certificat de vie et d'entretien
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idCertificatModifier" ng-hide="true" ng-model="certificat.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>N° de la pi&egrave;ce d'identit&eacute; </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="certificat.numero_piece_personne" id="numero_piece_personne" name="numero_piece_personne" placeholder="N° pièce d'identité du démandeur">
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
                                    <input type="text" class="form-control bfh-phone" ng-model="certificat.contact_personne" id="contact_personne" name="contact_personne" data-format="(dd) dd-dd-dd-dd" placeholder="Contact du demandeur">
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
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificat.adresse_personne" id="adresse_personne" name="adresse_personne" placeholder="Adresse du demandeur" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Profession </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-circle-o"></i>
                                    </div>
                                    <select id="fonction_id" name="fonction_id" class="form-control">
                                        <option value="">-- Selectionner la profession --</option>
                                        @foreach($fonctions as $fonction)
                                        <option value="{{$fonction->id}}"> {{$fonction->libelle_fonction}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <div class="row">
                        <div class="col-md-3">
                                <div class="form-group">
                                    <label>Montant de la demande </label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <input type="number" min="0" class="form-control" ng-model="certificat.montant" id="montant" name="montant" placeholder="Montant de la demande">
                                    </div>
                                </div>
                            </div>
                        <div class="col-md-9"> <br/>
                            <h5 class="text-bold text-green">
                                <label>
                                    <input type="checkbox" id="etat_civil_naissance" name="etat_civil_naissance" ng-model="certificat.etat_civil_naissance" ng-checked="certificat.etat_civil_naissance">&nbsp; Cochez cette case si cette personne fait la demande dans son lieu de naissance
                                </label>
                            </h5>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-3" id="div_numero_acte_naissance">
                            <div class="form-group">
                                <label>N° d'acte de naissance (Facultatif)</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <input type="text" id="numero_acte_naissance_personne" class="form-control" ng-model="certificat.numero_acte_naissance_personne" name="numero_acte_naissance_personne" placeholder="N° de l'acte de naissance"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3" id="div_naissance">
                            <div class="form-group">
                                <label>N° d'acte de naissance (Facultatif)</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <select id="naissance_id" name="naissance_id"  class="form-control select2">
                                        <option value="" ng-show="false">-- Sectionner le num&eacute;ro --</option>
                                        @foreach($naissances as $naissance)
                                        <option value="{{$naissance->id}}"> {{$naissance->numero_acte_naissance.' du '.$naissance->date_dressers}}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nom de et pr&eacute;nom(s) *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificat.nom_complet_personne" id="nom_complet_personne" name="nom_complet_personne" placeholder="Nom et prénom(s)" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date de naissance *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="certificat.date_naissances" id="date_naissance" name="date_naissance" placeholder="Ex : 01-01-1994" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Lieu du naissance *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificat.lieu_naissance" id="lieu_naissance" name="lieu_naissance" placeholder="Lieu de naissance" required>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="row"> 
                        <div class="col-md-12"> 
                            <h5 class="text-bold text-green">
                                <label>
                                    Liste des enfants en charge de la personne
                                </label>
                            </h5>
                        </div>
                    </div>
                    <div id="div_enregistrement">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>N° extrait de l'enfant *</label>
                                    <input type="text" class="form-control" id="numero_extrait_enfant" placeholder="Ex : N° XXXX DU XX-XX-XXXX">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nom et pr&eacute;nom(s) de l'enfant *</label>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" id="nom_complet_enfant" placeholder="Nom et prénom(s) de l'enfant">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Date de naissance *</label>
                                    <input type="text" class="form-control" id="date_naissance_enfant" placeholder="EX : 01-01-1994">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Lieu de naissance *</label>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" id="lieu_naissance_enfant" placeholder="Lieu de naissance">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group"><br/>
                                    <button type="button" class="btn btn-primary btn-xs  add-row pull-left"><i class="fa fa-plus">Ajouter</i></button>
                                </div>
                            </div>
                        </div><br/>
                        <table class="table table-info table-striped box box-warning">
                            <thead>
                                <tr>
                                    <th>Cochez</th>
                                    <th>N° extrait de naissance</th>
                                    <th>Nom et pr&eacute;nom(s)</th>
                                    <th>Date de naissance</th>
                                    <th>Lieu de naissance</th>
                                </tr>
                            </thead>
                            <tbody class="enfants">

                            </tbody>
                        </table>
                        <button type="button" class="delete-row">Supprimer ligne</button>
                    </div>
                    <div id="div_update">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" id="btnModalAjoutChild" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus">Ajouter un enfant</i></button>
                                </div>
                            </div> 
                        </div><br/>
                        <table id="tableChildInfo" class="table table-success table-striped box box-info"
                               data-pagination="true"
                               data-search="false"
                               data-toggle="table"
                               data-unique-id="id"
                               data-show-toggle="false">
                            <thead>
                                <tr>
                                    <th data-field="numero_extrait_enfant">N° extrait </th>
                                    <th data-field="nom_complet_enfant">Nom et pr&eacute;nom(s) </th>
                                    <th data-field="date_naissances">Date de naissance </th>
                                    <th data-field="lieu_naissance_enfant">Lieu de naissance </th>
                                    <th data-field="id" data-formatter="optionChildFormatter" data-width="100px" data-align="center"><i class="fa fa-wrench"></i></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-send"><span class="overlay loader-overlay"> <i class="fa fa-refresh fa-spin"></i> </span>Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal add child -->
<div class="modal fade bs-modal-add-child" category="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width:65%">
        <form id="formAjoutChild" ng-controller="formAjoutChildCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Ajout d'un enfant
                </div>
                @csrf
                <div class="modal-body ">
                   <input type="text" class="hidden" id="idChildModifier"  ng-model="child.id"/>
                   <input type="text" class="hidden" id="papa"  name="idPapa"/>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>N° extrait de naissance *</label>
                                <input type="text" class="form-control" id="numero_extrait_child" name="numero_extrait_child" ng-model="child.numero_extrait_enfant" placeholder="Ex : N° XXXX DU XX-XX-XXXX" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nom et pr&eacute;nom(s) de l'enfant *</label>
                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" id="nom_complet_child" name="nom_complet_child" ng-model="child.nom_complet_enfant" placeholder="Nom et prénom(s) de l'enfant" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date de naissance *</label>
                                <input type="text" class="form-control" id="date_naissance_child" name="date_naissance_child" placeholder="EX : 01-01-1994" ng-model="child.date_naissances" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Lieu de naissance *</label>
                                <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" id="lieu_naissance_child" name="lieu_naissance_child" ng-model="child.lieu_naissance_enfant" placeholder="Lieu de naissance de l'enfant" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-send"><span class="overlay loader-overlay"> <i class="fa fa-refresh fa-spin"></i> </span>Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal suppresion -->
<div class="modal fade bs-modal-supprimer-child" category="dialog" data-backdrop="static">
    <div class="modal-dialog ">
        <form id="formSupprimerChild" ng-controller="formSupprimerChildCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        Confimation de la suppression
                </div>
                @csrf
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idChildSupprimer"  ng-model="child.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer l'enfant <br/><b>@{{child.nom_complet_enfant}}</b></div>
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
                    <input type="text" class="hidden" id="idCertificatSupprimer"  ng-model="certificat.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer la demande de <br/><b>@{{certificat.nom_complet_personne}}</b></div>
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
    var ajoutChild = false;
    var $table = jQuery("#table"), rows = [], $tableChildInfo = jQuery("#tableChildInfo"), rowsChild = [];
    
    appSmarty.controller('formAjoutCtrl', function ($scope) {
        $scope.populateForm = function (certificat) {
        $scope.certificat = certificat;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.certificat = {};
        };
    }); 
    
    appSmarty.controller('formAjoutChildCtrl', function ($scope) {
        $scope.populateChildForm = function (child) {
        $scope.child = child;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.child = {};
        };
    }); 
    
    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (certificat) {
        $scope.certificat = certificat;
        };
        $scope.initForm = function () {
        $scope.certificat = {};
        };
    });
    
     appSmarty.controller('formSupprimerChildCtrl', function ($scope) {
        $scope.populateSupChildForm = function (child) {
        $scope.child = child;
        };
        $scope.initForm = function () {
        $scope.child = {};
        };
    });
    
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        $tableChildInfo.on('load-success.bs.table', function (e, data) {
            rowsChild = data.rows; 
        });
        $("#div_enregistrement").show();
        $("#div_update").hide();
       
        $("#searchByName").keyup(function (e) { 
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-vie-entretien'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-vie-entretien-by-name/' + name});
            }
        });
        $("#searchByNumeroPiece").keyup(function (e) { 
            var numero = $("#searchByNumeroPiece").val();
            if(numero == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-vie-entretien'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-vie-entretien-by-piece-identite/' + numero});
            }
        });
      
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ""){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-vie-entretien'])}}"});
            }else{
               $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-vie-entretien-by-date/' + date});
            }
        });
       
        $('#searchByDate').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
        });
        $('#date_naissance_enfant, #date_naissance, #date_naissance_child').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            maxDate : new Date()
        });
       
        $("#naissance_id, #fonction_id").select2({width: '100%', allowClear: true});
        $("#div_naissance").hide();
        
        $("#btnModalAjoutChild").on("click", function () {
            ajoutChild = true;
            var papa = $("#idCertificatModifier").val();
            document.forms["formAjoutChild"].reset();
             $("#papa").val(papa);
            $(".bs-modal-add-child").modal("show");
        });
        
        $("#btnModalAjout").on("click", function () {
            $("#div_enregistrement").show();
            $("#div_update").hide();
            $("#div_naissance").hide();
            $("#div_numero_acte_naissance").show();
            $("#naissance_id, #fonction_id").val('').trigger('change');
            $('input:checkbox[name=etat_civil_naissance]').attr('checked',false);

            $('#nom_complet_personne').prop('readonly', false);
            $('#date_naissance').prop('readonly', false);
            $('#lieu_naissance').prop('readonly', false);
                
            $('#nom_complet_personne').val("");
            $('#date_naissance').val("");
            $('#lieu_naissance').val("");
        });
        
        $('#etat_civil_naissance').click(function(){
            if(document.querySelector('#etat_civil_naissance:checked') !== null)
            {
                document.getElementById("etat_civil_naissance").checked = true;
                $("#div_naissance").show();
                $("#div_numero_acte_naissance").hide();
                $("#naissance_id").select2("val", "");
                $('#nom_complet_personne').prop('readonly', true);
                $('#date_naissance').prop('readonly', true);
                $('#lieu_naissance').prop('readonly', true);
            }else{
                document.getElementById("etat_civil_naissance").checked = false;
                $("#div_naissance").hide();
                $("#div_numero_acte_naissance").show();
                $("#naissance_id").select2("val", "");
                
                $('#nom_complet_personne').prop('readonly', false);
                $('#date_naissance').prop('readonly', false);
                $('#lieu_naissance').prop('readonly', false);
                
                $('#nom_complet_personne').val("");
                $('#date_naissance').val("");
                $('#lieu_naissance').val("");
            }
        });
        
        $("#naissance_id").change(function (e) {
            var naissance_id = $("#naissance_id").val();
            $.getJSON("../e-civil/find-acte-naissance-by-id/" + naissance_id, function (reponse) {
                if(reponse.total>0){
                    $.each(reponse.rows, function (index, naissance) { 
                        var nom_complet_personne = naissance.nom_enfant + ' ' + naissance.prenom_enfant;
                        $('#nom_complet_personne').val(nom_complet_personne);
                        $('#date_naissance').val(naissance.date_naissance);
                        $('#lieu_naissance').val(naissance.lieu_naissance_enfant);
                    });
                }else{
                        $('#nom_complet_personne').val("");
                        $('#date_naissance').val("");
                        $('#lieu_naissance').val("");
                }
           });
        });
        
        $(".add-row").click(function () {
            if ($("#numero_extrait_enfant").val() != '' && $("#nom_complet_enfant").val() != '' && $("#date_naissance_enfant").val() != '' && $("#lieu_naissance_enfant").val() != '') {
                var numero_extrait_enfant = $("#numero_extrait_enfant").val();
                var nom_complet_enfant = $("#nom_complet_enfant").val();
                var date_naissance_enfant = $("#date_naissance_enfant").val();
                var lieu_naissance_enfant = $("#lieu_naissance_enfant").val();
              
                var markup = "<tr><td><input type='checkbox' name='record'></td><td><input type='hidden' name='numero_extrait_enfants[]' value='" + numero_extrait_enfant + "'>" + numero_extrait_enfant + "</td><td><input type='hidden' name='nom_complet_enfants[]' value='" + nom_complet_enfant + "'>" + nom_complet_enfant + "</td><td><input type='hidden' name='date_naissance_enfants[]' value='" + date_naissance_enfant + "'>" + date_naissance_enfant + "</td><td><input type='hidden' name='lieu_naissance_enfants[]' value='" + lieu_naissance_enfant + "'>" + lieu_naissance_enfant + "</td></tr>";
                $(".enfants").append(markup);
                $("#numero_extrait_enfant").val("");
                $("#nom_complet_enfant").val("");
                $("#date_naissance_enfant").val("");
                $("#lieu_naissance_enfant").val("");
            }else{
                alert("Veillez sasir toutes les informations de l'enfant !");
            }
        });
        
        // Find and remove selected table rows
        $(".delete-row").click(function () {
            $("table tbody").find('input[name="record"]').each(function () {
                if ($(this).is(":checked")) {
                    $(this).parents("tr").remove();
                }else{
                   alert("Cochez la ligne que vous souhaitez supprimer !"); 
                }
            });
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
                var url = "{{route('e-civil.certificat-vie-entretien.store')}}";
             }else{
                var id = $("#idCertificatModifier").val();
                var methode = 'PUT';
                var url = 'certificat-vie-entretien/' + id;
             }
            editerCertificatAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });
        
        $("#formAjoutChild").submit(function (e) {
            e.preventDefault();
            var $valid = $(this).valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            }
            var $ajaxLoader = $("#formAjoutChild .loader-overlay");

            if (ajoutChild==true) {
                var methode = 'POST';
                var url = "{{route('e-civil.enfant-en-charge.store')}}";
             }else{
                var id = $("#idChildModifier").val();
                var methode = 'PUT';
                var url = 'enfant-en-charge/' + id;
             }
            editerChildAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $tableChildInfo, ajoutChild);
        });
        
        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idCertificatSupprimer").val();
            var formData = $(this).serialize();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('certificat-vie-entretien/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
        
        $("#formSupprimerChild").submit(function (e) {
            e.preventDefault();
            var id = $("#idChildSupprimer").val();
            var formData = $(this).serialize();
            var $question = $("#formSupprimerChild .question");
            var $ajaxLoader = $("#formSupprimerChild .processing");
            supprimerChildAction('enfant-en-charge/' + id, $(this).serialize(), $question, $ajaxLoader, $tableChildInfo);
        });
    });
    
    function updateRow(idCertificat) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var certificat =_.findWhere(rows, {id: idCertificat});
         $scope.$apply(function () {
            $scope.populateForm(certificat);
        });
        if(certificat.etat_civil_naissance==1){
                $("#naissance_id").select2("val", certificat.naissance_id);
                $("#div_naissance").show();
                $("#div_numero_acte_naissance").hide();
                $('#nom_complet_personne').prop('readonly', true);
                $('#date_naissance').prop('readonly', true);
                $('#lieu_naissance').prop('readonly', true);
        }else{
                $("#div_naissance").hide();
                $("#div_numero_acte_naissance").show();
                $("#naissance_id").select2("val", "");
                
                $('#nom_complet_personne').prop('readonly', false);
                $('#date_naissance').prop('readonly', false);
                $('#lieu_naissance').prop('readonly', false);
        }
        certificat.fonction_id != null ? $('#fonction_id').select2("val", certificat.fonction_id) : $('#fonction_id').select2("val", ""); 
        $tableChildInfo.bootstrapTable('refreshOptions', {url: "../e-civil/liste-enfants-en-charge/" + idCertificat});
        $("#div_enregistrement").hide();
        $("#div_update").show();
        $(".bs-modal-ajout").modal("show");
    }
    
    function deleteRow(idCertificat) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var certificat =_.findWhere(rows, {id: idCertificat});
           $scope.$apply(function () {
              $scope.populateForm(certificat);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function updateChildRow(idChild){
        ajoutChild = false;
        var $scope = angular.element($("#formAjoutChild")).scope();
        var child =_.findWhere(rowsChild, {id: idChild});
         $scope.$apply(function () {
            $scope.populateChildForm(child);
        });
        $(".bs-modal-add-child").modal("show");
    }
    
    function deleteChildRow(idChild){
        var $scope = angular.element($("#formSupprimerChild")).scope();
        var child =_.findWhere(rowsChild, {id: idChild});
         $scope.$apply(function () {
            $scope.populateSupChildForm(child);
        });
        $(".bs-modal-supprimer-child").modal("show");
    }
    
    function extraiRow(idCertificat){
        window.open("../e-civil/fiche-certificat-vie-entretien/" + idCertificat ,'_blank')
    }
    
    function montantFormatter(montant){
        return '<span class="text-bold">' + $.number(montant)+ '</span>';
    }
    function optionFormatter(id, row) { 
            return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                   <button class="btn btn-xs btn-default" data-placement="left" data-toggle="tooltip" title="Imprimer la fiche" onClick="javascript:extraiRow(' + id + ');"><i class="fa fa-print"></i></button>\n\
                    <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    
    function optionChildFormatter(id, row) { 
            return '<button type="button" class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateChildRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                    <button type="button" class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteChildRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    
   function editerCertificatAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
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
                    $("#naissance_id, #fonction_id").val('').trigger('change');
                    $('#nom_complet_personne').prop('readonly', false);
                    $('#date_naissance').prop('readonly', false);
                    $('#lieu_naissance').prop('readonly', false);
                    $('#nom_complet_personne').val("");
                    $('#date_naissance').val("");
                    $('#lieu_naissance').val("");
                    document.getElementById("etat_civil_naissance").checked = false;
                    $("table tbody").find('input[name="record"]').each(function () {
                        $(this).parents("tr").remove();
                    });
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
   function editerChildAction(methode, url, $formObject, formData, $ajoutLoader, $tableChildInfo, ajoutChild = true) {
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
                if (ajoutChild) { //creation
                    $tableChildInfo.bootstrapTable('refresh');
                    $(".bs-modal-add-child").modal("hide");
                } else { //Modification
                    $tableChildInfo.bootstrapTable('updateByUniqueId', {
                        id: reponse.data.id,
                        row: reponse.data
                    });
                    $tableChildInfo.bootstrapTable('refresh');
                    $(".bs-modal-add-child").modal("hide");
                }
                $formObject.trigger('eventAjouter', [reponse.data]);
                ajout = false;
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
    
    //Supprimer un enfant
function supprimerChildAction(url, formData, $question, $ajaxLoader, $tableChildInfo) {
    jQuery.ajax({
        type: "DELETE",
        url: url,
        cache: false,
        data: formData,
        success: function (reponse) {
            if (reponse.code === 1) {
                 $tableChildInfo.bootstrapTable('remove', {
                    field: 'id',
                    values: [reponse.data.id]
                });
                $tableChildInfo.bootstrapTable('refresh');
                $(".bs-modal-supprimer-child").modal("hide");
                ajout = false;
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
            //alert(res.message);
            //alert(Object.getOwnPropertyNames(res));
            $.gritter.add({
                // heading of the notification
                title: "E-Civil",
                // the text inside the notification
                text: res.message,
                sticky: false,
                image: basePath + "/assets/img/gritter/confirm.png"
            });
            $ajaxLoader.hide();
            $question.show();
        },
        beforeSend: function () {
            $question.hide();
            $ajaxLoader.show();
        },
        complete: function () {
            $ajaxLoader.hide();
            $question.show();
        }
    });
}
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection


