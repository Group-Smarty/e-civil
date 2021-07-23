@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Concepteur')
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
@if($infoConfig==null)
<div class="row"> 
    <div class="col-md-12">
        <form id="formAjout" action="{{route('configuration.store')}}" enctype="multipart/form-data"  method="post">
            <div class="modal-content">
                <div class="modal-header bg-green-active">
                    <span style="font-size: 16px;">
                        <i class="fa fa-cog fa-2x"></i>
                        Configuration des paramètres
                    </span>
                </div>
                <div class="modal-body ">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom de commune *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-bank"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" id="commune" name="commune" placeholder="Nom de la commune" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Logo de la mairie *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-file"></i>
                                    </div>
                                    <input type="file" class="form-control" id="logo" name="logo" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Responsable du logiciel *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-user"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" id="nom_responsable" name="nom_responsable" placeholder="Nom et prénom(s) du reponsable du logiciel" required>
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
                                    <input type="text" class="form-control bfh-phone" data-format="dd dd-dd-dd-dd" pattern="[0-9]{2} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}"  name="contact_responsable" id="contact_responsable" placeholder="Contact du responsable du logiciel" required>
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
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" id="service_responsable" name="service_responsable" placeholder="Service du responsable de logiciel" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Poste occup&eacute; *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-circle-o"></i>
                                    </div>
                                    <input type="text" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" class="form-control" id="post_responsable" name="post_responsable" placeholder="Poste occupé par le responsable" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>T&eacute;l&eacute;phone fixe de la mairie *</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" data-format="dd dd-dd-dd-dd" pattern="[0-9]{2} [0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}" id="telephone_mairie" name="telephone_mairie" placeholder="Numéro de téléphone fixe" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>T&eacute;l&eacute;phone faxe de la mairie</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-fax"></i>
                                    </div>
                                    <input type="text" class="form-control bfh-phone" data-format="dd dd-dd-dd-dd" id="fax_mairie" name="fax_mairie" placeholder="Numéro du fax">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Site web de la mairie</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-link"></i>
                                    </div>
                                    <input type="text" class="form-control" id="site_web_mairie" name="site_web_mairie" placeholder="Site web de la mairie">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Adresse de la mairie</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <input type="text" class="form-control" onkeyup="this.value = this.value.charAt(0).toUpperCase() + this.value.substr(1);" id="adresse_marie" name="adresse_marie" placeholder="Adresse de la mairie">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success    "><span class="overlay loader-overlay"> <i class="fa fa-refresh fa-spin"></i> </span>Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>
@else
<div class="row">
    <div class="col-md-12">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes --> 
            <div class="widget-user-header bg-green-active">
                <h2 class="widget-user-username">Mairie de la commune <?php $n=substr($infoConfig->commune,0,1); if($n=='A' || $n=='E' || $n=='I' || $n=='O' || $n=='U' || $n=='Y'){ echo "d'";}else{echo "de ";}?><b>{{$infoConfig->commune}}</b></h2>
                                                       <a href="{{route('configuration.infos-update')}}" class="btn btn-default pull-right">Modifier les infos</a>
                </div>
            <div class="widget-user-image">
                <img class="img-circle" src="{{asset($infoConfig->logo)}}" alt="Logo">
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header">Responsable</h5>
                            <span class="description-text">{{$infoConfig->nom_responsable}}</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-sm-3 border-right">
                        <div class="description-block">
                            <h5 class="description-header">Contact</h5>
                            <span class="description-text">{{$infoConfig->contact_responsable}}</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-sm-3">
                        <div class="description-block">
                            <h5 class="description-header">Service</h5>
                            <span class="description-text">{{$infoConfig->service_responsable}}</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-sm-3">
                        <div class="description-block">
                            <h5 class="description-header">Post occup&eacute;</h5>
                            <span class="description-text">{{$infoConfig->post_responsable}}</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="description-block">
                            <h5 class="description-header">T&eacute;l&eacute;phone fixe</h5>
                            <span class="description-text">{{$infoConfig->telephone_mairie}}</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-sm-3">
                        <div class="description-block">
                            <h5 class="description-header">T&eacute;l&eacute;phone faxe</h5>
                            <span class="description-text">{{$infoConfig->fax_mairie}}</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-sm-3">
                        <div class="description-block">
                            <h5 class="description-header">Site web</h5>
                            <span class="description-text">{{$infoConfig->site_web_mairie}}</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                    <div class="col-sm-3">
                        <div class="description-block">
                            <h5 class="description-header">Adresse</h5>
                            <span class="description-text">{{$infoConfig->adresse_marie}}</span>
                        </div>
                        <!-- /.description-block -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.widget-user -->
    </div>
</div>
@endif
<script type="text/javascript">

</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection