(function ($) {

    $(document).ready(function() {
	    $(".user_dropdown").click(function() {
		    $(".user_menu ul").toggle();
	    });
	    
	    $(".start-widgetcontainer .portal-widget-list .ui-widget-content article img").wrap("<div class='widget_image_wrapper'></div>");
	    
	    $("#mooc-courses-show #layout_container .description img").each(function() {
		    if( $("#mooc-courses-show #layout_container .description img").css("float") == "left" ) {
			    $(this).css("padding-right", "15px");
			  }
	    });
		});
    

}(jQuery));