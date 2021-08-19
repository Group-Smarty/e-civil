@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Taxe')
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
<script src="{{asset('assets/js/underscore-min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
<script src="{{asset('assets/js/fonction_crude.js')}}"></script>
<script src="{{asset('assets/js/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{asset('assets/plugins/Bootstrap-form-helpers/js/bootstrap-formhelpers-phone.js')}}"></script>
<script src="{{asset('assets/plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/jquery.datetimepicker.min.css')}}" rel="stylesheet">
<div class="col-md-2">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByNumero" placeholder="Rech. N° CC ou registre">
    </div>
</div>
<div class="col-md-2">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByDate" placeholder="Rech. par date déclar.">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <select id="searchByContribuable"  class="form-control">
            <option value="0">--- Tous les contribuables ---</option>
            @foreach($contribuables as $contribuable)
            <option value="{{$contribuable->id}}"> {{$contribuable->nom_complet.' N° id : '.$contribuable->numero_identifiant}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <select id="searchByLocalite"  class="form-control">
            <option value="0">--- Toutes les localit&eacute;s ---</option>
            @foreach($localites as $localite)
            <option value="{{$localite->id}}"> {{$localite->libelle_localite}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="col-md-1">
    <a class="btn btn-success pull-right" onclick="imprimePdf()">Imprimer</a><br/>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('taxe',['action'=>'liste-declaration-activites'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="true">
    <thead>
        <tr>
            <th data-field="date_declarations">Date d&eacute;cl. </th>
            <th data-field="nom_structure">Structure </th>
            <th data-field="type_societe.libelle_type_societe">Type soci&eacute;t&eacute;</th>
            <th data-field="nom_activite">Activit&eacute; </th>
            <th data-field="secteur.libelle_secteur">Secteur d'activit&eacute; </th>
            <th data-field="contact">Contact</th>
            <th data-field="localite.libelle_localite">Localit&eacute;</th>
            <th data-field="situation_geographique" data-visible="false">Adresse geo.</th>
            <th data-field="type_taxe.libelle_type_taxe">Type taxe</th>
            <th data-field="contribuable.nom_complet">Contribuable</th>
            <th data-field="numero_cc" data-visible="false">N° CC </th>
            <th data-field="numero_registre" data-visible="false">N° registre </th>
            <th data-field="longitude" data-visible="false">Longitude</th>
            <th data-field="latitude" data-visible="false">Latitude</th>
            <th data-field="adresse_postale" data-visible="false">Adresse postale</th>
            <th data-field="email" data-visible="false">E-mail</th>
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
                        <i class="fa fa-bookmark fa-2x"></i>
                        Gestion des d&eacute;clarations d'activit&eacute;s
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idDeclarationActiviteModifier" ng-hide="true" ng-model="declarationActivite.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contribuable </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <select name="contribuable_id" id="contribuable_id" class="form-control" required>
                                        <option value="">Selectionner le contribuable</option>
                                        @foreach($contribuables as $contribuable)
                                        <option value="{{$contribuable->id}}"> {{$contribuable->nom_complet.' N° identif : '.$contribuable->numero_identifiant}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date de d&eacute;claration *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="declarationActivite.date_declarations" id="date_declaration" name="date_declaration" value="{{date("d-m-Y")}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nom de l'activit&eacute;  *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-pencil"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="declarationActivite.nom_activite" id="nom_activite" name="nom_activite" placeholder="Nom de l'activité à déclarer" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nom de la structure  </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-pencil"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="declarationActivite.nom_structure" id="nom_structure" name="nom_structure" placeholder="Nom de le structure à déclarer" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>N° du Compte Contribuable  *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-pencil"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="declarationActivite.numero_cc" id="numero_cc" name="numero_cc" placeholder="N° du compte contribuable" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>N° du registre *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-pencil"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="declarationActivite.numero_registre" id="numero_registre" name="numero_registre" placeholder="N° du registre" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Type de soci&eacute;t&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <select name="type_societe_id" id="type_societe_id" class="form-control" required>
                                        <option value="">-- Selectionner le type de soci&eacute;t&eacute; --</option>
                                        @foreach($typeSocietes as $typeSociete)
                                        <option value="{{$typeSociete->id}}"> {{$typeSociete->libelle_type_societe}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Secteur d'activit&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <select name="secteur_id" id="secteur_id" class="form-control" required>
                                        <option value="">-- Selectionner le secteur --</option>
                                        @foreach($secteurs as $secteur)
                                        <option value="{{$secteur->id}}"> {{$secteur->libelle_secteur}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Type de taxe *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <select name="type_taxe_id" id="type_taxe_id" class="form-control" required>
                                        <option value="">-- Selectionner la taxe --</option>
                                        @foreach($typeTaxes as $typeTaxe)
                                        <option value="{{$typeTaxe->id}}"> {{$typeTaxe->libelle_type_taxe}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Montant taxe *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <input type="text" pattern="[0-9]*" class="form-control" ng-model="declarationActivite.montant_taxe" id="montant_taxe" name="montant_taxe" placeholder="Montant taxe" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Localit&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <select name="localite_id" id="localite_id" class="form-control" required>
                                        <option value="">-- Selectionner la localit&eacute; --</option>
                                        @foreach($localites as $localite)
                                            <option value="{{$localite->id}}"> {{$localite->libelle_localite}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Adresse g&eacute;ographique  *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-signs"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="declarationActivite.situation_geographique" id="situation_geographique" name="situation_geographique" placeholder="Adresse géographique de la structure" required>
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
                                    <input type="text" class="form-control bfh-phone" ng-model="declarationActivite.contact" id="contact" name="contact" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" placeholder="Contact ">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>E-mail </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-at"></i>
                                    </div>
                                    <input type="email" class="form-control" ng-model="declarationActivite.email" id="email" name="email" placeholder="Adresse mail">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Adresse postale</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="declarationActivite.adresse_postale" id="adresse_postale" name="adresse_postale" placeholder="Adresse postale">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Longitude </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list-ol"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="declarationActivite.longitude" id="longitude" name="longitude" placeholder="Longitude">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Latitude</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list-ol"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="declarationActivite.latitude" id="latitude" name="latitude" placeholder="Latitude">
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
                    <input type="text" class="hidden" id="idDeclarationActiviteSupprimer"  ng-model="declarationActivite.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer le declaration d'activite de la soci&eacute;t&eacute; <br/><b>@{{declarationActivite.nom_structure}}</b></div>
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
        $scope.populateForm = function (declarationActivite) {
        $scope.declarationActivite = declarationActivite;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.declarationActivite = {};
        };
    });

    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (declarationActivite) {
        $scope.declarationActivite = declarationActivite;
        };
        $scope.initForm = function () {
        $scope.declarationActivite = {};
        };
    });

    $(function () {
    	$table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });

       $('#date_declaration, #searchByDate').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            maxDate : new Date(),
        }); 
        
        $("#contribuable_id, #secteur_id, #type_societe_id, #type_taxe_id, #localite_id, #searchByContribuable, #searchByLocalite").select2({width: '100%', allowClear: true});
        
        $("#btnModalAjout").on("click", function () {
            $("#contribuable_id, #secteur_id, #type_societe_id, #type_taxe_id, #localite_id").val('').trigger('change');
        });
        
        $("#searchByNumero").keyup(function (e) {
            var numero = $("#searchByNumero").val();
            if(numero == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('taxe', ['action' => 'liste-declaration-activites'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-activites-by-numero/' + numero});
            }
        });

        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();

            if(date == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('taxe', ['action' => 'liste-declaration-activites'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-activites-by-date/' + date});
            }
        });
        
        $("#searchByContribuable").change(function (e) {
     
            var contribuable = $("#searchByContribuable").val();
            var localite = $("#searchByLocalite").val();
            
            if(contribuable == 0 && localite == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('taxe', ['action' => 'liste-declaration-activites'])}}"});
            }
            if(contribuable != 0 && localite == 0){
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-activites-by-contribuable/' + contribuable});
            }
            if(contribuable == 0 && localite != 0){
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-activites-by-localite/' + localite});
            }
            if(contribuable != 0 && localite != 0){
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-activites-by-localite-contribuable/' + localite + '/' + contribuable});
            }
        });
        
        $("#searchByLocalite").change(function (e) {
     
            var contribuable = $("#searchByContribuable").val();
            var localite = $("#searchByLocalite").val();
            
            if(contribuable == 0 && localite == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('taxe', ['action' => 'liste-declaration-activites'])}}"});
            }
            if(contribuable != 0 && localite == 0){
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-activites-by-contribuable/' + contribuable});
            }
            if(contribuable == 0 && localite != 0){
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-activites-by-localite/' + localite});
            }
            if(contribuable != 0 && localite != 0){
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-activites-by-localite-contribuable/' + localite + '/' + contribuable});
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
                var url = "{{route('taxe.declaration-activites.store')}}";
             }else{
                var id = $("#idDeclarationActiviteModifier").val();
                var methode = 'PUT';
                var url = 'declaration-activites/' + id;
             }
            editerDeclarationActiviteAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idDeclarationActiviteSupprimer").val();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('declaration-activites/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });

    function updateRow(idDeclarationActivite) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var declarationActivite =_.findWhere(rows, {id: idDeclarationActivite});
         $scope.$apply(function () {
            $scope.populateForm(declarationActivite);
        });
        
        $('#contribuable_id').select2("val", declarationActivite.contribuable_id);
        $('#type_societe_id').select2("val", declarationActivite.type_societe_id);
        $('#secteur_id').select2("val", declarationActivite.secteur_id);
        $('#type_taxe_id').select2("val", declarationActivite.type_taxe_id);
        $('#localite_id').select2("val", declarationActivite.localite_id);
        
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idDeclarationActivite) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var declarationActivite =_.findWhere(rows, {id: idDeclarationActivite});
           $scope.$apply(function () {
              $scope.populateForm(declarationActivite);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function optionFormatter(id, row) {
        return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    
    function mailFormatter(mail){
        return mail ? '<a href="mailto:' + mail + '">' + mail + '</a>' : "";
    }

     function imprimePdf(){
        var date = $("#searchByDate").val();
        var contribuable = $("#searchByContribuable").val();
        var localite = $("#searchByLocalite").val();

        if(date == "" && contribuable == 0 && localite == 0){
            window.open("../taxe/liste-activites-pdf/" ,'_blank');
        }
        if(date != "" && contribuable == 0 && localite == 0){
            window.open("../taxe/liste-activites-by-date-pdf/" + date,'_blank');  
        }
        if(date == "" && contribuable != 0 && localite == 0){
            window.open("../taxe/liste-activites-by-contribuables-pdf/" + contribuable,'_blank');  
        }
        if(date == "" && contribuable == 0 && localite != 0){
            window.open("../taxe/liste-activites-by-localites-pdf/" + localite,'_blank');  
        }
        if(date == "" && contribuable != 0 && localite != 0){
            window.open("../taxe/liste-activites-by-contribuable-localite-pdf/" + contribuable + "/" + localite,'_blank');  
        }
    }
    
    function editerDeclarationActiviteAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
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
                    $("#contribuable_id, #secteur_id, #type_societe_id, #type_taxe_id, #localite_id").val('').trigger('change');
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