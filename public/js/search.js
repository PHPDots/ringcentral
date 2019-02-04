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
                // if(result.data.isNext == 1)
                // {
                //     $("#gridNextLink").removeClass("disabled");
                //     $("#gridNextLink a").attr("data-page",result.data.nextPage);
                // }
                // else
                // {
                //     $("#gridNextLink").addClass("disabled");
                // }

                // if(result.data.isPrev == 1)
                // {
                //     $("#gridPrevLink").removeClass("disabled");
                //     $("#gridPrevLink a").attr("data-page",result.data.prevPage);
                // }
                // else
                // {
                //     $("#gridPrevLink").addClass("disabled");
                // }

                // $("#currentActivePage").html("Page "+result.data.currentPage);
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

$(document).ready(function(){

	fillGridData();

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