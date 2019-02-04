@extends('layouts.master')


@section('content')        
        {!! Form::open(['url' => 'api/data', 'files' => true,'id' => 'main-search-frm']) !!}
        <input type="hidden" name="page" id="pageNo" value="1" />
        <input type="hidden" name="filterDate" id="searchDateRange" value="1" />
        <input type="hidden" name="filterType" id="searchType" value="Voice" />
        <section>
            <div class="container-fluid">
                <div class="row">                    
                    <div class="col-lg-offset-2 col-lg-8 col-md-offset-2 col-md-8 col-sm-9 bound-section">
                        <div class="row">
                            <div class="container-fluid">
                                @include('includes.filtercheckboxes')
                                <div class="tab-content">
                                    <div id="menu1" class="tab-pane active">   
                                        @include('includes.charts')
                                    </div>
                                    <div id="menu2" class="tab-pane fade">
                                        @include('includes.usercallLogs')
                                    </div>
                                    <div id="menu3" class="tab-pane fade">
                                        <div class="map-section">
                                            <div id="map_div" style="height: 600px;"></div>                                            
                                            <script src='https://maps.googleapis.com/maps/api/js'></script>
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
        {!! Form::close() !!}
@endsection

@section('scripts')
<script src="{{ asset('js/search.js') }}" type="text/javascript"></script>
@endsection