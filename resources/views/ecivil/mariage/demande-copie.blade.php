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
       <input type="text" class="form-control" id="searchByNumeroDemande" placeholder="Rechercher par N° de demande">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByActe" placeholder="Rechercher par N° de l'acte">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByName" placeholder="Rechercher par nom">
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
               data-url="{{url('e-civil',['action'=>'liste-demandes-acte-mariage'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="id" data-formatter="recuFormatter" data-width="50px" data-align="center">Re&ccedil;u</th>
            <th data-field="numero_demande">N° demande</th>
            <th data-field="date_demandes">Date demande</th>
            <th data-field="nom_demandeur">Demandeur</th>
            <th data-formatter="numeroActeMariageFormatter">N° acte</th>
            <th data-field="date_mariages">Date mariage</th>
            <th data-field="mariage.nom_complet_homme">Epoux </th>
            <th data-field="mariage.nom_complet_femme">Epouse</th>
            <th data-field="date_retrait_demandes">Date retrait</th>
            <th data-field="nombre_copie" data-align="center">Copie </th>
            <th data-field="copie_integrale" data-formatter="copieIntegraleFormatter" data-align="center">Copie int&eacute;grale</th>
            <th data-field="id" data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="fa fa-wrench"></i></th>
        </tr>
    </thead>
</table>
<!-- Modal ajout et modification -->
<div class="modal fade bs-modal-ajout" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 70%">
        <form id="formAjout" ng-controller="formAjoutCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-copy fa-2x"></i>
                        Gestion des demandes de copie d'extrait de mariage
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idDemandeModifier" ng-hide="true" ng-model="demande.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pr&eacute;nom(s) et nom du demandeur *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="demande.nom_demandeur" id="nom_demandeur" name="nom_demandeur" placeholder="Prénom(s) et Nom du demandeur" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Contact du demandeur </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-mobile-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="demande.contact_demandeur" id="contact_demandeur" name="contact_demandeur" data-format="(dd) dd-dd-dd-dd" placeholder="Numéro mobile du demandeur">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><br/>
                                <label>
                                    <input type="checkbox" id="copie_integrale" name="copie_integrale" ng-model="demande.copie_integrale" ng-checked="demande.copie_integrale">&nbsp; Cochez cette case si c'est une copie int&eacute;grale
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nombre de copies *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-copy"></i>
                                    </div>
                                    <input type="number" min="1" class="form-control" ng-model="demande.nombre_copie" id="nombre_copie" name="nombre_copie" placeholder="Nombre de copies" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date de retrait *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="demande.date_retrait_demandes" id="date_retrait_demande" name="date_retrait_demande" placeholder="Ex: 01-01-1994" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Montant de la demande </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <input type="number" min="0" class="form-control" ng-model="demande.montant" id="montant" name="montant" placeholder="Montant à payer">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>N° acte de mariage </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <select name="mariage_id" id="mariage_id" class="form-control" required>
                                        <option value="">-- Saisir le nume&eacute;ro de l'acte --</option>
                                        @foreach($mariages as $mariage)
                                        <option value="{{$mariage->id}}"> {{$mariage->numero_acte_mariage.' DU '.$mariage->date_dressers}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date de mariage</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-birthday-cake"></i>
                                    </div>
                                    <input type="text" class="form-control"  id="date_mariage" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Regime </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" class="form-control" id="regime"  readonly>
                                </div>
                            </div>
                        </div>
                        </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Epoux </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="nom_epoux" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Epouse </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control"  id="nom_epouse" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date naissance </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-birthday-cake"></i>
                                    </div>
                                    <input type="text" class="form-control" id="date_naissance_epoux" readonly>
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
                                    <input type="text" class="form-control" id="fonction_epoux" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date naissance </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-birthday-cake"></i>
                                    </div>
                                    <input type="text" class="form-control" id="date_naissance_epouse" readonly>
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
                                    <input type="text" class="form-control" id="fonction_epouse" readonly>
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
                    <input type="text" class="hidden" id="idDemandeSupprimer"  ng-model="demande.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer la demande N° <br/><b>@{{demande.numero_demande}}</b></div>
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
        $scope.populateForm = function (demande) {
        $scope.demande = demande;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.demande = {};
        };
    }); 
    
    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (demande) {
        $scope.demande = demande;
        };
        $scope.initForm = function () {
        $scope.demande = {};
        };
    });
    
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        $('#searchByDate').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            maxDate : new Date()
        });
        $('#date_retrait_demande').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr'
        }); 
        
        $("#mariage_id").select2({width: '100%', allowClear: true});
        $("#btnModalAjout").on("click", function () {
            $("#mariage_id").select2("val", "");
        });
        
        $("#searchByNumeroDemande").keyup(function (e) { 
            var numero_demande = $("#searchByNumeroDemande").val();
            if(numero_demande == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-demandes-acte-mariage'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-demandes-by-numero/' + numero_demande + '/mariage'});
            }
        });
        $("#searchByActe").keyup(function (e) {
            var numero_acte = $("#searchByActe").val();
            if(numero_acte == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-demandes-acte-mariage'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-demandes-by-numero-acte/' + numero_acte + '/mariage'});
            }
        });
        $("#searchByName").keyup(function (e) {
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-demandes-acte-mariage'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-demandes-by-name/' + name + '/mariage'});
            }
        });
        $("#searchByDate").change(function (e) {
            var date = $("#searchByDate").val();
            if(date == ""){
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-demandes-acte-mariage'])}}"});
            }else{
               $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-demandes-by-date/' + date + '/mariage'});
            }
        });
        
        $("#mariage_id").change(function (e) {
            var mariage_id = $("#mariage_id").val();
            
            $('#date_mariage').val("");
            $('#regime').val("");
            $('#fonction_epoux').val("");
            $('#fonction_epouse').val("");
            $('#nom_epoux').val("");
            $('#date_naissance_epoux').val("");
            $('#nom_epouse').val("");
            $('#date_naissance_epouse').val("");
            
            $.getJSON("../e-civil/find-acte-mariage-by-id/" + mariage_id, function (reponse) {
                if(reponse.total>0){
                    $.each(reponse.rows, function (index, mariage) { 
                        $('#date_mariage').val(mariage.date_mariages);
                        $('#regime').val(mariage.regime.libelle_regime);
                        $('#fonction_epoux').val(mariage.fonction_homme.libelle_fonction);
                        $('#fonction_epouse').val(mariage.fonction_femme.libelle_fonction);
                        $('#nom_epoux').val(mariage.nom_complet_homme);
                        $('#date_naissance_epoux').val(mariage.date_naissance_hommes);
                        $('#nom_epouse').val(mariage.nom_complet_femme);
                        $('#date_naissance_epouse').val(mariage.date_naissance_femmes);
                    });
                }else{
                   $('#date_mariage').val("");
                    $('#regime').val("");
                    $('#fonction_epoux').val("");
                    $('#fonction_epouse').val("");
                    $('#nom_epoux').val("");
                    $('#date_naissance_epoux').val("");
                    $('#nom_epouse').val("");
                    $('#date_naissance_epouse').val("");
                }
           })  
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
                var url = "{{route('e-civil.demandes.store')}}";
             }else{
                var id = $("#idDemandeModifier").val();
                var methode = 'PUT';
                var url = 'demandes/' + id;
             }
            editerDemandeAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idDemandeSupprimer").val();
            var formData = $(this).serialize();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('demandes/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });
    
    function updateRow(idDemande){
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var demande =_.findWhere(rows, {id: idDemande});
         $scope.$apply(function () {
            $scope.populateForm(demande);
        });
        $('#mariage_id').select2("val", demande.mariage_id); 
        $(".bs-modal-ajout").modal("show");
    }
    
    function deleteRow(idDemande) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var demande =_.findWhere(rows, {id: idDemande});
           $scope.$apply(function () {
              $scope.populateForm(demande);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
     function numeroActeMariageFormatter(id,row){
        return row.mariage.numero_acte_mariage + ' DU ' + row.date_dressers;
    }
    
    function recuRow(idDemande){
        window.open("recu-demande-copie-mariage/" + idDemande ,'_blank')
    }
    function extraiRow(idMariage){
        window.open("../e-civil/extrait-declaration-mariage/" + idMariage ,'_blank')
    }
    function extraitCopieIntegraleRow(idMariage){
        window.open("../e-civil/extrait-mariage-copie-integrale/" + idMariage ,'_blank')
    }
    function recuFormatter(id, row){
        return '<button class="btn btn-xs btn-info" data-placement="left" data-toggle="tooltip" title="Reçu" onClick="javascript:recuRow(' + id + ');"><i class="fa fa-file-text-o"></i></button>';
    }
    function optionFormatter(id, row) {
        if(row.copie_integrale==0){
            return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-default" data-placement="left" data-toggle="tooltip" title="Imprimer la fiche" onClick="javascript:extraiRow(' + row.mariage.id + ');"><i class="fa fa-print"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
        }else{
             return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-warning" data-placement="left" data-toggle="tooltip" title="Imprimer la fiche" onClick="javascript:extraitCopieIntegraleRow(' + row.mariage.id + ');"><i class="fa fa-print"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
        }
    }

    function montantFormatter(montant){
        return '<span class="text-bold">' + $.number(montant)+ '</span>';
    }
    function copieIntegraleFormatter(copie){
        return copie ? '<span class="text-bold">Oui</span>' : '<span class="text-bold">Non</span>';
    }
    function editerDemandeAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
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
                    $formObject.trigger('eventAjouter', [reponse.data]);
                        $("#mariage_id").select2("val", "");
                        $('#date_mariage').val("");
                        $('#regime').val("");
                        $('#fonction_epoux').val("");
                        $('#fonction_epouse').val("");
                        $('#nom_epoux').val("");
                        $('#date_naissance_epoux').val("");
                        $('#nom_epouse').val("");
                        $('#date_naissance_epouse').val("");
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

