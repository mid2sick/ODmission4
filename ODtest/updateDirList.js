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
            // console.log("updateDirList.js: original response: ");
            // console.log(response);
            $.each(response,  function (index, element) {       // get the only item (index 0 item)        
                // console.log("index " + index + ": ");
                // console.log(element);
                directoryNames = JSON.parse(element.directoryNames);
                directoryIDs = JSON.parse(element.directoryIDs);
                for(var id in directoryIDs) {
                    // console.log(directoryIDs[id]);
                    // console.log(directoryNames[id]);
                    $("#dirList").append(`<li ><a href="#">` + directoryNames[id] + '</a></li>');
                }
                
            }); 
        }
    });
}