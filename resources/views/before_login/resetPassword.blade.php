@extends('layouts.beforeLogin')
@section('content')
<div class="container">                
    <div class="col-sm-6 col-sm-offset-3">
        <div class="login-form">
                {!! Form::open(['route' => 'resetPasswordData', 'files' => true,'id' => 'login-frm','redirect-url'=>url('/')]) !!} 
                <h2 class="text-center text-white login-title">Login</h2>
                <div class="form-group mb-30">
                    <input type="password" class="form-control input-txt" placeholder="Enter New Password" name="password" />
                </div>
                <div class="form-group mb-30">
                    <input type="password" class="form-control input-txt" placeholder="Enter Confirm Password" name="password_confirmation" />
                </div>
                <button type="submit" class="btn btn-primary text-uppercase btn-block btn-login">Submit</button>
                <input type="hidden" name="key" value="{{$key}}">
                {!! Form::close() !!}
                <div class="row" align="center"><br/>
                    <a href="{{ url('/') }}" style="color: white;" align="center">Back to login?</a>
                </div>
        </div>
    </div>
</div>

@stop