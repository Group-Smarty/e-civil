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
<div class="row">
    <div class="col-md-6">
        <!--<label>Voir la liste sur une p&eacute;riode</label>-->
    </div>
</div>
<div class="row">
    <div class="col-md-3">
       <div class="form-group">
       <!--<input type="text" class="form-control" id="dateDebut" placeholder="Date du debut">-->
    </div>
    </div>
    <div class="col-md-3">
       <div class="form-group">
       <!--<input type="text" class="form-control" id="dateFin" placeholder="Date de fin">-->
    </div>
    </div>
    <div class="col-md-6">
        <a class="btn btn-success pull-right" onclick="imprimePdf()">Imprimer</a><br/>
    </div>
</div><br/>
<table id="table" class="table table-warning table-striped box box-warning"
               data-pagination="true"
               data-search="false" 
               data-toggle="table"
               data-url="{{url('e-civil',['action'=>'liste-nouveaux-majeurs'])}}"
               data-unique-id="id"
               data-show-toggle="false"
               data-show-columns="false">
    <thead>
        <tr>
            <th data-field="prenom_enfant">Pr&eacute;nom(s) </th>
            <th data-field="nom_enfant">Nom</th>
            <th data-field="date_naissance">Date de naissance</th>
            <th data-field="lieu_naissance_enfant">Lieu de naissance</th>
        </tr>
    </thead>
</table>


<script type="text/javascript">
    var $table = jQuery("#table"), rows = [];
    $(function () {
        $table.on('load-success.bs.table', function (e, data) {
            rows = data.rows; 
        });
        
        $('#dateDebut, #dateFin').datetimepicker({
            timepicker: false,
            formatDate: 'd-m-Y',
            format: 'd-m-Y',
            local : 'fr'
        }); 
        
        $("#dateDebut, #dateFin").change(function (e) {
            var dateDebut = $("#dateDebut").val();
            var dateFin = $("#dateFin").val();
            if(dateDebut!='' && dateFin!=''){
                $table.bootstrapTable('refreshOptions', {url: '../e-civil/liste-nouveaux-majeurs-periode/' + dateDebut + '/' + dateFin});
            }else{
                $table.bootstrapTable('refreshOptions', {url: "{{url('e-civil', ['action' => 'liste-nouveaux-majeurs'])}}"});
            }
        });
    });
    function imprimePdf(){
//        var dateDebut = $("#dateDebut").val();
//        var dateFin = $("#dateFin").val();
//        if(dateDebut!='' && dateFin!=''){
//            window.open("fiche-nouveaux-majeurs-periode/" + dateDebut + '/' + dateFin ,'_blank');  
//        }else{
            window.open("../e-civil/fiche-nouveaux-majeurs/" ,'_blank');
//        }
    }

</script>
@else
@include('layouts.partials.look_page')
@endif
@endsection


