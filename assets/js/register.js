$(document).ready(function(){
    // On click signup It Hides the Login Form and Displays the Registration Form
    $("#signup").click(function(){
        $("#first").slideUp("slow", function(){
            $("#second").slideDown("slow"); 
        });	
    });
    
    // On click signin It Hides the Registration Form and Displays the Login Form
    $("#signin").click(function(){
        $("#second").slideUp("slow",function(){
            $("#first").slideDown("slow");
        });
    });
})