@extends('layouts.beforeLogin')
@section('content')
<div class="container">                
        <div class="col-sm-6 col-sm-offset-3">
            <div class="login-form">
                    {!! Form::open(['url' => 'login', 'files' => true,'id' => 'login-frm','redirect-url'=>url('/')]) !!} 
                    <h2 class="text-center text-white login-title">Login</h2>
                    <div class="form-group mb-30">
                        <input type="email" class="form-control input-txt" placeholder="Enter Email" name="email" />
                    </div>
                    <div class="form-group mb-30">
                        <input type="password" class="form-control input-txt" placeholder="Enter Password" name="password" />
                    </div>
                    <button type="submit" class="btn btn-primary text-uppercase btn-block btn-login">Submit</button>
                    {!! Form::close() !!}
                    <div class="row" align="center"><br/>
                        <a href="{{ route('forgotPassword') }}" style="color: white;" align="center">Forgot Password?</a>
                    </div>
            </div>
        </div>
</div>

@stop