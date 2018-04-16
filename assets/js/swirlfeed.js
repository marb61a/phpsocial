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
        } else if (type = 'message'){
            // Page to load messages
            pageName = "ajax_load_messages.php";
            $("span").remove("#unread_message");
        } else if (type = 'friend_requests'){
            // Page to load friend requests
             pageName = "ajax_load_friend_requests.php";
        }
        
        var ajaxreq = $.ajax({
            url:"includes/handlers/"+pageName,
            type:"POST",
            data:"page=1&userLoggedIn=" + user,
            cache:false,
            success: function(response){
                // Append with new posts
                $('.dropdown_data_window').html(response);
                $('.dropdown_data_window').css({"padding": "0px", "height": "280px"});
                // Set hidden input field  to the type of data being loaded
                $('#dropdown_data_type').val(type);
            }
        });
    } else {
        $(".dropdown_data_window").css({"padding": "0px", "height": "0px"});
        $(".dropdown_data_window").html("");
    }
}

$(document).click(function(e){
    // Resize the post text box and submit button when user clicks on it
    if($(e.target).is('#swirl_text')){
        // Resize the textbox
        changeHeight("swirl_text", "60px");
        
        // Resize the submit button
        changeHeight("swirl_button", "60px");
    } else if($('#swirl_text').length > 0){
        // This resizes the post text box and submit button when user clicks off it
        // Resize the textbox
        changeHeight("swirl_text", "30px");
        
        // Resize the submit button
        changeHeight("swirl_button", "30px");
    }
    
    // If the user clicks away from search bar and search results, hide drop down results
    if(e.target.class != 'search_results' && e.target.id != 'search-text-input'){
        // Remove drop down results
        
        //Remove drop down results from under search bar
        $(".search_results").html("");
        
        //Remove drop down results footer 
        $(".search_results_footer").html(""); 
        $(".search_results_footer").toggleClass("search_results_footer_empty" );
        $(".search_results_footer").toggleClass("search_results_footer" )
    }
    
    // If the user clicks away from dropdown data window then hide drop down results
    if(e.target.className != 'dropdown_data_window'){
        // Remoove drop down window starting with padding and height
        $('dropdown_data_window').css({
            "padding": "0px",
            "height": "0px"
        });
        
        // Remove the content
        $(".dropdown_data_window").html("");
    }
});

$(document).ready(function(){
    // The user clicks on the search bar
    $('#search-text-input').focus(function(){
        // Make the width larger
        $(this).animate({width: '250px'}, 500);
    });   
    
    // Make the div on the search bar act like a submit button
    $('.button_holder').on('click', function(){
        document.search_form.submit();
    });
    
    // Button to submit profile post
    $("#submit_profile_post").click(function(){
        $.ajax({
            type: "POST",
            url: "includes/handlers/ajax_submit_profile_post.php", 
            data: $('form.profile_post').serialize(),     
            success: function(msg){
                $("#post_form").modal('hide');
                location.reload();
            },
            failure: function(){
                alert("failure");
            }
        });
    });
});
