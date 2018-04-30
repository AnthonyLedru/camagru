document.addEventListener("DOMContentLoaded", function(event) {

    function createColumn(className, name) {
        var column = document.createElement('div');        
        column.innerHTML = '<span class="'+className+'">\
                                <p class="subtitle user_type">'+name+'</p>\
                            </span>';
        return column;
    }

    function addParagraph(column, is_result) {
        var p = document.createElement('p');
        column.getElementsByTagName('span')[0].appendChild(p);
        if (!is_result) {
            p.classList.add('no_results');
            p.innerHTML = "No results";
        }
    }

    var request = null;
    document.getElementById('search_user').addEventListener("keyup", function () {
        var columns = document.getElementById('live_search_columns')
        while (columns.firstChild) {
            columns.removeChild(columns.firstChild);
        }
        columns.classList.add('is-hidden');
        
        if (request != null)
            request.cancel();

        if (this.value.length == 0)
            return ;

        request  = new Request ({
            url        : "script/searchUser.php",
            method     : 'POST',
            handleAs   : 'json',
            parameters : { search : this.value, wait : true },
            onSuccess  : function(res) {
                            var usersLoginColumn = createColumn("login", "Login");
                            var usersNameColumn = createColumn("name", "Name");
                            columns.appendChild(usersLoginColumn);
                            columns.appendChild(usersNameColumn);
                            columns.classList.remove("is-hidden");
                            console.log(res);
                            if (res['valid']) {
                                if (res['usersLogin'].length !== 0) {
                                    for (var i = 0; i < res['usersLogin'].length; i++) {
                                        var p = document.createElement('p');
                                        usersLoginColumn.getElementsByTagName('span')[0].appendChild(p);
                                        p.innerHTML = "<a class='link' href='profile.php?user_id="+res['usersLogin'][i].userId+"'>" + res['usersLogin'][i].login + "</a>";
                                    }
                                } else
                                    addParagraph(usersLoginColumn, false);

                                if (res['usersName'].length !== 0) {
                                    for (var i = 0; i < res['usersName'].length; i++) {
                                        var p = document.createElement('p');
                                        usersNameColumn.getElementsByTagName('span')[0].appendChild(p);
                                        p.innerHTML = "<a class='link' href='profile.php?user_id="+res['usersName'][i].userId+"'>" + res['usersName'][i].fullName + "</a>";
                                    }
                                } else
                                    addParagraph(usersNameColumn, false);
                            } else {
                                addParagraph(usersLoginColumn, false);
                                addParagraph(usersNameColumn, false);
                            }
            },
            onError    : function(status, message) {
                            display_notification("notification", "red", status + ": " + message);
            }
        });
    });
});