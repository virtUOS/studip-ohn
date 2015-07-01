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
        
        // dashboard clean up
        if ($('.start-widgetcontainer').length > 0){
          $('.ui-widget_head span:contains("OHN-Kurse")').siblings().hide();
          $('.sidebar-widget-header:contains("Sprungmarken")').parent().hide();
          $('.sidebar-widget-header:contains("Aktionen")').parent().hide();
          var $editname = "https://ohn-kursportal.de/dispatch.php/settings/account";
          var $editpass = "https://ohn-kursportal.de/dispatch.php/settings/password";
          var $username = $('#username').val();
          var $userfullname = $('#userfullname').val();
          var $usermail = $('#usermail').val();

          $('.sidebar').append(
            '<div class="sidebar-widget">'+
              '<div class="sidebar-widget-header ohn-dashboard">'+
              $username+
              '</div>'+
              '<div class="sidebar-widget-content ohn-dashboard">'+
              '<ul class="widget-list">'+
            '<li><p>voller Name (<a href="'+$editname+'">bearbeiten</a>):</p><b>'+$userfullname+'</b></li>'+
            '<li><p>E-Mail (<a href="'+$editname+'">bearbeiten</a>):</p><b>'+$usermail+'</b></li>'+
            '<li><a href="'+$editpass+'">Passwort ändern</a></li>'+
              '</ul>'+
              '</div>'+
            '</div>'  
          );
        }
    
    
    

}(jQuery));
