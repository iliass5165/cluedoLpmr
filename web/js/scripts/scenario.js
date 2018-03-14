$(document).ready(function(){
    //self envoking function the get checked element of a scenario after loading the page.
    (function () {
        var route =  Routing.generate("scenario_get_selected_elements");
        var scenarioId = $("input[type=hidden]#selectedScenario").val();
        $.ajax({
            type: "POST",
            url: route,
            data: JSON.stringify({"scenario": scenarioId}),
            success: function(data, dataType){
                //unchecking all checkboxes
                $("input[type=checkbox]").prop("checked", false);
                if(data.elements){
                    var elements = JSON.parse(data.elements);
                    for(i=0; i < elements.length; i++){
                        $("#element_"+elements[i]).prop("checked", true);
                    }
                }
                
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                alert("Une erreur est survenue lors du chargement des element deja cocher !");
                console.log("Error: "+errorThrown);
            }

        });
    })();

    $("input[name=radio_scenarios]").change(function(){
        var route = Routing.generate("scenario_post_isselected");
        var selectedScenarioId = $(this).val();

         $.ajax({
            type: "POST",
            url: route,
            data: JSON.stringify({"selectedId": selectedScenarioId}),
            success: function(data, dataType){
                $("input[type=hidden]#selectedScenario").val(selectedScenarioId);
                //unchecking all checkboxes
                $("input[type=checkbox]").prop("checked", false);
                if(data.elements){
                    var elements = JSON.parse(data.elements);
                    for(i=0; i < elements.length; i++){
                        $("#element_"+elements[i]).prop("checked", true);
                    }
                }
                
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                alert("Une erreur est survenue lors de la selection d'un scenario !");
                console.log("Error: "+errorThrown);
            }
        });

    })
    
    $(".checkElement").change(function(){
        var route =  $(this).is(":checked") ? Routing.generate("scenario_check_element")  : Routing.generate("scenario_uncheck_element");
        
        var object = {};
        object["scenario"] =  $("input[type=hidden]#selectedScenario").val();
        object["element"] =  $(this).val();
        
        $.ajax({
            type: "POST",
            url: route,
            data: JSON.stringify(object),
            success: function(data, dataType){
                data.status == "added" ? Materialize.toast('Added!', 3000)  : Materialize.toast('Removed!', 3000) ;
                object = {};
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                alert("Une erreur est survenue lors de la selection d'un element !");
                console.log("Error: "+errorThrown);
            }
        });
    })
});