$(document).ready(function(){
    
    (function(){
        elements = $("input[type=hidden]");
        for(var i=0; i< elements.length; i++)
        {   
            count = 7200;
            id = $(elements[i]).val();
            launchedAt = $(elements[i]).data("launchedat");
            objChild = $("#groupe"+id);
            (function(count, id){
                count = count - ((Math.floor(Date.now() / 1000)) - launchedAt);
                counter = setInterval(function() {
                    count = count - 1;
                    if (count < 0) {
                        clearInterval(counter);
                        $("#timer"+id).html("0:0:0");
                        return;
                    }
                
                    var seconds = count % 60;
                    var minutes = Math.floor(count / 60);
                    var hours = Math.floor(minutes / 60);
                    minutes %= 60;
                    hours %= 60;
                    $("#timer"+id).html(hours+":"+minutes+":"+seconds);
                }, 1000)
                
            })(count, id)
            
            $("#groupe"+id).children(".action").children("button").addClass("disabled");
        
            
        }
        function timer(count) {
            count = count - 1;
            if (count == -1) {
                clearInterval(counter);
                return;
            }
        
            var seconds = count % 60;
            var minutes = Math.floor(count / 60);
            var hours = Math.floor(minutes / 60);
            minutes %= 60;
            hours %= 60;
            objChild.children(".timer").html(hours+":"+minutes+":"+seconds);
        }
    })()
    
    
    $(".launchButton").on("click", function(){
        var clickedElement = $(this);
        var groupe = $(this).closest("tr");
        var groupeId = groupe.data("id");
        var route =  Routing.generate("set_launched_at");
        var count = 7200;
        //Todo
        //then in front get lanchedAt date 
        // to set the timer = (2h): 7200 - (getCurrentDate - lanchedAt)
        //then logout user when another user connect


        launchedAt = Math.floor(Date.now() / 1000);
        var data = {"groupeId": groupeId, "launchedAt": launchedAt};
        $.ajax({
            type: "POST",
            url: route,
            data: JSON.stringify(data),
            success: function(data, dataType){
                clickedElement.addClass("disabled");
                clickedElement.closest("tr").children(".statut").html("Activé");
                count = count - ((Math.floor(Date.now() / 1000)) - launchedAt);
                counter = setInterval(timer, 1000, groupeId);
                
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                console.log("Error: "+errorThrown);
            }
        });

        
        
        function timer(id) {
            count = count - 1;
            if (count == -1) {
                clearInterval(counter);
                return;
            }
            
            var seconds = count % 60;
            var minutes = Math.floor(count / 60);
            var hours = Math.floor(minutes / 60);
            minutes %= 60;
            hours %= 60;
            $('#timer'+id).html(hours+":"+minutes+":"+seconds);
        }
    });


    
});