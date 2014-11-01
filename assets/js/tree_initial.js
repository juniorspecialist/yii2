/**
 * Created by alex on 31.10.14.
 */
//$(function() {
//    $("#tree").treeview({
//        collapsed: true,
//        animated: "medium",
//        control:"#sidetreecontrol",
//        persist: "location"
//    });
//})

function initTrees() {
    $("#black").treeview({
        url: "node",
        collapsed: true
    })

    $("#mixed").treeview({
        url: "node",
        collapsed: true,
        unique:false,
        persist: "location",
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
    initTrees();
    $("#refresh").click(function() {
        $("#black").empty();
        $("#mixed").empty();
        initTrees();
    });

//    jQuery(function($) {
//        jQuery("#menu-treeview").treeview({'url':'/manager/tree/fillTree','collapsed':true,'unique':false});
//    });
});