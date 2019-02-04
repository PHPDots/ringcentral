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
    },60000);        

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
});