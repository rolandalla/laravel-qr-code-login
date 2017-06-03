@extends('backLayout.app')
@section('title')
{{trans('role.role')}}
@stop

@section('content')
<div class="panel panel-default">
        <div class="panel-heading">{{trans('role.role')}} {{$role->name}}</div>

        <div class="panel-body">
   <ul>
    <div class="row" >
            {{ Form::label('slug', trans('role.slug'), ['class' => 'col-md-4 control-label']) }}
            <div class="col-md-6">
            {{ $role->slug}}
            </div>
        </div>
      <div class="row">
            {{ Form::label('name', trans('basic.name'), ['class' => 'col-md-4 control-label']) }}
            <div class="col-md-6">
            {{ $role->name}}
            </div>
        </div>
      

        <div class="row">
        <br>
        <div class="col-md-6 col-md-offset-4">
            <a href="{{route('role.index')}}" class="btn btn-default">{{trans('basic.back')}}</a>
            </div>
        </div>
    </ul>
    </div>
    </div>

@endsection