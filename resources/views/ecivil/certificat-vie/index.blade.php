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
               data-url="{{url('e-civil',['action'=>'liste-certificat-vie'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="date_demande_certificats">Date de la demande </th>
            <th data-field="nom_complet_naissance">Concern&eacute;(e) </th>
            <th data-field="adresse_demandeur">Adresse </th>
            <th data-field="contact_demandeur">Contact </th>
            <th data-field="fonction.libelle_fonction" data-visible="true">Profession </th>
            <th data-field="date_naissances">Date de naissance</th>
            <th data-field="lieu_naissance">Lieu de naissance</th>
            <th data-field="nom_complet_pere">P&egrave;re</th>
            <th data-field="nom_complet_mere">M&egrave;re</th>
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
                        <i class="fa fa-heartbeat fa-2x"></i>
                        Gestion des demandes de certificat de vie
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
                                    <input type="text" class="form-control" ng-model="certificat.numero_piece_demandeur" id="numero_piece_demandeur" name="numero_piece_demandeur" placeholder="N° pièce d'identité du demandeur">
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
                                    <input type="text" class="form-control bfh-phone" ng-model="certificat.contact_demandeur" id="contact_demandeur" name="contact_demandeur" data-format="(dd) dd-dd-dd-dd" placeholder="Contact du demandeur">
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
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificat.adresse_demandeur" id="adresse_demandeur" name="adresse_demandeur" placeholder="Adresse du demandeur" required>
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
                                <label>Nom d'usage </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificat.nom_complet_usage" id="nom_complet_usage" name="nom_complet_usage" placeholder="Nom après le mariage">
                                </div>
                            </div>
                        </div>
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
                        <div class="col-md-6"> <br/>
                            <h5 class="text-bold text-green">
                                <label>
                                    <input type="checkbox" id="etat_civil_naissance" name="etat_civil_naissance" ng-model="certificat.etat_civil_naissance" ng-checked="certificat.etat_civil_naissance">&nbsp; Cochez cette case si cette personne est dans son lieu de naissance
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
                                    <input type="text" id="numero_acte_naissance_demandeur" class="form-control" ng-model="certificat.numero_acte_naissance_demandeur" name="numero_acte_naissance_demandeur" placeholder="N° de l'acte de naissance"/>
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
                                        <option value="" ng-show="false">-- Selectionner le num&eacute;ro --</option>
                                        @foreach($naissances as $naissance)
                                        <option value="{{$naissance->id}}"> {{$naissance->numero_acte_naissance.' du '.$naissance->date_dressers}}</option>
                                        @endforeach 
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nom de naissance *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificat.nom_complet_naissance" id="nom_complet_naissance" name="nom_complet_naissance" placeholder="Nom et prénom(s) à la naissance" required>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom complet du p&egrave;re </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-male"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificat.nom_complet_pere" id="nom_complet_pere" name="nom_complet_pere" placeholder="Nom et prénom(s) du père">
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom complet de la m&egrave;re </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-female"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificat.nom_complet_mere" id="nom_complet_mere" name="nom_complet_mere" placeholder="Nom et prénom(s) de la mère">
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
                    <input type="text" class="hidden" id="idCertificatSupprimer"  ng-model="certificat.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer la demande de <br/><b>@{{certificat.nom_complet_naissance}}</b></div>
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
        $scope.populateForm = function (certificat) {
        $scope.certificat = certificat;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.certificat = {};
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
    
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        $("#searchByName").keyup(function (e) { 
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-vie'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-vie-by-name/' + name});
            }
        });
        $("#searchByNumeroPiece").keyup(function (e) { 
            var numero = $("#searchByNumeroPiece").val();
            if(numero == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-vie'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-vie-by-piece-identite/' + numero});
            }
        });
      
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ""){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-vie'])}}"});
            }else{
               $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-vie-by-date/' + date});
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
       
        $("#naissance_id,#fonction_id").select2({width: '100%', allowClear: true});
        $("#div_naissance").hide();
        
        $("#btnModalAjout").on("click", function () {
            $("#div_naissance").hide();
            $("#div_numero_acte_naissance").show();
         
            $("#naissance_id, #fonction_id").val('').trigger('change');
            $('input:checkbox[name=etat_civil_naissance]').attr('checked',false);

            $('#nom_complet_naissance').prop('readonly', false);
            $('#date_naissance').prop('readonly', false);
            $('#lieu_naissance').prop('readonly', false);
            $('#nom_complet_pere').prop('readonly', false);
            $('#nom_complet_mere').prop('readonly', false);
                
            $('#nom_complet_naissance').val("");
            $('#date_naissance').val("");
            $('#lieu_naissance').val("");
            $('#nom_complet_pere').val("");
            $('#nom_complet_mere').val("");
        });
        
        $('#etat_civil_naissance').click(function(){
            if(document.querySelector('#etat_civil_naissance:checked') !== null)
            {
                document.getElementById("etat_civil_naissance").checked = true;
                $("#div_naissance").show();
                $("#div_numero_acte_naissance").hide();
                $("#naissance_id").select2("val", "");
                $('#nom_complet_naissance').prop('readonly', true);
                $('#date_naissance').prop('readonly', true);
                $('#lieu_naissance').prop('readonly', true);
                $('#nom_complet_pere').prop('readonly', true);
                $('#nom_complet_mere').prop('readonly', true);
            }else{
                document.getElementById("etat_civil_naissance").checked = false;
                $("#div_naissance").hide();
                $("#div_numero_acte_naissance").show();
                $("#naissance_id").select2("val", "");
                
                $('#nom_complet_naissance').prop('readonly', false);
                $('#date_naissance').prop('readonly', false);
                $('#lieu_naissance').prop('readonly', false);
                $('#nom_complet_pere').prop('readonly', false);
                $('#nom_complet_mere').prop('readonly', false);
                
                $('#nom_complet_naissance').val("");
                $('#date_naissance').val("");
                $('#lieu_naissance').val("");
                $('#nom_complet_pere').val("");
                $('#nom_complet_mere').val("");
            }
        });
        
        $("#naissance_id").change(function (e) {
            var naissance_id = $("#naissance_id").val();
            $.getJSON("../e-civil/find-acte-naissance-by-id/" + naissance_id, function (reponse) {
                if(reponse.total>0){
                    $.each(reponse.rows, function (index, naissance) { 
                        $('#nom_complet_pere').val("");
                        $('#nom_complet_mere').val("");
                        var nom_complet_naissance = naissance.nom_enfant + ' ' + naissance.prenom_enfant;
                        $('#nom_complet_naissance').val(nom_complet_naissance);
                        $('#date_naissance').val(naissance.date_naissance);
                        $('#lieu_naissance').val(naissance.lieu_naissance_enfant);
                        $('#nom_complet_pere').val(naissance.nom_complet_pere);
                        $('#nom_complet_mere').val(naissance.nom_complet_mere);
                    });
                }else{
                        $('#nom_complet_naissance').val("");
                        $('#date_naissance').val("");
                        $('#lieu_naissance').val("");
                        $('#nom_complet_pere').val("");
                        $('#nom_complet_mere').val("");
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
                var url = "{{route('e-civil.certificat-vie.store')}}";
             }else{
                var id = $("#idCertificatModifier").val();
                var methode = 'PUT';
                var url = 'certificat-vie/' + id;
             }
            editerCertificatAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });
       
        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idCertificatSupprimer").val();
            var formData = $(this).serialize();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('certificat-vie/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
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
                $('#nom_complet_naissance').prop('readonly', true);
                $('#date_naissance').prop('readonly', true);
                $('#lieu_naissance').prop('readonly', true);
                $('#nom_complet_pere').prop('readonly', true);
                $('#nom_complet_mere').prop('readonly', true);
        }else{
                $("#div_naissance").hide();
                $("#div_numero_acte_naissance").show();
                $("#naissance_id").select2("val", "");
                
                $('#nom_complet_naissance').prop('readonly', false);
                $('#date_naissance').prop('readonly', false);
                $('#lieu_naissance').prop('readonly', false);
                $('#nom_complet_pere').prop('readonly', false);
                $('#nom_complet_mere').prop('readonly', false);
        }
        certificat.fonction_id != null ? $('#fonction_id').select2("val", certificat.fonction_id) : $('#fonction_id').select2("val", ""); 
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
    
    function extraiRow(idCertificat){
        window.open("../e-civil/fiche-certificat-vie/" + idCertificat ,'_blank')
    }
    
    function imageFormatter(id, row) { 
        return row.scanne_pv_ou_certificat_deces ? '<img width=50 height=50 style="cursor: pointer;" title="Voir le document" onClick="javascript:voirImg(' + row.id + ');" src="{{asset('')}}' + row.scanne_pv_ou_certificat_deces+'"/>' : "";
    }
    function montantFormatter(montant){
        return '<span class="text-bold">' + $.number(montant)+ '</span>';
    }
    
    function optionFormatter(id, row) { 
            return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                    <button class="btn btn-xs btn-default" data-placement="left" data-toggle="tooltip" title="Imprimer la fiche" onClick="javascript:extraiRow(' + id + ');"><i class="fa fa-print"></i></button>\n\
                    <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
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
                    $('#nom_complet_naissance').prop('readonly', false);
                    $('#date_naissance').prop('readonly', false);
                    $('#lieu_naissance').prop('readonly', false);
                    $('#nom_complet_pere').prop('readonly', false);
                    $('#nom_complet_mere').prop('readonly', false);
                    $('#nom_complet_naissance').val("");
                    $('#date_naissance').val("");
                    $('#lieu_naissance').val("");
                    $('#nom_complet_pere').val("");
                    $('#nom_complet_mere').val("");
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


