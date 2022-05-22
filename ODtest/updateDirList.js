function updateDirList(name) {
    console.log(name);
    $.ajax({
        type: "POST",
        url: "getDirList.php",
        data: {username: name},
        error: function (e) {
            console.log("Get directory list failed: ", e);
        },

        success: function (response) {
            console.log("updateDirList.js: original response: ");
            console.log(response);
            directoryIDs = JSON.parse(response.IDs);
            directoryNames = JSON.parse(response.names);
            for(var id in directoryIDs) {
                $("#dirList").append(`<li ><a href="javascript:void(0)" class="dir" id="${directoryIDs[id]}">` + `${directoryNames[id]}` + '</a></li>');
            }
                
        }
    });
}