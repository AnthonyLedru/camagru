document.addEventListener("DOMContentLoaded", function(event) {

   /* ------------ Gallery ----------- */

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

    function comment_event(send_message) {
        send_message.onsubmit = function(e) {
            var imageId = this.elements['imageId'].value;
            var comment = this.elements['comment'].value
            var comment_raw = this.elements['comment'];
            request  = new Request ({
                url        : "script/comment.php",
                method     : 'POST',
                handleAs   : 'json',
                parameters : { imageId : imageId, comment : comment },
                onSuccess  : function(res) {
                                if (res['valid']) {
                                    display_notification("notification", "green", res['message']);
                                }
                                else
                                    display_notification("notification", "red", res['message']);
                },
                onError    : function(status, message) {
                                display_notification("notification", "red", status + ": " + message);
                }
            });
            comment_raw.value = "";
            comment_raw.placeholder = "Comment..."
            return false;
        }
    }

    if (document.getElementsByClassName('like')) {
        var like_icons = document.getElementsByClassName('like');
        for (i = 0; i < like_icons.length; i++) {
            like_event(like_icons[i]);
        }
    }

    if (document.getElementsByClassName('send_message')) {
        var send_message = document.getElementsByClassName('send_message');
        for (i = 0; i < send_message.length; i++) {
            comment_event(send_message[i]);
        }
    }

    function createColumn(image) {
        var column = document.createElement('div');
        column.setAttribute('class', 'column is-one-third');
        column.innerHTML = '<div class="card">\
                            <div class="card-image">\
                                <figure class="image is-4by3">\
                                    <a href="photo.php?image_id={$image->getImageId()}">\
                                        <img src="' + image.path + '" alt="gallery image">\
                                    </a>\
                                </figure>\
                            </div>\
                            <div class="card-content">\
                                <div class="media">\
                                    <div class="media-left">\
                                        <figure class="image is-48x48">\
                                            <img src="https://bulma.io/images/placeholders/96x96.png"  alt="Placeholder image">\
                                        </figure>\
                                    </div>\
                                    <div class="media-content">\
                                        <p class="title is-4">'+ image.user.fullName +'</p>\
                                        <p class="subtitle is-6"><a class="link" href="profile.php?user_id='+ image.user.userId +'">@'+ image.user.login +'</a></p>\
                                    </div>\
                                </div>\
                                <div class="content">\
                                    <i class="italic_desc">'+ image.description +'</i>\
                                    <br><br>\
                                    <div class="columns">\
                                        <div class="column">\
                                            <time>'+ image.date +'</time>\
                                        </div>\
                                        <div class="column">\
                                            <input name="imageId" value="'+ image.imageId +'" type="hidden">\
                                            <span>'+ image.nbLikes +'</span>\
                                            <span class="like" title="Like it"> like(s) ❤️</span>\
                                        </div>\
                                    </div>\
                                    <form class="send_message">\
                                        <div class="columns is-desktop">\
                                            <div class="column is-four-fifths-desktop">\
                                                <input name="imageId" value="'+ image.imageId +'" type="hidden">\
                                                <input placeholder="Comment..." class="input" name="comment" type"text">\
                                            </div>\
                                            <div class="column">\
                                                <button class="button is-dark" type="submit">Send</button>\
                                            </div>\
                                        </div>\
                                    </form>\
                                    <a href="photo.php?image_id='+ image.imageId +'" class="link comment_link">See comments</a>\
                                </div>\
                            </div>\
                        </div>';
        if (image.hasLiked === true) {
            column.getElementsByClassName('like')[0].innerHTML = " like(s) 💔";
        }
        like_event(column.getElementsByClassName('like')[0]);
        comment_event(column.getElementsByClassName('send_message')[0]);
        
        return column;
    }
    var skip = 6;
    const limit = 6;

    window.onscroll = function() {
        if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight) {
            request  = new Request ({
                url        : "script/loadImages.php",
                method     : 'POST',
                handleAs   : 'json',
                parameters : { skip : skip, limit : limit },
                onSuccess  : function(res) {
                                if (res['message'] === "Images loaded") {
                                    var nb_cards = document.getElementsByClassName('card').length;
                                    skip += res['images'].length;
                                    for (var i = 0; i < res['images'].length; i++) {
                                        if (nb_cards % 3 === 0) {
                                            var columns = document.createElement('div');
                                            columns.setAttribute('class', 'columns is-vcentered card_container');
                                            var container = document.getElementsByClassName('container');
                                            container[0].appendChild(columns);
                                        }
                                        var column = createColumn(res['images'][i]);
                                        nb_cards++;
                                        var columns = document.getElementsByClassName('card_container');
                                        columns[columns.length - 1].appendChild(column);
                                    }
                                    if (document.getElementsByClassName('like')) {
                                        var like_icons = document.getElementsByClassName('like');
                                    }
                                }
                                else
                                    display_notification("notification", "red", res['message']);
                },
                onError    : function(status, res) {
                                display_notification("notification", "red", status + ": " + res['message']);
                }
            });
        }
    };
    });