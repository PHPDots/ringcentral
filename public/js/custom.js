$(function () {
    $(".toggle-menu").click(function () {
        $(this).toggleClass("active");
        $('.menu-drawer').toggleClass("open");
    });

    var start = moment();
    var end = moment();
    var iCounter = 1;

    function cb(start, end) 
    {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + " - "+end.format('MMMM D, YYYY'));
        $("#searchDateRange").val(start.format('YYYY-MM-D')+"/"+end.format('YYYY-MM-D'));

        if(iCounter > 1)
        {
            $("#pageNo").val(1);
            fillGridData();
        }        

        iCounter++;
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    cb(start, end);    
});



/*
 * create map
 */
var map = new google.maps.Map(document.getElementById("map_div"), {
    center: new google.maps.LatLng(33.808678, -117.918921),
    zoom: 14,
    mapTypeId: google.maps.MapTypeId.ROADMAP
});

/*
 * use google maps api built-in mechanism to attach dom events
 */
google.maps.event.addDomListener(window, "load", function () {

    /*
     * create infowindow (which will be used by markers)
     */
    var infoWindow = new google.maps.InfoWindow();

    /*
     * marker creater function (acts as a closure for html parameter)
     */
    function createMarker(options, html) {
        var marker = new google.maps.Marker(options);
        if (html) {
            google.maps.event.addListener(marker, "click", function () {
                infoWindow.setContent(html);
                infoWindow.open(options.map, this);
            });
        }
        return marker;
    }

    /*
     * add markers to map
     */
    var marker0 = createMarker({
        position: new google.maps.LatLng(33.808678, -117.918921),
        map: map,
        icon: "images/ic-map-pin.png"
    }, "<h1>Marker 0</h1><p>This is the home marker.</p>");

    var marker1 = createMarker({
        position: new google.maps.LatLng(33.818038, -117.928492),
        map: map,
        icon: "images/ic-map-pin.png"
    }, "<h1>Marker 1</h1><p>This is marker 1</p>");

    var marker2 = createMarker({
        position: new google.maps.LatLng(33.803333, -117.915278),
        map: map,
        icon: "images/ic-map-pin.png"
    }, "<h1>Marker 2</h1><p>This is marker 2</p>");
});

// listen for the window resize event & trigger Google Maps to update too
window.onresize = function () {
    var currCenter = map.getCenter();
    google.maps.event.trigger(map, 'resize');
    map.setCenter(currCenter);
};




function createChart() {
    $("#chart").kendoChart({
//        title: {
//            text: "Call History"
//        },
        legend: {
            position: "fixed"
        },
        chartArea: {
            background: ""
        },
        seriesDefaults: {
            type: "line",
            style: "smooth"
        },
        series: [{
                name: "Inbound",
                data: [90.907, 85.943, 50.848, 60.284, 40.263, 9.801, 3.890, 78.238, 60.552, 6.855, 9.552, 50.552, 9.552, 9.552, 30.552, 9.552, 9.552, 84.552, 9.552, 9.552, 20.552, 90.552, 9.552, 85.652]
            }, {
                name: "Outbound",
                data: [72.107, 6.343, 1.148, 2.884, 4.963, 55.121, 0.790, 99.238, 5.592, 87.875, 2.102, 3.552, 6.782, 35.552, 87.552, 2.552, 4.552, 72.052, 46.052, 6.752, 56.452, 45.552, 8.582, 78.272]
            }, {
                name: "Connected",
                data: [78.907, 8.943, 4.848, 7.284, 78.263, 2.801, 4.890, 84.28, 7.552, 2.855, 79.552, 2.552, 7.552, 87.552, 3.552, 98.752, 2.552, 8.092, 86.552, 8.552, 94.552, 1.552, 10.552, 78.852]
            }, {
                name: "Missed",
                data: [18.907, 25.943, 35.848, 55.284, 70.263, 65.801, 90.890, 10.238, 12.552, 18.855, 55.542, 75.872, 89.552, 84.552, 96.552, 99.552, 2.552, 18.552, 21.552, 22.552, 36.552, 48.582, 54.552, 58.552]
            }, {
                name: "Rejected",
                data: [58.907, 50.943, 7.848, 70.284, 89.263, 40.801, 67.890, 8.238, 9.552, 12.855, 56.552, 9.552, 99.552, 56.552, 9.552, 9.552, 87.552, 93.552, 9.552, 88.552, 9.552, 74.552, 63.052, 13.552]
            }],
        valueAxis: {
            labels: {
                format: "{0}"
            },
            line: {
                visible: false
            },
            axisCrossingValue: -10
        },
        categoryAxis: {
            categories: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24],
            majorGridLines: {
                visible: false
            },
            labels: {
                rotation: "auto"
            }
        },
        tooltip: {
            visible: true,
            format: "{0}",
            template: "#= series.name #: #= value #"
        }
    });
}

$(document).ready(createChart);
$(document).bind("kendo:skinChange", createChart);

//SIDEBAR
$(".menu-drawer").click(function () {
    $(".menu-drawer").removeClass("open");
});
$(".menu-drawer").click(function () {
    $(".menu-drawer").removeClass("open");
    $(".sidebar .toggle-menu").removeClass("active");
});
//SEARCH
jQuery(".btn-search").click(function () {
    jQuery('#input_show').toggle();
});
