document.addEventListener("DOMContentLoaded", function(event) {

   /* ------------- Photo ------------ */

    var send_message_photo = document.getElementById('send_message_photo');
    send_message_photo.onsubmit = function(e) {
        var imageId = this.elements['imageId'].value;
        var comment = this.elements['comment'].value;
        var comment_raw = this.elements['comment'];
        request  = new Request ({
            url        : "script/comment.php",
            method     : 'POST',
            handleAs   : 'json',
            parameters : { imageId : imageId, comment : comment },
            onSuccess  : function(res) {
                            if (res['valid']) {
                                var list_comment = document.getElementById('list_comment');
                                var first_child = document.getElementById('list_comment').childNodes[1];
                                var div = document.createElement("div");
                                if (first_child !== undefined)
                                    if (first_child.className !== "div_gray")
                                        div.classList.add('div_gray');
                                var p = document.createElement("p");
                                p.innerHTML = "@" + res['login'] + " (" + res['date'] + ") :<br> <i>" + res['comment'] + "</i>";
                                div.appendChild(p);
                                list_comment.insertBefore(div, first_child);
                                if (list_comment.children.length > 1) {
                                    for (var i = 0; i < list_comment.children.length; i++) {
                                        if (list_comment.children[i].className !== "div_gray")
                                            list_comment.children[i].classList.add('div_gray');
                                        else
                                            list_comment.children[i].classList.remove('div_gray');
                                    }
                                }
                                display_notification("notification", "green", res['message']);
                            }
                            else
                                display_notification("notification", "red", res['message']);
            },
            onError    : function(status, message) {
                            display_notification("notification", "red", status + ": " + message);
            }
        });
        document.getElementById('comment_card').scrollTop = 0;
        comment_raw.value = "";
        comment_raw.placeholder = "Comment..."
        return false;
    }

    function like_event(like_icon) {
        like_icon.addEventListener('click', function(e) {
            var imageId = this.parentNode.children[0].value;
            var nb_like = this.parentNode.children[1];
            var like_icon = this.parentNode.children[2];
            request  = new Request ({
                url        : "script/like.php",
                method     : 'POST',
                handleAs   : 'json',
                parameters : { imageId : imageId },
                onSuccess  : function(res) {
                                if (res['unlike']) {
                                    nb_like.innerHTML = res['nbLike'];
                                    like_icon.innerHTML = " like(s) ❤️";
                                    display_notification("notification", "green", res['message']);
                                } else if (res['like']) {
                                    nb_like.innerHTML = res['nbLike'];
                                    like_icon.innerHTML = " like(s) 💔";
                                    display_notification("notification", "green", res['message']);
                                } else {
                                    display_notification("notification", "red", res['message']);
                                }
                },
                onError    : function(status, message) {
                                display_notification("notification", "red", status + ": " + message);
                }
            });
        });
    }

    if (document.getElementsByClassName('like')) {
        var like_icons = document.getElementsByClassName('like');
        for (i = 0; i < like_icons.length; i++) {
            like_event(like_icons[i]);
        }
    }

    document.getElementById('delete_photo_link').addEventListener('click', function(event) {
        var modal = document.getElementById('delete_photo_modal');
        var html = document.querySelector('html');
        event.preventDefault();
        modal.classList.add('is-active');
        html.classList.add('is-clipped');
    
        modal.querySelector('.modal-background').addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.remove('is-active');
            html.classList.remove('is-clipped');
        });

        var delete_cancel = document.getElementsByClassName('delete_cancel');
        for (i = 0; i < delete_cancel.length; i++) {
            delete_cancel[i].addEventListener('click', function(e) {
                e.preventDefault();
                modal.classList.remove('is-active');
                html.classList.remove('is-clipped');
            });
        }
    });

    document.getElementById('delete_photo_button').addEventListener('click', function(event) {
        var imageId = document.getElementById('image_id').value;
        request  = new Request ({
            url        : "script/deleteImage.php",
            method     : 'POST',
            handleAs   : 'json',
            parameters : { imageId : imageId },
            onSuccess  : function(res) {
                            if (res['valid'])
                                window.location.href = 'profile.php?user_id=' + res['userId'];
                            else
                                display_notification("notification", "red", res['message']);
            },
            onError    : function(status, message) {
                            display_notification("notification", "red", status + ": " + message);
            }
        });
    });

});