@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur')
 <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
 <script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
 <script src="{{asset('assets/js/underscore-min.js')}}"></script>
 <script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
 <script src="{{asset('assets/js/fonction_crude.js')}}"></script>
 <script src="{{asset('assets/plugins/Bootstrap-form-helpers/js/bootstrap-formhelpers-phone.js')}}"></script>
 <script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
 <link href="{{asset('assets/plugins/iCheck/square/orange.css')}}" rel="stylesheet">
 <link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet">
 <table id="table" class="table table-warning table-striped box box-warning"
                           data-pagination="true"
                           data-search="true"
                           data-toggle="table"
                           data-show-columns="false"
                           data-url="{{url('auth', ['action'=>'liste-users'])}}"
                           data-unique-id="token"
                           data-toolbar="#toolbar"
                           data-show-toggle="false">
        <thead>
           <tr>
            <th data-width="50px" data-align="center" data-formatter="optionResetPasswordFormatter"><i class="fa fa-key"></i></th>
            <th data-field="full_name" data-sortable="true" data-searchable="true">Nom</th>
            <th data-searchable="true" data-formatter="mailFormatter">Login</th>
            <th data-field="contact" data-searchable="true">Contact</th>
            <th data-field="role" data-searchable="true">Role</th>
            <th data-field="service" data-searchable="true">Service</th>
            <th data-field="chef_service" data-formatter="chefFormatter" data-align="center">Chef de service</th>
            <th data-field="statut_compte" data-formatter="etatCompteFormatter">Etat du compte</th>
            <th data-field="last_login">Derni&egrave;re connexion</th>
            <th data-field="id" data-width="80px" data-align="center" data-formatter="optionFormatter"><i class="fa fa-wrench"></i></th>
        </tr>
        </thead>
    </table>
<!-- Modal ajout et modification -->
<div class="modal fade bs-modal-ajout" role="dialog" data-backdrop="static">
    <div class="modal-dialog" style="width: 60%">
        <form id="formAjout" ng-controller="formAjoutCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span style="font-size: 16px;">
                        <i class="fa fa-users fa-2x"></i>
                        Gestion des utilisateurs de la plateforme
                    </span>
                </div>
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idUserModifier" ng-hide="true" ng-model="user.id"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Agents *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="hidden" ng-model="user.full_name" id="full_name" name="full_name">
                                    <select name="employe_id" id="employe_id" class="form-control select2" required>
                                        <option value="" ng-show="false">-- Sectionner un agent --</option>
                                        @foreach($agents as $agent)
                                        <option value="{{$agent->id}}"> {{$agent->full_name_agent}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Role *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </div>
                                    <select name="role" id="role" ng-model="user.role" class="form-control" required>
                                        <option value="" ng-hide="true">-- Aucun --</option>
                                        @if(Auth::user()->role == 'Concepteur')
                                        <option value="Concepteur"> Concepteur</option>
                                        @endif
                                        <option value="Administrateur"> Administrateur</option>
                                        <option value="Taxe"> Gestionnaire des taxes</option>
                                        <option value="Caissier"> Caissier</option>
                                        <option value="Operatrice"> Op&eacute;ratrice de saisie</option>
                                        <option value="Courrier"> Gestionnaire des courriers</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>E-mail </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-at"></i>
                                    </div>
                                    <input type="email" class="form-control" ng-model="user.email" id="email" name="email" placeholder="Adresse mail de l'utilisateur" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Contact *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" data-format="(dd) dd-dd-dd-dd" pattern="[(0-9)]{4} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}"  name="contact" id="contact" ng-model="user.contact" required readonly>
                                </div>
                            </div>
                        </div>                      
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mot de passe </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-at"></i>
                                    </div>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Login </label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-lock"></i>
                                    </div>
                                    <input type="text" class="form-control"  name="login" id="login" ng-model="user.login" placeholder="Login">
                                </div>
                            </div>
                        </div>                      
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Service *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <input type="text" class="form-control" ng-model="user.service" id="service" name="service" placeholder="Service de l'utilisateur" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" id="chef_service_div">
                            <div class="form-group">
                                <div class="form-group"><br/>
                                    <label>
                                        <input type="checkbox" id="chef_service" name="chef_service" ng-model="user.chef_service" ng-checked="user.chef_service">&nbsp; Chef de service ?
                                    </label>
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
<!-- Modal fermeture de compte -->
<div class="modal fade bs-modal-lokked-acount" category="dialog" data-backdrop="static">
    <div class="modal-dialog ">
        <form id="formLokedAcount" ng-controller="formLokedAcountCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-red">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Confimation de l'op&eacute;ration
                </div>
                @csrf
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idUserLokedAcount"  ng-model="user.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir <b>@{{user.statut_compte==1?'désactiver' : 'activer'}}</b> le compte de l'utilisateur <br/><b>@{{user.full_name}}</b></div>
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

<!-- Modal reset password -->
<div class="modal fade bs-modal-reset-password" category="dialog" data-backdrop="static">
    <div class="modal-dialog ">
        <form id="formPasswordReset" ng-controller="formPasswordResetCtrl" action="#">
            <div class="modal-content">
                <div class="modal-header bg-yellow">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Confimation de l'op&eacute;ration
                </div>
                @csrf
                <div class="modal-body ">
                    <input type="text" class="hidden" id="idUserPasswordReset"  ng-model="user.id"/>
                    <div class="clearfix">
                        <div class="text-center question"><i class="fa fa-question-circle fa-2x"></i> Etes vous certains de vouloir r&eacute;initialiser le mot de passe de cet utilisateur <br/><b>@{{user.full_name}}</b></div>
                        <div class="text-center vertical processing">R&eacute;initialisation en cours</div>
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
        var ajout = true;
        var $table = jQuery("#table"), rows = [];
    appSmarty.controller('formAjoutCtrl', function ($scope) {
        $scope.populateForm = function (user) {
        $scope.user = user;
        };
        $scope.initForm = function () {
        ajout = true;
        $scope.user = {};
        };
    });
    appSmarty.controller('formLokedAcountCtrl', function ($scope) {
        $scope.populateForm = function (user) {
        $scope.user = user;
        };
        $scope.initForm = function () {
        $scope.user = {};
        };
    });
    appSmarty.controller('formPasswordResetCtrl', function ($scope) {
        $scope.populateForm = function (user) {
        $scope.user = user;
        };
        $scope.initForm = function () {
        $scope.user = {};
        };
    });
     $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows;
        });
        $("#chef_service_div").show();
        $("#employe_id").select2({width: '100%', allowClear: true});
        $("#btnModalAjout").on("click", function () {
           $("#employe_id").select2("val", "");
           $("#chef_service_div").show();
        });
        $("#role").change(function (e) {
            var role = $("#role").val();
            if(role=='Gestionnaire'){
               $("#chef_service_div").hide(); 
            }else{
                $("#chef_service_div").show();
            }
        });
        $("#employe_id").change(function (e) {
            var agent = $("#employe_id").val();
            if(agent>0){
               $.getJSON("../recrutement/find-agent-by-id/" + agent, function (reponse) {
                   $.each(reponse.rows, function (index, agent) { 
                        $('#contact').val(agent.phone1);
                        $('#email').val(agent.email);
                        $('#full_name').val(agent.full_name_agent);
                        $('#service').val(agent.libelle_service);
                    });
                
                })  
            }else{
                $('#contact').val("");
                $('#email').val("");
                $('#full_name').val("");
                $('#service').val("");
            }
        });

        $("#formAjout").submit(function (e) {
            e.preventDefault();
            var $ajaxLoader = $("#formAjout .loader-overlay");
            if (ajout === true) {
            var methode = 'POST';
            var url = "{{route('auth.users.store')}}";
            } else {
            var id = $("#idUserModifier").val();
            var methode = 'PUT';
            var url = 'users/' + id;
            }
            editerUserAction(methode, url, $(this), $(this).serialize(), $ajaxLoader, $table, ajout);
        });

        $("#formLokedAcount").submit(function (e) {
            e.preventDefault();
            var id = $("#idUserLokedAcount").val();
            var formData = $(this).serialize();
            var $question = $("#formLokedAcount .question");
            var $ajaxLoader = $("#formLokedAcount .processing");
            lokedAcountAction('users/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });

        $("#formPasswordReset").submit(function (e) {
            e.preventDefault();
            var id = $("#idUserPasswordReset").val();
            var formData = $(this).serialize();
            var $question = $("#formPasswordReset .question");
            var $ajaxLoader = $("#formPasswordReset .processing");
            resetPasswordAction('reset-password-manualy/' + id, $(this).serialize(), $question, $ajaxLoader, $table);
        });
     });
   
    function updateRow(idUser) {
        ajout = false;
        var $scope = angular.element($("#formAjout")).scope();
        var user =_.findWhere(rows, {id: idUser});
        $scope.$apply(function () {
        $scope.populateForm(user);
        });
        if(user.role=='Gestionnaire'){
            $("#chef_service_div").hide(); 
        }else{
            $("#chef_service_div").show(); 
        }
        $('#employe_id').select2("val", user.employe_id);
        $(".bs-modal-ajout").modal("show");
    }

    function lokedAcountRow(idUser) {
        var $scope = angular.element($("#formLokedAcount")).scope();
        var user =_.findWhere(rows, {id: idUser});
        $scope.$apply(function () {
        $scope.populateForm(user);
        });
        $(".bs-modal-lokked-acount").modal("show");
    }

    function updatePasswordRow(idUser) {
    
    var $scope = angular.element($("#formPasswordReset")).scope();
    var user =_.findWhere(rows, {id: idUser});
    $scope.$apply(function () {
    $scope.populateForm(user);
    });
    $(".bs-modal-reset-password").modal("show");
    }
    function optionFormatter(id, row) {
        if(row.statut_compte==0){
            return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-success" data-placement="left" data-toggle="tooltip" title="Activer" onClick="javascript:lokedAcountRow(' + id + ');"><i class="fa fa-check"></i></button>';
            }else{
                 return '<button class="btn btn-xs btn-primary" data-placement="left" data-toggle="tooltip" title="Modifier" onClick="javascript:updateRow(' + id + ');"><i class="fa fa-edit"></i></button>\n\
                <button class="btn btn-xs btn-danger" data-placement="left" data-toggle="tooltip" title="Désactiver" onClick="javascript:lokedAcountRow(' + id + ');"><i class="fa fa-remove"></i></button>';
            }
    
    }
    function chefFormatter(chef){
        return chef==1 ? "<span class='label label-success'>OUI</span>":"<span>---</span>"; 
    }
    function etatCompteFormatter(etat){
        return etat==1 ? "<span class='label label-success'>Active</span>":"<span class='label label-danger'>Fermé</span>";
    }
    function optionResetPasswordFormatter(id, row){
        if(row.email!=null){
            return '<button class="btn btn-xs btn-warning" data-placement="left" data-toggle="tooltip" title="Reset password" onClick="javascript:updatePasswordRow(' + row.id + ');"><i class="fa fa-refresh"></i></button>';
        }else{
            return '';
        }
    }
    function mailFormatter(id,row){
        return row.email ? row.email : row.login;
    }

//Enregistrement 
function editerUserAction(methode, url, $formObject, formData, $ajoutLoader, $table, ajout = true) {
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

//Réinitialiser un mot de passe
function resetPasswordAction(url, formData, $question, $ajaxLoader, $table) {
    jQuery.ajax({
        type: "DELETE",
        url: url,
        cache: false,
        data: formData,
        success: function (reponse) {
            if (reponse.code === 1) {
                $(".bs-modal-reset-password").modal("hide");
                $table.bootstrapTable('refresh');
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

//Fermer un compte
   //Réinitialiser un mot de passe
function lokedAcountAction(url, formData, $question, $ajaxLoader, $table) {
    jQuery.ajax({
        type: "DELETE",
        url: url,
        cache: false,
        data: formData,
        success: function (reponse) {
            if (reponse.code === 1) {
                $(".bs-modal-lokked-acount").modal("hide");
                $table.bootstrapTable('refresh');
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