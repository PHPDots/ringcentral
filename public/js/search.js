
var map;
var markers = [];

function initialLoadChart() {
google.load("visualization", "1.1", {
                packages: ["bar","corechart","treemap"],
                callback: 'createChart'
            });
}

function createChart()
{
    fillGridData();
}

function initMap() {
    var elevator;
    var myOptions = 
    {
        zoom: 1,
        center: new google.maps.LatLng(0, 0),
        mapTypeId: 'terrain'
    };

    map = new google.maps.Map($('#map_div')[0], myOptions);
}

function getActiveCalls(){    
    $.ajax({
        type: "GET",
        url: '/api/get-active-calls',
        success: function (result){
            $("#active-calls-section").html(result);
        },
        error: function (error) {
        }
    });     
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {
    for (var i = 0; i < markers.length; i++) {
      markers[i].setMap(map);
    }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
    setMapOnAll(null);
}

// Adds a marker to the map and push to the array.
function addMarker(location) {
    var marker = new google.maps.Marker({
      position: location,
      map: map
    });
    markers.push(marker);
}

 // Deletes all markers in the array by removing references to them.
function deleteMarkers() {
    clearMarkers();
    markers = [];
}

function fillGridData()
{
	var page = 1;
    $('#AjaxLoaderDiv').fadeIn('slow');
    $.ajax({
        type: "POST",
        url: $("#main-search-frm").attr('action'),
        data: $("#main-search-frm").serialize(),
        success: function (result)
        {
            $('#AjaxLoaderDiv').fadeOut('slow');
            if(result.status == 1)
            {
            	$("#tbl-call-log tbody").html(result.data.gridHtml);
                $("#tbl-call-log .pagination-section").html(result.data.paginationHTML);

                if($("#pageNo").val() == 1)
                {
                    drawChart(result.data.graph_data);

                    for(var label in result.data.counter_data)
                    {
                        $("#"+label).html(result.data.counter_data[label]);
                    }

                    deleteMarkers();

                    for(var i in result.data.locationData){
                        var latlng = new google.maps.LatLng(result.data.locationData[i].lat, result.data.locationData[i].lng);
                        addMarker(latlng);
                    }
                }
            }
            else
            {
                $.bootstrapGrowl(result.msg, {type: 'danger', delay: 4000});
            }
        },
        error: function (error) {
            $('#AjaxLoaderDiv').fadeOut('slow');
            $.bootstrapGrowl("Internal server error !", {type: 'danger', delay: 4000});
        }
    });	
}

function drawChart(graphData)
{
    var formatedData = [];
    formatedData.push(['Date', 'Inbound', 'Outbound', 'Connected', 'Missed', 'Rejected', 'Hang Up', 'No Answer', 'Busy']);
    if(graphData.length > 0)
    {
        for(var i in graphData)
        {
           var d = new Date(graphData[i][0]);
           var $created = d;
           formatedData.push(
            [
                {f: graphData[i][0], v: $created},
                parseFloat(graphData[i][1]), parseFloat(graphData[i][2]), parseFloat(graphData[i][3]), 
                parseFloat(graphData[i][4]),parseFloat(graphData[i][5]), parseFloat(graphData[i][6]), 
                parseFloat(graphData[i][7]), parseFloat(graphData[i][8])                
            ]
           )
        }
    }
    else
    {
        formatedData.push([null, 0, 0, 0, 0, 0, 0, 0, 0]);
    }    

    var data = google.visualization.arrayToDataTable(formatedData);

    var options = {      
      curveType: 'function',
      legend: {position: 'none'},
      colors: ['#0070c0', '#7030a0', '#3c763d', '#ff0000', '#a94442', '#7f7f7f', '#ed7d31', '#000000'],
      pointSize: 10,
      chartArea:{width:"80%",height:"85%"}      
    };

    var chart = new google.visualization.LineChart(document.getElementById('chart'));
    chart.draw(data, options);    
}

$(document).ready(function(){

    initialLoadChart();	
    getActiveCalls();

    setInterval(function (){
        getActiveCalls();        
    },30000);

    $(document).on("click",".page-link",function(){

        if($(this).parent().hasClass("disabled"))
        {
            // do norhing
        }
        else
        {
            $url = $(this).attr("href");            
            $url = $url.split("=");
            $page = $url[1];
            $("#pageNo").val($page);
            fillGridData();                    
        }

        return false;
    });    

    $(document).on("change",".filter-chks",function(){
        $("#pageNo").val(1);
        fillGridData();
    });    

    $(document).on("click",".select-filter-type",function(){        
        if($(this).attr("data-value") == "Fax")
        {
            $(this).attr("data-value","Voice");
            $(this).text("Voice");

            $(".current-filter-type").text("Fax");
            $("#searchType").val("Fax");
        }        
        else if($(this).attr("data-value") == "Voice")
        {
            $(this).text("Fax");
            $(this).attr("data-value","Fax");
            $(".current-filter-type").text("Voice");
            $("#searchType").val("Voice");
        }        

        $("#pageNo").val(1);
        fillGridData();   
    });

    $(document).on("click",".select-filter-byusers",function(){

        $(".current-filter-byusers").text($(this).text());
        $("#searchUserType").val($(this).attr("data-value"));

        if($(this).attr("data-value") == "all")
        {                        
            $("#current-filter-byusers-img").attr("src","/images/ic-group.png");
        }
        else
        {
            $("#current-filter-byusers-img").attr("src","/images/user-pic-white.png");
        }

        $("#pageNo").val(1);
        fillGridData();   
    });

    $(document).on("change","#searchByPhone",function(){
        $("#filterByPhone").val($(this).val());
        $("#pageNo").val(1);
        fillGridData();
    });

    $(document).on("click",".select-filter-bygroups",function(){

        $(".current-filter-bygroups").text($(this).text());
        $("#filterGroupType").val($(this).attr("data-value"));

        if($(this).attr("data-value") == "all")
        {                        
            $("#current-filter-bygroups-img").attr("src","/images/ic-group.png");
        }
        else
        {
            $("#current-filter-bygroups-img").attr("src","/images/user-pic-white.png");
        }

        $("#pageNo").val(1);
        fillGridData();
    });
});