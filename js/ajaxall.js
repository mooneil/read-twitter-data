function show_user(hob) {
    //alert(hob);
    $.ajax({
        type: "GET",
        url: "server/functions.php",
        async: true,
        data: {action: "show_user", hob: hob}
    }).done(function (msg) {
        //alert(msg);
        $("#results").html(msg);
    });
}

function add_users(id,name) {
    $.ajax({
        type: "GET",
        url: "server/functions.php",
        async: true,
        data: {action: "add_users", id: id,name:name}
    }).done(function (msg) {
        //alert(msg);
        $("#message").html(msg);
    });
}

function add_hob() {
    var name = $('#tuser').val();
    var  location= $('#location').val();
    var  description= $('#description').val();
    $.ajax({
        type: "GET",
        url: "server/functions.php",
        async: true,
        data: {action: "add_hob", name:name,location:location,description:description}
    }).done(function (msg) {
        alert(msg);
        $("#message").html(msg);
    });
}

