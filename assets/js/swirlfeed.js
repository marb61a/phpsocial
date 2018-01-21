function changeHeight(id, height){
    // Change height
    document.getElementById(id).style.height = height;
}

// This will get users as a user is typing into a search box
// It also presents the results in a drop down table
function getLiveSearchUsers(value, user){
    $.post("includes/handlers/ajax_search.php", {query: value, userLoggedIn: user}, function(data){
        if($(".search_results_footer_empty")[0]){
            $(".search_results_footer_empty").toggleClass("search_results_footer" );
            $(".search_results_footer_empty").toggleClass("search_results_footer_empty" );
        }
        
        $(".search_results").html(data);
        $(".search_results_footer").html("<a href='search.php?q=" + value + "'>See All Results</a>");
        
        if(data == ""){
            $(".search_results_footer").html("");
            $(".search_results_footer").toggleClass("search_results_footer_empty" );
            $(".search_results_footer").toggleClass("search_results_footer" );
        }        
    });
}

// This gets users as a user is typing into the search box and presents results in a drop down table
function getUsers(value, user){
    $.post("includes/handlers/ajax_friend_search.php", 
    {
        query: value,
        userLoggedIn: user
    }, function(data){
       $(".results").html(data); 
    });
}

// This gets notifications, messages or friend requests for loggedin userand presents results in a drop down table
function getDropdownData(user, type){
    if ($(".dropdown_data_window").css("height") == "0px"){
        // A variable to hold the name of the page to send an AJAX request to
        var pageName;
        
        if(type == 'notification'){
            // Page to load notifications
            pageName = "ajax_load_notifications.php";
            $("span").remove("#unread_notification");
        }
    }
}

