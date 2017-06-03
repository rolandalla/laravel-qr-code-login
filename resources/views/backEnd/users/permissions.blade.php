@extends('backLayout.app')
@section('title')
User details
@stop

@section('content')
<div class="panel panel-default">
        <div class="panel-heading">User  {{$user->name}} Permissions</div>

        <div class="panel-body">

{{ Form::open(array('url' => route('user.save', $user->id), 'class' => 'form-horizontal')) }}
    <ul>
    <div class="row">
    @foreach($actions as $action)
        <div class="col-md-4">
          <?php $first= array_values($action)[0];
            $firstname =explode(".", $first)[0];
          ?> 

        {{Form::label($firstname, $firstname, ['class' => 'form col-md-2 capital_letter'])}}
        <select name="permissions[]" class="select" multiple="multiple">
            @foreach($action as $act)
            @if(explode(".", $act)[0]=="api")
            <option value="{{$act}}" {{array_key_exists($act, $user->permissions)?"selected":""}}>
            {{isset(explode(".", $act)[2])?explode(".", $act)[1].".".explode(".", $act)[2]:explode(".", $act)[1]}}</option>
            @else
             <option value="{{$act}}" {{array_key_exists($act, $user->permissions)?"selected":""}}>

              {{explode(".", $act)[1]}}
              
             </option>
            @endif
            @endforeach
        </select>        
        </div>
    @endforeach
    </div>
       <div class="form-group">
            <div class="col-sm-offset-3 col-sm-3">
                {!! Form::submit('Submit', ['class' => 'btn btn-success form-control']) !!}
            </div>
            <a href="{{in_array($user->roles()->first()->name, ['Client', 'Deliver'])?route('user.index', ['type='.$user->roles()->first()->name]):route('user.index')}}" class="btn btn-default">Back to list</a>
        </div>
    </ul> 
    </div>
    </div>
    {{ Form::close() }}               

@stop

@section('scripts')
<script src="{{ URL::asset('/backend/vendors/sumoselect/jquery.sumoselect.js') }}"></script>
<link href="{{ URL::asset('/backend/vendors/sumoselect/sumoselect.css') }}" rel="stylesheet" />

<script type="text/javascript">
    $('.select').SumoSelect({ selectAll: true, placeholder: 'Nothing selected' });
</script>
@endsection