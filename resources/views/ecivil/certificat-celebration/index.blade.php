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
<div class="col-md-4">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByName" placeholder="Rechercher par nom de l'epoux ou de l'epouse">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByDate" placeholder="Rechercher par date de mariage ou demande">
    </div>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-certificat-celebrations'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="date_demandes">Date de la demande </th>
            <th data-field="nom_epoux">Epoux </th>
            <th data-field="fonction_epoux.libelle_fonction">Fonction Epoux </th>
            <th data-field="nom_epouse">Epouse</th>
            <th data-field="fonction_epouse.libelle_fonction">Fonction Epouse </th>
            <th data-formatter="numeroActeMariageFormatter">N° acte de mariage</th>
            <th data-field="date_mariages">Date du mariage</th>
            <th data-field="id" data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="fa fa-wrench"></i></th>
        </tr>
    </thead>
</table>

<!-- Modal ajout et modification -->
<div class="modal fade bs-modal-ajout" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 65%">
        <form id="formAjout" ng-controller="formAjoutCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-list fa-2x"></i>
                        Gestion des certificats de c&eacute;l&eacute;bration
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idCertificatCelebrationModifier" ng-hide="true" ng-model="certificatCelebration.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>N° acte de marige *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="certificatCelebration.numero_acte" id="numero_acte" name="numero_acte" placeholder="Ex: 6655" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date de dresser *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="certificatCelebration.date_dressers" id="date_dresser" name="date_dresser" placeholder="Ex : 01-01-1994" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date du mariage *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="certificatCelebration.date_mariages" id="date_mariage" name="date_mariage" placeholder="Ex : 01-01-1994 à 14h15" required>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom complet de l'epoux *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatCelebration.nom_epoux" id="nom_epoux" name="nom_epoux" placeholder="Nom et prénom(s) de l'epoux" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fonction de l'epoux </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-circle-o"></i>
                                    </div>
                                    <select name="fonction_epoux" id="fonction_epoux" class="form-control">
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
                                <label>Nom complet de l'epouse *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatCelebration.nom_epouse" id="nom_epouse" name="nom_epouse" placeholder="Nom et prénom(s) de l'nom_epouse" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fonction de l'epouse </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-circle-o"></i>
                                    </div>
                                    <select name="fonction_epouse" id="fonction_epouse" class="form-control">
                                        <option value="" >-- Selectionner la fonction --</option>
                                        @foreach($fonctions as $fonction)
                                        <option value="{{$fonction->id}}"> {{$fonction->libelle_fonction}}</option>
                                        @endforeach
                                    </select>
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
                    <input type="text" class="hidden" id="idCertificatCelebrationSupprimer"  ng-model="certificatCelebration.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer la demande de <br/><b>@{{certificatCelebration.numero_acte +' DU ' + certificatCelebration.date_dressers}}</b></div>
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
        $scope.populateForm = function (certificatCelebration) {
        $scope.certificatCelebration = certificatCelebration;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.certificatCelebration = {};
        };
    }); 
    
    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (certificatCelebration) {
        $scope.certificatCelebration = certificatCelebration;
        };
        $scope.initForm = function () {
        $scope.certificatCelebration = {};
        };
    });
    
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        
        $("#searchByName").keyup(function (e) { 
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-celebrations'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-celebrations-by-nom//' + name});
            }
        });
      
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ""){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-celebrations'])}}"});
            }else{
               $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-celebrations-by-date/' + date});
            }
        });
       
       $("#fonction_epouse, #fonction_epoux").select2({width: '100%', allowClear: true});
        
        $("#btnModalAjout").on("click", function () {
            $("#fonction_epouse, #fonction_epoux").val('').trigger('change');
        });
       
        $('#date_dresser,#searchByDate').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            maxDate: new Date()
        });
        
        $('#date_mariage').datetimepicker({
            timepicker: true,
            formatDate: 'd-m-Y',
            formatTime: 'H:i',
            format: 'd-m-Y H:i',
            local : 'fr'
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
                var url = "{{route('e-civil.certificat-celebrations.store')}}";
             }else{
                var id = $("#idCertificatCelebrationModifier").val();
                var methode = 'PUT';
                var url = 'certificat-celebrations/' + id;
             }
            editerCertificatAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });
       
        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idCertificatCelebrationSupprimer").val();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('certificat-celebrations/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });
    
    function updateRow(idCertificatCelebration) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var certificatCelebration =_.findWhere(rows, {id: idCertificatCelebration});
         $scope.$apply(function () {
            $scope.populateForm(certificatCelebration);
        });
        certificatCelebration.fonction_epouse!=null ? $("#fonction_epouse").select2("val", certificatCelebration.fonction_epouse.id):$("#fonction_epouse").select2("val","");
        certificatCelebration.fonction_epoux!=null ? $("#fonction_epoux").select2("val", certificatCelebration.fonction_epoux.id):$("#fonction_epoux").select2("val","");

        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idCertificatCelebration) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var certificatCelebration =_.findWhere(rows, {id: idCertificatCelebration});
           $scope.$apply(function () {
              $scope.populateForm(certificatCelebration);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function numeroActeMariageFormatter(id,row){
        return row.numero_acte + ' DU ' + row.date_dressers;
    }
    
    function extraiRow(idCertificatCelebration){
        window.open("../e-civil/fiche-certificat-celebration-pdf/" + idCertificatCelebration ,'_blank')
    }
    
    function concerneFormatter(id, row) { 
        return row.civilite + '. ' + row.concerne;
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
                } else { //Modification
                    $table.bootstrapTable('updateByUniqueId', {
                        id: reponse.data.id,
                        row: reponse.data
                    });
                    $table.bootstrapTable('refresh');
                    $(".bs-modal-ajout").modal("hide");
                }
                $("#fonction_epouse, #fonction_epoux").val('').trigger('change');
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


