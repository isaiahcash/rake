// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 350) {
        document.getElementById("top_button").style.display = "block";
    } else {
        document.getElementById("top_button").style.display = "none";
    }
}
// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}

function show_alert(flag, message)
{
    var rand = Math.floor(Math.random() * 100000) + 1;
    var class_flag = "";
    if(flag) class_flag = "success";
    else class_flag = "danger";
    $("#alert_div").append("<div class='hovering-alert alert alert-" + class_flag + " ' role='alert' id='alert-" + rand + "'><button type='button' class='close' data-dismiss='alert'><span><i class='fas fa-times ml-2'></i></span></button>" + message + "</div>");
    setTimeout(function(){
        $("#alert-" + rand).hide();
    }, 3000);
}