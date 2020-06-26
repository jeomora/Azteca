$(document).ready(function() {
    $("li").removeClass('active');
    $("#progress1").addClass('active');
        $(document).delegate('.foool', 'click', function(event){

            $(".open").addClass('oppenned');
            $(this).addClass('cls');
            $(this).addClass('rotated'); 
            event.stopPropagation();
        })
        $(document).delegate('body', 'click', function(event) {
            $('.open').removeClass('oppenned');
        })
        $(document).delegate('.cls', 'click', function(event){
            $('.open').removeClass('oppenned');
            $(this).removeClass('cls');
            $(this).removeClass('rotated'); 
            event.stopPropagation();
        });
    });

//var intervalID = window.setInterval(myCallback, 500);

function myCallback() {
  //console.log("Dudes");
}