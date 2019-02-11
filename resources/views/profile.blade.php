@extends('layouts.master')

@section('styles')
<link href="{{ asset('dropify/dist/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<section>
    <div class="container-fluid">
        <div class="row">                    
            <div class="col-lg-offset-2 col-lg-8 col-md-offset-2 col-md-8 col-sm-9 bound-section">
                <div class="row">
                    <div class="container-fluid">
                        <div class="container">
                            <h2>My Profile</h2>
                            <div class="clearfix">&nbsp;</div>
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#profile"><i class="fa fa-user"></i> &nbsp; Profile</a></li>
                                <li><a data-toggle="tab" href="#changePass"><i class="fa fa-lock"></i> &nbsp;  Change Password</a></li>
                            </ul>
                            <div class="tab-content">
                                <!-- Profile From -->
                                <div id="profile" class="tab-pane fade in active">
                                {!! Form::model($authUser,['method' => 'POST','files' => true, 'route' => 'updateProfile','class' => 'form-horizontal', 'id' => 'submit-frm','redirect-url'=>route('profile')]) !!}
                                    <div class="clearfix">&nbsp;</div><br/><br/>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Name :</label>
                                        <div class="col-sm-10">
                                            {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Enter Name']) !!}
                                        </div>
                                    </div>
                                    <div class="clearfix">&nbsp;</div>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Email :</label>
                                        <div class="col-sm-10">
                                            {!! Form::email('email',null,['class'=>'form-control','placeholder'=>'Enter Email']) !!}
                                        </div>
                                    </div>
                                    <div class="clearfix">&nbsp;</div>
                                    <div class="form-group">
                                        @if(!empty($authUser->image))
                                            <?php $img = asset('uploads/users/'.$authUser->image); ?>
                                        @else    
                                            <?php $img = asset('images/default-medium.png'); ?>
                                        @endif
                                        <label for="name" class="col-sm-2 control-label">Image :</label>
                                        <div class="col-sm-3">
                                            <input type="file" class="dropify" data-default-file="{{ $img }}" name="image" accept="image/*" >
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row" align="center">
                                        <input type="submit" name="submit" value="Change" class="btn btn-primary">
                                        <a href="{{ route('profile') }}" class="btn btn-danger">Reset</a>
                                    </div>
                                    {!! Form::close() !!}
                                </div>

                                <!-- Change Password Form -->
                                <div id="changePass" class="tab-pane fade">
                                    {!! Form::open(['method' => 'POST','route' => 'changePassword','class' => 'form-horizontal', 'id' => 'changePassword-frm','redirect-url'=>route('profile')]) !!}
                                    <div class="clearfix">&nbsp;</div><br/><br/>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Current Password :</label>
                                        <div class="col-sm-10">
                                            {!! Form::password('password_current',['class'=>'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="clearfix">&nbsp;</div>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">New Password :</label>
                                        <div class="col-sm-10">
                                            {!! Form::password('password',['class'=>'form-control']) !!}
                                        </div>
                                    </div>
                                    <div class="clearfix">&nbsp;</div>
                                    <div class="form-group">
                                        <label for="name" class="col-sm-2 control-label">Confirm Password :</label>
                                        <div class="col-sm-10">
                                            {!! Form::password('password_confirmation',['class'=>'form-control']) !!}
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row" align="center">
                                        <input type="submit" name="submit" value="Change" class="btn btn-primary">
                                        <a href="{{ route('profile') }}" class="btn btn-danger">Reset</a>
                                    </div>
                                    {!! Form::close() !!}
                                </div>                                
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-12 pull-right">
                @include('includes.right_sidebar')
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script src="{{ asset('dropify/dist/js/dropify.min.js') }}"></script>
<script src="{{ asset('dropify/dropify.js') }}"></script>
@endsection