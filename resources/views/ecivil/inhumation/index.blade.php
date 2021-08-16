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
       <input type="text" class="form-control" id="searchByName" placeholder="Rechercher par nom du demandeur ou du defunt">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByDate" placeholder="Rechercher par date du décès, de l'inhumation ou demande">
    </div>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-inhumations'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="date_demande_permiss">Date de la demande </th>
            <th data-field="nom_complet_defunt">Defunt </th>
            <th data-field="fonction.libelle_fonction" data-visible="true">Profession </th>
            <th data-field="date_inhumations">Date d'inhumation</th>
            <th data-field="lieu_inhumation">Lieu d'inhumation</th>
            <th data-field="date_decess">Date du d&eacute;c&egrave;s</th>
            <th data-field="nom_complet_demandeur">Demandeur</th>
            <th data-field="montant" data-formatter="montantFormatter" data-align="center">Montant</th>
            <th data-formatter="imageFormatter" data-align="center">PV ou certificat</th>
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
                        <i class="fa fa-mail-forward fa-2x"></i>
                        Gestion des demandes de permis d'inhumation
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" name="idInhumation" ng-hide="true" ng-model="inhumation.id"/>
                    @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Demandeur *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="inhumation.nom_complet_demandeur" id="nom_complet_demandeur" name="nom_complet_demandeur" placeholder="Nom et prénom(s) du demandeur" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>N° de la pi&egrave;ce d'identit&eacute; </label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <input type="text" class="form-control" ng-model="inhumation.numero_piece_demandeur" id="numero_piece_demandeur" name="numero_piece_demandeur" placeholder="N° pièce d'identité du demandeur">
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
                                        <input type="text" class="form-control bfh-phone" ng-model="inhumation.contact_demandeur" id="contact_demandeur" name="contact_demandeur" data-format="(dd) dd-dd-dd-dd" placeholder="Contact du demandeur">
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
                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="inhumation.adresse_demandeur" id="adresse_demandeur" name="adresse_demandeur" placeholder="Adresse du demandeur" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Lieu d'inhumation *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-map-marker"></i>
                                        </div>
                                        <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="inhumation.lieu_inhumation" id="lieu_inhumation" name="lieu_inhumation" placeholder="Lieu d'inhumation" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date d'inhumation *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control" ng-model="inhumation.date_inhumations" id="date_inhumation" name="date_inhumation" placeholder="Ex : 01-02-1994 14:00" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Montant du permis *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <input type="number" min="0" class="form-control" ng-model="inhumation.montant" id="montant" name="montant" placeholder="Montant à payer pour le permis" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Scanne du PV ou certificat du medecin *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-file"></i>
                                        </div>
                                        <input type="file" class="form-control" name="scanne_pv_ou_certificat_deces">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="text-bold text-green"><br/>
                                    <label>
                                        <input type="checkbox" id="inhumer_chez_lui" name="inhumer_chez_lui" ng-model="inhumation.inhumer_chez_lui" ng-checked="inhumation.inhumer_chez_lui">&nbsp; Cochez cette case si le d&eacute;c&egrave;s a &eacute;t&eacute; d&eacute;clar&eacute; dans cette mairie
                                    </label>
                                </h5>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>N° d'acte du d&eacute;c&egrave;s *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <select id="deces_id" name="deces_id"  class="form-control select2" >
                                        <option value="" ng-show="false">-- Selectionner le num&eacute;ro --</option>
                                        @foreach($listeDeces as $deces)
                                        <option value="{{$deces->id}}"> {{$deces->numero_acte_deces.' du '.$deces->date_dressers}}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>N° d'acte de naissance du defunt</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <input type="text" id="numero_acte_naissance_defunt" class="form-control" ng-model="inhumation.numero_acte_naissance_defunt" name="numero_acte_naissance_defunt" placeholder="N° de l'acte de naissance du defunt"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>N° pi&egrave;ce d'identit&eacute; du defunt</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <input type="text" id="numero_piece_defunt" class="form-control" ng-model="inhumation.numero_piece_defunt" name="numero_piece_defunt" placeholder="N° de la pièce d'identité du defunt"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                                <div class="form-group">
                                    <label>Profession du defunt *</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-circle-o"></i>
                                        </div>
                                        <input type="hidden" ng-model="inhumation.fonction_id" name="fonction_id" id="fonctionRecu">
                                        <select id="fonction_id" ng-model="inhumation.fonction_id" ng-init="inhumation.fonction_id=''" class="form-control" required>
                                            <option value="" ng-show="false">-- Selectionner la profession --</option>
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
                                <label>Nom et pr&eacute;nom(s) du defunt *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="inhumation.nom_complet_defunt" id="nom_complet_defunt" name="nom_complet_defunt" placeholder="Nom et prénom(s) du defunt" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Adresse du defunt *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="inhumation.adresse_defunt" id="adresse_defunt" name="adresse_defunt" placeholder="Adresse du defunt" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date du d&eacute;c&egrave;s *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="inhumation.date_decess" id="date_deces" name="date_deces" placeholder="Ex : 01-02-1994 14:00" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Lieu du d&eacute;c&egrave;s *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="inhumation.lieu_deces" id="lieu_deces" name="lieu_deces" placeholder="Lieu du décès" required>
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
                    <input type="text" class="hidden" id="idInhumationSupprimer"  ng-model="inhumation.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer le permis de l'inhumation de <br/><b>@{{inhumation.nom_complet_defunt}}</b></div>
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

<!-- Modal document pv ou certificat -->
<div class="modal fade bs-modal-image" id="image" ng-controller="ImageCtrl" category="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 75%">
            <div class="modal-content">
                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <span style="font-size: 16px;">
                        <i class="fa fa-file-archive-o fa-2x"></i>
                        Permis d'ihnumation de <b>@{{inhumation.nom_complet_defunt}}</b>
                    </span>
                </div>
                <div class="modal-body ">
                    <div class="box-body">
                        <img class="img-responsive pad" src="{{asset('')}}@{{inhumation.scanne_pv_ou_certificat_deces}}" alt="Contrat">
                    </div>
                </div>
            </div>
    </div>
</div>
<script type="text/javascript">
    var ajout = false;
    var $table = jQuery("#table"), rows = [];
    
    appSmarty.controller('formAjoutCtrl', function ($scope) {
        $scope.populateForm = function (inhumation) {
        $scope.inhumation = inhumation;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.inhumation = {};
        };
    }); 
   
    appSmarty.controller('ImageCtrl', function ($scope) {
        $scope.populateForm = function (inhumation) {
            $scope.inhumation = inhumation;
        };
    });
    
    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (inhumation) {
        $scope.inhumation = inhumation;
        };
        $scope.initForm = function () {
        $scope.inhumation = {};
        };
    });
    
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        $("#searchByName").keyup(function (e) { 
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-inhumations'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-inhumations-by-name/' + name});
            }
        });
      
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ""){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-inhumations'])}}"});
            }else{
               $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-inhumations-by-date/' + date});
            }
        });
       
        $('#date_inhumation').datetimepicker({
            timepicker: true,
            formatDate: 'd-m-Y',
            formatTime: 'H:i',
            format: 'd-m-Y H:i',
            local : 'fr',
            minDate : new Date()
        });
        
        $('#date_deces').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            maxDate : new Date()
        });
        
        $('#searchByDate').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
        });
       
        $("#deces_id").select2({width: '100%', allowClear: true}); 
        
        $("#btnModalAjout").on("click", function () {
                $("#deces_id").select2("val", "");
                $('input:checkbox[name=inhumer_chez_lui]').attr('checked',false);
                $("#deces_id").prop('disabled', true);
                $('#nom_complet_defunt').prop('readonly', false);
                $('#numero_acte_naissance_defunt').prop('readonly', false);
                $('#numero_piece_defunt').prop('readonly', false);
                $('#fonction_id').prop('disabled', false);
                $('#date_deces').prop('readonly', false);
                $('#lieu_deces').prop('readonly', false);
                $('#adresse_defunt').prop('readonly', false);
                
                $('#nom_complet_defunt').val("");
                $('#numero_acte_naissance_defunt').val("");
                $('#numero_piece_defunt').val("");
                $('#date_deces').val("");
                $('#lieu_deces').val("");
                $('#adresse_defunt').val("");
                $('#fonction_id').val("");
                $('#fonctionRecu').val("");
        });
        
        $('#inhumer_chez_lui').click(function(){
            if(document.querySelector('#inhumer_chez_lui:checked') !== null)
            {
                document.getElementById("inhumer_chez_lui").checked = true;
                $("#deces_id").prop('disabled', false);
                $('#nom_complet_defunt').prop('readonly', true);
                $('#numero_acte_naissance_defunt').prop('readonly', true);
                $('#numero_piece_defunt').prop('readonly', true);
                $('#fonction_id').prop('disabled', true);
                $('#date_deces').prop('readonly', true);
                $('#lieu_deces').prop('readonly', true);
                $('#adresse_defunt').prop('readonly', true);
            }else{
                document.getElementById("inhumer_chez_lui").checked = false;
                $("#deces_id").prop('disabled', true);
                $('#nom_complet_defunt').prop('readonly', false);
                $('#numero_acte_naissance_defunt').prop('readonly', false);
                $('#numero_piece_defunt').prop('readonly', false);
                $('#fonction_id').prop('disabled', false);
                $('#date_deces').prop('readonly', false);
                $('#lieu_deces').prop('readonly', false);
                $('#adresse_defunt').prop('readonly', false);
                
                $('#nom_complet_defunt').val("");
                $('#numero_acte_naissance_defunt').val("");
                $('#numero_piece_defunt').val("");
                $('#date_deces').val("");
                $('#lieu_deces').val("");
                $('#adresse_defunt').val("");
                $('#fonction_id').val("");
                $('#fonctionRecu').val("");
            }
        });
        $("#fonction_id").change(function (e) {
            var fonction = $('#fonction_id').val();
            $('#fonctionRecu').val(fonction);
        });
        
        $("#deces_id").change(function (e) {
            var deces_id = $("#deces_id").val();
            $.getJSON("../e-civil/find-acte-deces-by-id/" + deces_id, function (reponse) {
                if(reponse.total>0){
                    $.each(reponse.rows, function (index, deces) { 
                        $('#nom_complet_defunt').val(deces.nom_complet_decede);
                        $('#numero_acte_naissance_defunt').val(deces.numero_acte_naissance_decede);
                        $('#numero_piece_defunt').val(deces.numero_piece_identite_decede);
                        $('#date_deces').val(deces.date_decess);
                        $('#lieu_deces').val(deces.lieu_deces);
                        $('#adresse_defunt').val(deces.adresse_decede);
                        $('#fonction_id').val(deces.fonction.id);
                        $('#fonctionRecu').val(deces.fonction.id);
                    });
                }else{
                        $('#nom_complet_defunt').val("");
                        $('#numero_acte_naissance_defunt').val("");
                        $('#numero_piece_defunt').val("");
                        $('#date_deces').val("");
                        $('#lieu_deces').val("");
                        $('#adresse_defunt').val("");
                        $('#fonction_id').val("");
                        $('#fonctionRecu').val("");
                   alert('Aucun enregistrement trouvé');
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
                var url = "{{route('e-civil.inhumations.store')}}";
             }else{
                var methode = 'POST';
                var url = "{{route('e-civil.update-inhumation')}}";
             }
             var formData = new FormData($(this)[0]);
            editerInhumationsAction(methode, url, $(this), formData, $ajaxLoader, $table, ajout);
        });
        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idInhumationSupprimer").val();
            var formData = $(this).serialize();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('inhumations/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });
    
    function updateRow(idInhumation) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var inhumation =_.findWhere(rows, {id: idInhumation});
         $scope.$apply(function () {
            $scope.populateForm(inhumation);
        });
        if(inhumation.inhumer_chez_lui==1){
                $("#deces_id").select2("val", inhumation.deces_id);
                document.getElementById("inhumer_chez_lui").checked = true;
                $("#deces_id").prop('disabled', false);
                $('#nom_complet_defunt').prop('readonly', true);
                $('#numero_acte_naissance_defunt').prop('readonly', true);
                $('#numero_piece_defunt').prop('readonly', true);
                $('#fonction_id').prop('disabled', true);
                $('#date_deces').prop('readonly', true);
                $('#lieu_deces').prop('readonly', true);
                $('#adresse_defunt').prop('readonly', true);
        }else{
                $("#deces_id").select2("val", "");
                $("#deces_id").prop('disabled', true);
                $('#nom_complet_defunt').prop('readonly', false);
                $('#numero_acte_naissance_defunt').prop('readonly', false);
                $('#numero_piece_defunt').prop('readonly', false);
                $('#fonction_id').prop('disabled', false);
                $('#date_deces').prop('readonly', false);
                $('#lieu_deces').prop('readonly', false);
                $('#adresse_defunt').prop('readonly', false);
        }
        $(".bs-modal-ajout").modal("show");
    }
    
    function voirImg(idImage) {
        var $scope = angular.element($("#image")).scope();
        var inhumation =_.findWhere(rows, {id: idImage});
         $scope.$apply(function () {
            $scope.populateForm(inhumation);
        });
        $(".bs-modal-image").modal("show");
    }

    function deleteRow(idInhumation) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var inhumation =_.findWhere(rows, {id: idInhumation});
           $scope.$apply(function () {
              $scope.populateForm(inhumation);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function imageFormatter(id, row) { 
//        return row.scanne_pv_ou_certificat_deces ? '<img width=50 height=50 style="cursor: pointer;" title="Voir le document" onClick="javascript:voirImg(' + row.id + ');" src="{{asset('')}}' + row.scanne_pv_ou_certificat_deces+'"/>' : "";
          return row.scanne_pv_ou_certificat_deces ? "<a target='_blank' href='" + basePath + '/' + row.scanne_pv_ou_certificat_deces + "'>Voir le document</a>" : "";
    }
    function montantFormatter(montant){
        return '<span class="text-bold">' + $.number(montant)+ '</span>';
    }
    function optionFormatter(id, row) { 
            return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                    <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    
    function editerInhumationsAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
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
                    $("#deces_id").select2("val", "");
                    $("#deces_id").prop('disabled', true);
                    $('#nom_complet_defunt').prop('readonly', false);
                    $('#numero_acte_naissance_defunt').prop('readonly', false);
                    $('#numero_piece_defunt').prop('readonly', false);
                    $('#fonction_id').prop('disabled', false);
                    $('#date_deces').prop('readonly', false);
                    $('#lieu_deces').prop('readonly', false);
                    $('#adresse_defunt').prop('readonly', false);
                    $('#nom_complet_defunt').val("");
                    $('#numero_acte_naissance_defunt').val("");
                    $('#numero_piece_defunt').val("");
                    $('#date_deces').val("");
                    $('#lieu_deces').val("");
                    $('#adresse_defunt').val("");
                    $('#fonction_id').val("");
                    $('#fonctionRecu').val("");
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


