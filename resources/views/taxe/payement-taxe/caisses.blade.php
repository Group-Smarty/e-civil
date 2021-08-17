@extends('layouts.app')
@section('content')
@if(Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Taxe')
<script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap-table.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap-table/locale/bootstrap-table-fr-FR.js')}}"></script>
<script src="{{asset('assets/js/fonction_crude.js')}}"></script>
<script src="{{asset('assets/js/underscore-min.js')}}"></script>
<script src="{{asset('assets/js/jquery.number.min.js')}}"></script>
<link href="{{asset('assets/css/bootstrap-table.min.css')}}" rel="stylesheet">
    <div class="row">
        @foreach($caisses as $caisse)
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <a style="text-decoration: none; color: #000000; cursor:pointer;" onclick="event.preventDefault(); document.getElementById('logout-form' + {{$caisse->id}}).submit();">
                        <span class="info-box-icon {{$caisse->ouvert ? 'bg-green' : 'bg-red'}}">
                            <i class="fa fa-fax"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text {{$caisse->ouvert ? 'text-green' : 'text-red'}}">{{$caisse->ouvert ? 'Ouverte' : 'Fermée'}}</span>
                            <span class="info-box-number">{{$caisse->libelle_caisse}}</span>
                        </div>
                    </a>
                    <form id="logout-form{{$caisse->id}}" action="{{ route('taxe.point-caisse') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                        <input type="hidden" name="caisse_id" value="{{$caisse->id}}">
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@else
@include('layouts.partials.look_page')
@endif
@endsection


