@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Concepteur')
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
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByName" placeholder="Rechercher par nom">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
       <input type="text" class="form-control" id="searchByNumero" placeholder="Rechercher par N° idententifiant">
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <select id="searchByNation"  class="form-control">
            <option value="0">--- Toutes les nationalit&eacute; ---</option>
            @foreach($nations as $nation)
            <option value="{{$nation->id}}"> {{$nation->libelle_nation}}</option>
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
               data-url="{{url('taxe',['action'=>'liste-contribuables'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="true">
    <thead>
        <tr>
            <th data-field="id" data-formatter="ficheFormatter" data-width="60px" data-align="center"><i class="fa fa-list"></i></th>
            <th data-field="numero_identifiant">Num&eacute;ro ident. </th>
            <th data-formatter="nameFormatter">Contribuable </th>
            <th data-field="contact">Contact</th>
            <th data-field="nation.libelle_nation">Nationalit&eacute;</th>
            <th data-field="email" data-formatter="mailFormatter">E-mail </th>
            <th data-field="commune.libelle_commune">Commune </th>
            <th data-field="adresse">Adresse</th>
            <th data-field="contact2" data-visible="false">Contact 2</th>
            <th data-field="date_naissances" data-visible="false">Date naissance</th>
            <th data-field="situation_matrimoniale" data-visible="false">Situation matrimoniale</th>
            <th data-field="fonction.libelle_fonction" data-visible="false">Fonction</th>
            <th data-field="numero_piece_identite" data-visible="false">N° Pi&egrave;ce </th>
            <th data-field="type_piece.libelle_type_piece" data-visible="false">Type pi&egrave;ce </th>
            <th data-field="id" data-formatter="optionFormatter" data-width="100px" data-align="center"><i class="fa fa-wrench"></i></th>
        </tr>
    </thead>
</table>

<!-- Modal ajout et modification -->
<div class="modal fade bs-modal-ajout" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 72%">
        <form id="formAjout" ng-controller="formAjoutCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-users fa-2x"></i>
                        Gestion des contribuables
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idContribuableModifier" ng-hide="true" ng-model="contribuable.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Nom complet *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-pencil"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="contribuable.nom_complet" id="nom_complet" name="nom_complet" placeholder="Nom et prénom(s)" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nationalit&eacute; </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-flag"></i>
                                    </div>
                                    <select name="nation_id" id="nation_id" class="form-control" required>
                                        <option value="">Selectionner la nationalit&eacute;</option>
                                        @foreach($nations as $nation)
                                        <option value="{{$nation->id}}"> {{$nation->libelle_nation}}</option>
                                        @endforeach
                                    </select>
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
                                    <select name="sexe" id="sexe" ng-model="contribuable.sexe" ng-init="contribuable.sexe='Masculin'" class="form-control" required>
                                        <option value="Masculin">Masculin</option>
                                        <option value="Feminin">Feminin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date de naissance *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="contribuable.date_naissances" id="date_naissance" name="date_naissance" placeholder="Ex: 01-01-1994" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Situation Matrimoniale *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-venus-double"></i>
                                    </div>
                                    <select name="situation_matrimoniale" id="situation_matrimoniale" ng-model="contribuable.situation_matrimoniale" ng-init="contribuable.situation_matrimoniale='Célibataire'" class="form-control" required>
                                        <option value="Célibataire">C&eacute;libataire</option>
                                        <option value="Marié(e)">Mari&eacute;(e)</option>
                                        <option value="Autres">Autres</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact 1 *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-mobile-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="contribuable.contact" id="contact" name="contact" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" placeholder="Contact 1" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact 2</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-mobile-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" ng-model="contribuable.contact2" id="contact2" name="contact2" data-format="(dd) dd-dd-dd-dd" placeholder="Contact 2">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>E-mail </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-at"></i>
                                    </div>
                                    <input type="email" class="form-control" ng-model="contribuable.email" id="email" name="email" placeholder="Adresse mail">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Commune de r&eacute;sidence *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map"></i>
                                    </div>
                                    <select name="commune_id" id="commune_id" class="form-control" required>
                                        <option value="">-- Selectionner la commune --</option>
                                        @foreach($communes as $commune)
                                        <option value="{{$commune->id}}"> {{$commune->libelle_commune}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Adresse du domicile / Quartier  </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" ng-model="contribuable.adresse" id="adresse" name="adresse" placeholder="Adresse du domicile">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Type de pi&egrave;ce d'identit&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bars"></i>
                                    </div>
                                    <select name="type_piece_id" id="type_piece_id" ng-model="contribuable.type_piece_id" class="form-control" required>
                                        <option value="">-- Selectionner le type de pi&egrave;ce --</option>
                                        @foreach($typePieces as $typePiece)
                                        <option value="{{$typePiece->id}}"> {{$typePiece->libelle_type_piece}}</option>
                                        @endforeach
                                    </select>
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
                                    <input type="text" class="form-control" ng-model="contribuable.numero_piece" id="numero_piece" name="numero_piece" placeholder="Numéro de la pièce d'identité" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fonction </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-circle-o"></i>
                                    </div>
                                    <select name="fonction_id" id="fonction_id" class="form-control">
                                        <option value="">-- Selectionner la fonction --</option>
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
                    <input type="text" class="hidden" id="idContribuableSupprimer"  ng-model="contribuable.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir supprimer le contribuable <br/><b>@{{contribuable.nom_complet}}</b></div>
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
        $scope.populateForm = function (contribuable) {
        $scope.contribuable = contribuable;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.contribuable = {};
        };
    });

    appSmarty.controller('formSupprimerCtrl', function ($scope) {
        $scope.populateForm = function (contribuable) {
        $scope.contribuable = contribuable;
        };
        $scope.initForm = function () {
        $scope.contribuable = {};
        };
    });

    $(function () {
    	$table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });

       $('#date_naissance').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr',
            maxDate : new Date(),
        }); 
        
        $("#commune_id, #nation_id, #fonction_id, #searchByNation").select2({width: '100%', allowClear: true});
        
        $("#btnModalAjout").on("click", function () {
            $("#commune_id, #nation_id, #fonction_id").val('').trigger('change');
        });
        
        $("#searchByName").keyup(function (e) {
            
            $("#searchByNumero").val("");
            $("#searchByNation").val(0);
            $("#searchBySexe").val("tous");
            
            var name = $("#searchByName").val();
            
            if(name == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('taxe', ['action' => 'liste-contribuables'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-contribuables-by-name/' + name});
            }
        });

        $("#searchByNumero").keyup(function (e) {
            
            $("#searchByName").val("");
            $("#searchByNation").val(0);
            $("#searchBySexe").val("tous");
            
            var numero = $("#searchByNumero").val();
            
            if(numero == ''){
                $table.bootstrapTable('refreshOptions', {url: "{{url('taxe', ['action' => 'liste-contribuables'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-contribuables-by-numero/' + numero});
            }
        });
        
        $("#searchByNation").change(function (e) {
            
            $("#searchByName").val("");
            $("#searchByNumero").val("");
            $("#searchBySexe").val("tous");
            
            var nation = $("#searchByNation").val();
            
            if(nation == 0){
                $table.bootstrapTable('refreshOptions', {url: "{{url('taxe', ['action' => 'liste-contribuables'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-contribuables-by-nation/' + nation});
            }
        });
        
        $("#searchBySexe").change(function (e) {
            
            var sexe = $("#searchBySexe").val();
            $("#searchByName").val("");
            $("#searchByNumero").val("");
            $("#searchByNation").val(0);
            
            if(sexe == "tous"){
                $table.bootstrapTable('refreshOptions', {url: "{{url('taxe', ['action' => 'liste-contribuables'])}}"});
            }
            else{
              $table.bootstrapTable('refreshOptions', {url: '../taxe/liste-contribuables-by-sexe/' + sexe});
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
                var url = "{{route('taxe.contribuables.store')}}";
             }else{
                var id = $("#idContribuableModifier").val();
                var methode = 'PUT';
                var url = 'contribuables/' + id;
             }
            editerContribuableAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });

        $("#formSupprimer").submit(function (e) {
            e.preventDefault();
            var id = $("#idContribuableSupprimer").val();
            var $question = $("#formSupprimer .question");
            var $ajaxLoader = $("#formSupprimer .processing");
            supprimerAction('contribuables/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
    });

    function updateRow(idContribuable) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var contribuable =_.findWhere(rows, {id: idContribuable});
         $scope.$apply(function () {
            $scope.populateForm(contribuable);
        });
        $('#commune_id').select2("val", contribuable.commune_id);
        $('#nation_id').select2("val", contribuable.nation_id);
        contribuable.fonction_id != null ?  $('#fonction_id').select2("val", contribuable.fonction_id) : $('#fonction_id').select2("val", ""); 
        
        $(".bs-modal-ajout").modal("show");
    }

    function deleteRow(idContribuable) {
          var $scope = angular.element($("#formSupprimer")).scope();
          var contribuable =_.findWhere(rows, {id: idContribuable});
           $scope.$apply(function () {
              $scope.populateForm(contribuable);
          });
       $(".bs-modal-suppression").modal("show");
    }
    
    function detailRow(idContribuable) {
        window.open("details-contribuables/" + idContribuable ,'_blank')
    }
    
    function nameFormatter(id, row){
        var name = 'M. '+row.nom_complet;
        if(row.sexe == 'Feminin' && row.situation_matrimoniale == 'Marié(e)'){
            name = 'Mme. '+row.nom_complet;
        }
        if(row.sexe == 'Feminin' && row.situation_matrimoniale != 'Marié(e)'){
            name = 'Mlle. '+row.nom_complet;
        }
        return name;
    }
    
    function ficheFormatter(id){
        return '<button class="btn btn-xs btn-warning" data-placement="left" data-toggle="tooltip" title="Voir fiche" onClick="javascript:detailRow(' + id + ');"><i class="fa fa-list"></i></button>';
    }
    
    function optionFormatter(id, row) {
        return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Supprimer" onClick="javascript:deleteRow(' + id + ');"><i class="fa fa-trash"></i></button>';
    }
    
    function mailFormatter(mail){
        return mail ? '<a href="mailto:' + mail + '">' + mail + '</a>' : "";
    }
    
    function editerContribuableAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
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
                    $("#commune_id, #nation_id, #fonction_id").val('').trigger('change');
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