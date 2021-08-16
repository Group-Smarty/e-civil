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
       <input type="text" class="form-control" id="searchByName" placeholder="Rechercher par nom du concerné ou du conjoint(e)">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByNumeroActe" placeholder="Rechercher par N° de l'acte">
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByDate" placeholder="Rechercher par date de l'évènement ou demande">
    </div>
</div>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-soit-transmis'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="date_demandes">Date de la demande </th>
            <th data-field="concerne">Concern&eacute;(e) </th>
            <th data-field="conjoint">Conjoint(e) </th>
            <th data-formatter="numeroActeFormatter">N° Acte </th>
            <th data-formatter="dateEvenementFormatter">Date &eacute;v&egrave;nement</th>
            <th data-field="commune_destination">Commune de destination</th>
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
                        <i class="fa fa-reply fa-2x"></i>
                        Gestion des soits transmis
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idSoitTransmisModifier" ng-hide="true" ng-model="soitTransmis.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Concern&eacute;(e) *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="soitTransmis.concerne" id="concerne" name="concerne" placeholder="Nom et prénom(s) du concerné(e)" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>N° d'avis de mention *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-edit"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="soitTransmis.numero_acte" id="numero_acte" name="numero_acte" placeholder="Ex : 6555" required> 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date de dresser *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="soitTransmis.date_dressers" id="date_dresser" name="date_dresser" placeholder="01-01-1994" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Nombre *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-edit"></i>
                                    </div>
                                    <input type="number" min="1" class="form-control" ng-model="soitTransmis.nombre" id="nombre" name="nombre" value="1" required> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Marie de destination *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bank"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="soitTransmis.commune_destination" id="commune_destination" name="commune_destination" placeholder="Mairie de destination" required>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-2">
                            <div class="form-group">
                                <label>Date du mariage</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="soitTransmis.date_mariages" id="date_mariage" name="date_mariage" placeholder="01-01-1994">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Conjoint(e) </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="soitTransmis.conjoint" id="conjoint" name="conjoint" placeholder="Nom et prénom(s) du conjoint(e)">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date du d&eacute;c&egrave;s</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="soitTransmis.date_decess" id="date_deces" name="date_deces" placeholder="01-01-1994">
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><br/>
                                    <input type="radio" checked="checked" value="mention_marginale" ng-model="soitTransmis.mention" name="mention">&nbsp;Pour mention marginale
                                </label>
                            </div>
                        </div> 
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><br/>
                                    <input type="radio" value="retour_apres_objet_rempli" ng-model="soitTransmis.mention" name="mention">&nbsp;En retour apr&egrave;s objet rempli
                                </label>
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
                    <input type="text" class="hidden" id="idSoitTransmisSupprimer"  ng-model="soitTransmis.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer la demande de <br/><b>@{{soitTransmis.concerne}}</b></div>
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
        $scope.populateForm = function (soitTransmis) {
        $scope.soitTransmis = soitTransmis;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.soitTransmis = {};
        };
    }); 
    
    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (soitTransmis) {
        $scope.soitTransmis = soitTransmis;
        };
        $scope.initForm = function () {
        $scope.soitTransmis = {};
        };
    });
    
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        $("#searchByName").keyup(function (e) { 
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-soit-transmis'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-soit-transmis-by-nom/' + name});
            }
        });
        $("#searchByNumeroActe").keyup(function (e) { 
            var numero = $("#searchByNumeroActe").val();
            if(numero == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-soit-transmis'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-soit-transmis-by-numero-acte/' + numero});
            }
        });
      
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ""){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-soit-transmis'])}}"});
            }else{
               $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-soit-transmis-by-date/' + date});
            }
        });
       
        $('#date_mariage, #date_dresser, #searchByDate, #date_deces').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
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
                var url = "{{route('e-civil.soit-transmis.store')}}";
             }else{
                var id = $("#idSoitTransmisModifier").val();
                var methode = 'PUT';
                var url = 'soit-transmis/' + id;
             }
            editerAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });
       
        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idSoitTransmisSupprimer").val();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('soit-transmis/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });
    
    function updateRow(idSoitTransmis) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var soitTransmis =_.findWhere(rows, {id: idSoitTransmis});
         $scope.$apply(function () {
            $scope.populateForm(soitTransmis);
        });
   
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idSoitTransmis) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var soitTransmis =_.findWhere(rows, {id: idSoitTransmis});
           $scope.$apply(function () {
              $scope.populateForm(soitTransmis);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function extraiRow(idCertificat){
        window.open("../e-civil/fiche-soit-transmis-pdf/" + idCertificat ,'_blank')
    }
    
    function numeroActeFormatter(id, row) { 
        return row.numero_acte + ' DU ' + row.date_dressers;
    }
    
    function dateEvenementFormatter(id, row){
        return row.date_mariages!=null ? "Mariage le " + row.date_mariages : "Décès le " + row.date_decess;
    }

    function optionFormatter(id, row) { 
            return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                    <button class="btn btn-xs btn-default" data-placement="left" data-toggle="tooltip" title="Imprimer la fiche" onClick="javascript:extraiRow(' + id + ');"><i class="fa fa-print"></i></button>\n\
                    <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }

</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection


