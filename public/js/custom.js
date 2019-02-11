$(function () {
    $(".toggle-menu").click(function () {
        $(this).toggleClass("active");
        $('.menu-drawer').toggleClass("open");
    });

    var start = moment().subtract(6,'d');
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
