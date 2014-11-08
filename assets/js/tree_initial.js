/**
 * Created by alex on 31.10.14.
 */

function initTrees() {
    $("#mixed").treeview({
        url: "node",
        collapsed: true,
        unique:false,
        //persist: "location",
        // add some additional, dynamic data and request with POST
        ajax: {
            data: {
                "additional": function() {
                    return "yeah: " + new Date;
                }
            },
            type: "post"
        }
    });
}
$(document).ready(function(){

    //проверим наличие блока для отображения дерева
    if($("#mixed").length>0) {
        initTrees();
        $("#refresh").click(function() {
            $("#mixed").empty();
            initTrees();
        });
    }


});