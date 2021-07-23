@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Concepteur')
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
        <select id="searchByTypeContrat"  class="form-control">
            <option value="0">--- Tous les types de contrat ---</option>
            @foreach($typeContrats as $type)
            <option value="{{$type->id}}"> {{$type->libelle_type_contrat}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <select id="searchByModeTravail"  class="form-control">
            <option value="0">--- Toutes les modes de travail ---</option>
            @foreach($modeTravails as $mode)
            <option value="{{$mode->id}}"> {{$mode->libelle_mode_travail}}</option>
            @endforeach
        </select>
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
               data-url="{{url('recrutement',['action'=>'liste-contrats'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-formatter="nameFormatter" data-sortable="true">Nom complet</th>
            <th data-field="type_contrat.libelle_type_contrat">Type de contrat</th>
            <th data-field="mode_travail.libelle_mode_travail">Mode de travail</th>
            <th data-field="salaire" data-formatter="salaireFormatter">Salaire</th>
            <th data-field="date_debuts">Date d&eacute;but </th>
            <th data-formatter="imageFormatter">Contrat </th>
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
                        <i class="fa fa-users fa-2x"></i>
                        Gestion des contrats de travail
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" name="idContrat" ng-hide="true" ng-model="contrat.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Employ&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-users"></i>
                                    </div>
                                    <select name="employe_id" id="employe_id" class="form-control select2" required>
                                        <option value="" ng-show="false">-- Selectionner l'employ&eacute; --</option>
                                        @foreach($agents as $agent)
                                        <option value="{{$agent->id}}"> {{$agent->full_name_agent}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Contact *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control" id="contact" placeholder="Contact téléphonique" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>N° de la pi&egrave;ce d'identit&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" class="form-control" id="numero_piece_identite" placeholder="Numéro de pièce d'identité" readonly>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Service *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-server"></i>
                                    </div>
                                    <input type="text" class="form-control" id="service" placeholder="Service" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fonction *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-circle-o"></i>
                                    </div>
                                    <input type="text" class="form-control" id="fonction" placeholder="Fonction" readonly>
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Salaire *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-money"></i>
                                    </div>
                                    <input type="number" min="0" class="form-control" ng-model="contrat.salaire" id="salaire" name="salaire" placeholder="Salaire" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Mode de travail *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-crop"></i>
                                    </div>
                                    <select name="mode_travail_id" id="mode_travail_id" ng-model="contrat.mode_travail_id" class="form-control" required>
                                        <option value="" ng-show="false">-- Selectionner le mode de travail --</option>
                                        @foreach($modeTravails as $mode)
                                        <option value="{{$mode->id}}"> {{$mode->libelle_mode_travail}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type de contrat *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-houzz"></i>
                                    </div>
                                    <select name="type_contrat_id" id="type_contrat_id" ng-model="contrat.type_contrat_id" class="form-control" required>
                                        <option value="" ng-show="false">-- Selectionner le type de contrat --</option>
                                        @foreach($typeContrats as $type)
                                        <option value="{{$type->id}}"> {{$type->libelle_type_contrat}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date du d&eacute;but *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" id="date_debut" name="date_debut" ng-model="contrat.date_debuts" placeholder="Date du debut de contrat" required>
                                </div>
                            </div>
                        </div> 
                         <div class="col-md-6">
                            <div class="form-group">
                                <label>Le document de contrat *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-file-archive-o"></i>
                                    </div>
                                    <input type="file" class="form-control" id="scan_contrat" name="scan_contrat" ng-model="contrat.scan_contrat">
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
                    <input type="text" class="hidden" id="idContratSupprimer"  ng-model="contrat.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer le contrat de <br/><b>@{{contrat.agent.full_name_agent}}</b></div>
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

<!-- Modal document contrat -->
<div class="modal fade bs-modal-image" id="image" ng-controller="ImageCtrl" category="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 75%">
            <div class="modal-content">
                <div class="modal-header bg-green">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <span style="font-size: 16px;">
                        <i class="fa fa-file-archive-o fa-2x"></i>
                        Contrat de travail de <b>@{{contrat.agent.full_name_agent}}</b>
                    </span>
                </div>
                <div class="modal-body ">
                    <div class="box-body">
                        <img class="img-responsive pad" src="{{asset('')}}@{{contrat.scan_contrat}}" alt="Contrat">
                    </div>
                </div>
            </div>
    </div>
</div>

<script type="text/javascript">
    var ajout = false;
    var $table = jQuery("#table"), rows = [];

    appSmarty.controller('formAjoutCtrl', function ($scope) {
        $scope.populateForm = function (contrat) {
        $scope.contrat = contrat;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.contrat = {};
        };
    });

    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (contrat) {
        $scope.contrat = contrat;
        };
        $scope.initForm = function () {
        $scope.contrat = {};
        };
    });
    appSmarty.controller('ImageCtrl', function ($scope) {
        $scope.populateForm = function (contrat) {
            $scope.contrat = contrat;
        };
    });

    $(function () {
    	$table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });

       $('#date_debut').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            minDate : new Date()
        }); 
        $("#employe_id").select2({width: '100%', allowClear: true});
        $("#btnModalAjout").on("click", function () {
           $("#employe_id").select2("val", "");
        });
        
        $("#searchByName").keyup(function (e) {
            var name = $("#searchByName").val();
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('recrutement', ['action' => 'liste-contrats'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../recrutement/liste-contrats-by-name/' + name});
            }
        });

        $("#searchByTypeContrat").change(function (e) {
            var type = $("#searchByTypeContrat").val();
            if(type == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('recrutement', ['action' => 'liste-contrats'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../recrutement/liste-contrats-by-type/' + type});
            }
        });
        $("#searchByModeTravail").change(function (e) {
            var mode = $("#searchByModeTravail").val();
            if(mode == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('recrutement', ['action' => 'liste-contrats'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../recrutement/liste-contrats-by-mode/' + mode});
            }
        });
        $("#searchBySexe").change(function (e) {
            var sexe = $("#searchBySexe").val();
            if(sexe == 'tous'){
                $table.bootstrapTable('refreshOptions', {url: "{{url('recrutement', ['action' => 'liste-contrats'])}}"});
            }else{
                $table.bootstrapTable('refreshOptions', {url: '../recrutement/liste-contrats-by-sexe/' + sexe});
            }
        });
        
        $("#employe_id").change(function (e) {
            var agent = $("#employe_id").val();
            if(agent>0){
               $.getJSON("../recrutement/find-agent-by-id-for-contrat/" + agent, function (reponse) {
                   $.each(reponse.rows, function (index, agent) { 
                        $('#contact').val(agent.phone1);
                        $('#numero_piece_identite').val(agent.numero_piece_identite);
                        $('#service').val(agent.libelle_service);
                        $('#fonction').val(agent.libelle_fonction);
                    });
                
                })  
            }else{
                $('#contact').val("");
                $('#numero_piece_identite').val("");
                $('#service').val("");
                $('#fonction').val("");
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
                var url = "{{route('recrutement.contrats.store')}}";
             }else{
                var methode = 'POST';
                var url ="{{route('recrutement.update-contrat')}}";
             }
             var formData = new FormData($(this)[0]);
            editerContratAction(methode, url, $(this), formData, $ajaxLoader, $table, ajout);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idContratSupprimer").val();
            var formData = $(this).serialize();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('contrats/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });

    function updateRow(idContrat) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var contrat =_.findWhere(rows, {id: idContrat});
         $scope.$apply(function () {
            $scope.populateForm(contrat);
        });
        $('#employe_id').select2("val", contrat.employe_id);
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idContrat) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var contrat =_.findWhere(rows, {id: idContrat});
           $scope.$apply(function () {
              $scope.populateForm(contrat);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function voirImg(idImage) {
        var $scope = angular.element($("#image")).scope();
        var contrat =_.findWhere(rows, {id: idImage});
         $scope.$apply(function () {
            $scope.populateForm(contrat);
        });
        $(".bs-modal-image").modal("show");
    }
    
    function nameFormatter(id, row){
        var name = 'M. '+row.agent.full_name_agent;
        if(row.agent.sexe == 'Feminin' && row.agent.situation_matrimoniale == 'Marié(e)'){
            name = 'Mme. '+row.agent.full_name_agent;
        }
        if(row.agent.sexe == 'Feminin' && row.agent.situation_matrimoniale != 'Marié(e)'){
            name = 'Mlle. '+row.agent.full_name_agent;
        }
        return name;
    }
    
    function salaireFormatter(salaire){
        return '<span class="text-bold">' + $.number(salaire)+ '</span>';
    }
    
    function imageFormatter(id, row) { 
//        return row.scan_contrat ? '<img width=50 height=50 style="cursor: pointer;" title="Voir le document" onClick="javascript:voirImg(' + row.id + ');" src="{{asset('')}}' + row.scan_contrat+'"/>' : "";
    return "<a target='_blank' href='" + basePath + '/' + row.scan_contrat + "'>Voir le document</a>";
    }
    
    function optionFormatter(id, row) {
        return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    
    function editerContratAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
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
                    $("#employe_id").select2("val", "");
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