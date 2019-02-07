@extends('layouts.beforeLogin')
@section('content')
<div class="container">                
        <div class="col-sm-6 col-sm-offset-3">
            <div class="login-form">
                    {!! Form::open(['route' => 'forgotPasswordData', 'files' => true,'id' => 'login-frm','redirect-url'=>url('/')]) !!} 
                    <h2 class="text-center text-white login-title">Forgot Password From</h2>
                    <div class="form-group mb-30">
                        <input type="email" class="form-control input-txt" placeholder="Enter Email" name="email" />
                    </div>
                    <button type="submit" class="btn btn-primary text-uppercase btn-block btn-login">Submit</button>
                    {!! Form::close() !!}
                    <div class="row" align="center"><br/>
                        <a href="{{ url('/login') }}" style="color: white;" align="center">Back to login?</a>
                    </div>
            </div>
        </div>
</div>
@stop