// Wait for the page to load first
window.onload = function() {
    username = usr;
    console.log("hey, " + username);
    $("body").on("click", ".dir", function() {
        id = $(this).attr("id");
        console.log(`at dir: ` + id);
        openDir(id, username);
    });
}

function openDir(dirID, username){
    console.log("get the username: " + username);
    console.log(`${dirID}`);
    $.ajax({
        type: "POST",
        url: "seeMetadata.php",
        data: {username: username, id:dirID},
        error: function (e) {
            console.log("Get metadata failed: ", e);
        },

        success: function (response) {
            console.log("openDir.js: original response: ");
            console.log(response);
            if(response[0] === "correct user") {
                data = response[1];
                console.log(data);
                if(data["metadata"] == "[]") {
                    $("#metaList").children().remove();
                    $("#metaList").append('<div style="position:relative;top:20px">No data in this directory yet.</div>');
                } else {
                    dataArr = data["metadata"].split(/[{}]/);
                    $("#metaList").children().remove();
                    dataArr.forEach(element => {
                        if (element === '[' || element === ']' || element === ',') {
                            return; // return is actually a continue in foreach loop
                        }
                        console.log("element: " + element);
                        jsonForm = "{" + element + "}";
                        json = JSON.parse(jsonForm);
                        htmlBlock = metaFormatting(json);
                        $("#metaList").append(htmlBlock);
                    });
                }
                
            } else {
                alert("invalid user");
            }
        }
    });
}

function metaFormatting(json) {
    console.log(json.題名);
    return '<div class="metaStack">' + 
                '<div class="metaRow">' + 
                    '<div class="metaBlock defineBlock">題名</div>' + 
                    '<div class="metaBlock titleBlock">' + json.題名 + '</div>' + 
                '</div>' + 
                '<div class="metaRow">' + 
                    '<div class="metaBlock defineBlock">來源系統</div>' + 
                    '<div class="metaBlock">' + json.來源系統 + '</div>' + 
                    '<div class="metaBlock defineBlock followDefine">系統縮寫</div>' + 
                    '<div class="metaBlock followMeta">' + json.來源系統縮寫 + '</div>' + 
                    '<div class="metaBlock defineBlock followDefine">類目階層</div>' + 
                    '<div class="metaBlock followMeta">' + json.類目階層 + '</div>' + 
                '</div>' + 
                '<div class="metaRow">' + 
                    '<div class="metaBlock defineBlock">典藏號</div>' + 
                    '<div class="metaBlock">' + json.典藏號 + '</div>' + 
                    '<div class="metaBlock defineBlock followDefine">起始時間</div>' + 
                    '<div class="metaBlock followMeta">' + json.起始時間 + '</div>' + 
                    '<div class="metaBlock defineBlock followDefine">結束時間</div>' + 
                    '<div class="metaBlock followMeta">' + json.結束時間 + '</div>' +  
                '</div>' + 
                '<div class="metaRow">' + 
                    '<div class="metaBlock defineBlock">摘要</div>' + 
                    '<div class="metaBlock">' + json.摘要 + '</div>' +  
                '</div>' + 
                '<div class="metaRow">' + 
                    '<div class="metaBlock defineBlock">原系統資料網址</div>' + 
                    '<div class="metaBlock">' + json.文件原系統頁面URL + '</div>' +  
                '</div>' + 
            '</div>';
}
