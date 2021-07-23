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
       <input type="text" class="form-control" id="searchByName" placeholder="Rechercher par nom du demandeur ou de l'enfant">
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
               data-url="{{url('e-civil',['action'=>'liste-certificat-non-naissances'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="date_demande_certificats">Date demande </th>
            <th data-field="nom_complet_enfant">Non de l'enfant </th>
            <th data-field="sexe">Sexe</th>
            <th data-field="nom_complet_pere">P&egrave;re </th>
            <th data-field="nom_complet_mere">M&egrave;re </th>
            <th data-field="nom_complet_demandeur">Demandeur</th>
            <th data-field="contact_demandeur">Contact</th>
            <th data-field="adresse_demandeur">Adresse</th>
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
                        <i class="fa fa-mars fa-2x"></i>
                        Gestion des demandes de certificat de non inscription sur les registres de naissance
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idCertificatNonNaissanceModifier" ng-hide="true" ng-model="certificatNonNaissance.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nom complet du demandeur</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);"  class="form-control" ng-model="certificatNonNaissance.nom_complet_demandeur" id="nom_complet_demandeur" name="nom_complet_demandeur" placeholder="Nom et prénom(s) demandeur" required>
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
                                    <input type="text" class="form-control bfh-phone" ng-model="certificatNonNaissance.contact_demandeur" id="contact_demandeur" name="contact_demandeur" data-format="(dd) dd-dd-dd-dd" placeholder="Contact du demandeur">
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
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatNonNaissance.adresse_demandeur" id="adresse_demandeur" name="adresse_demandeur" placeholder="Adresse du demandeur" required>
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
                                    <input type="number" min="0" class="form-control" ng-model="certificatNonNaissance.montant" id="montant" name="montant" placeholder="Montant de la demande" required>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nom de l'enfant *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatNonNaissance.nom_complet_enfant" id="nom_complet_enfant" name="nom_complet_enfant" placeholder="Nom et prénom(s) de l'enfant" required>
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
                                    <select id="sexe" name="sexe" ng-model="certificatNonNaissance.sexe" ng-init="certificatNonNaissance.sexe='Masculin'" class="form-control" required>
                                        <option value="Masculin">Masculin</option>
                                        <option value="Feminin">Feminin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nom complet du p&egrave;re</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatNonNaissance.nom_complet_pere" id="nom_complet_pere" name="nom_complet_pere" placeholder="Nom et prénom(s) du père">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nom complet de la m&egrave;re</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="certificatNonNaissance.nom_complet_mere" id="nom_complet_mere" name="nom_complet_mere" placeholder="Nom et prénom(s) de la mère">
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
                    <input type="text" class="hidden" id="idCertificatNonNaissanceSupprimer"  ng-model="certificatNonNaissance.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer la demande de <br/><b>@{{certificatNonNaissance.nom_complet_demandeur}}</b></div>
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
        $scope.populateForm = function (certificatNonNaissance) {
        $scope.certificatNonNaissance = certificatNonNaissance;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.certificatNonNaissance = {};
        };
    }); 
    
    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (certificatNonNaissance) {
        $scope.certificatNonNaissance = certificatNonNaissance;
        };
        $scope.initForm = function () {
        $scope.certificatNonNaissance = {};
        };
    });
    
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        $("#searchByName").keyup(function (e) { 
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-non-naissances'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-non-naissances-by-name/' + name});
            }
        });
      
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ""){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-certificat-non-naissances'])}}"});
            }else{
               $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-certificat-non-naissances-by-date/' + date});
            }
        });

        $('#searchByDate').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
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
                var url = "{{route('e-civil.certificat-non-naissances.store')}}";
             }else{
                var id = $("#idCertificatNonNaissanceModifier").val();
                var methode = 'PUT';
                var url = 'certificat-non-naissances/' + id;
             }
            editerAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });
       
        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idCertificatNonNaissanceSupprimer").val();
            var formData = $(this).serialize();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('certificat-non-naissances/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });
    
    function updateRow(idCertificatNonNaissance) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var certificatNonNaissance =_.findWhere(rows, {id: idCertificatNonNaissance});
         $scope.$apply(function () {
            $scope.populateForm(certificatNonNaissance);
        });
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idCertificatNonNaissance) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var certificatNonNaissance =_.findWhere(rows, {id: idCertificatNonNaissance});
           $scope.$apply(function () {
              $scope.populateForm(certificatNonNaissance);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function pdfRow(idCertificat){
        window.open("../e-civil/certificat-non-naissances-pdf/" + idCertificat ,'_blank')
    }

    function montantFormatter(montant){
        return '<span class="text-bold">' + $.number(montant)+ '</span>';
    }
    
    function optionFormatter(id, row) { 
            return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                    <button class="btn btn-xs btn-default" data-placement="left" data-toggle="tooltip" title="Imprimer la fiche" onClick="javascript:pdfRow(' + id + ');"><i class="fa fa-print"></i></button>\n\
                    <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
   
</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection


