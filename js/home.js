document.addEventListener("DOMContentLoaded", function(event) {

    function createColumn(className, name) {
        var column = document.createElement('div');        
        column.innerHTML = '<span class="'+className+'">\
                                <p>'+name+'</p>\
                                <hr>\
                                <p></p>\
                                <p></p>\
                                <p></p>\
                                <p></p>\
                                <p></p>\
                            </span>';
        return column;
    }

    document.getElementById('search_user').addEventListener("keyup", function () {
        var columns = document.getElementById('live_search_columns')
        for (var i = 0; i < columns.childNodes.length; i++) {
            columns.childNodes[i].remove();
        }
        request  = new Request ({
            url        : "script/searchUser.php",
            method     : 'POST',
            handleAs   : 'json',
            parameters : { search : this.value },
            onSuccess  : function(res) {
                            if (res['valid']) {
                                var live_search = document.getElementById("live_search");
                                if (res['usersLogin'] !== false)
                                    var usersLoginColumn = createColumn("login", "Login");
                                if (res['usersFirstName'] !== false)
                                    var usersFirstNameColumn = createColumn("first_name", "First Name");
                                if (res['usersLogin'] !== false)
                                    var usersLastNameColumn = createColumn("last_name", "Last name");
                                columns.classList.remove("is-hidden");
                                columns.appendChild(usersLoginColumn);
                                var usersLoginColumnP = usersLoginColumn.firstChild.getElementsByTagName('p');
                                for (var i = 0; i < res['usersLogin'].length; i++) {
                                    //usersLoginColumn[i].setAttribute('class', 'suggestions');
                                    usersLoginColumnP[i + 2].innerHTML = "<a class='link' href='profile.php?user_id="+res['usersLogin'][i].userId+"'>" + res['usersLogin'][i].login + "</a>";
                                }
                            }
            },
            onError    : function(status, message) {
                            display_notification("notification", "red", status + ": " + message);
            }
        });
      })

});