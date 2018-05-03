document.addEventListener("DOMContentLoaded", function(event) {


    function createColumn(image) {
        var column = document.createElement('div');
        column.setAttribute('class', 'column is-one-third');
        column.innerHTML = '<figure class="image is-500x500">\
                                    <a href="photo.php?image_id=' + image.imageId + '">\
                                        <img src="' + image.path + '" alt="profile image">\
                                    </a>\
                            </figure>';
        return column;
    }

    var skip = 6;
    const limit = 6;

    window.onscroll = function() {
        var second_tab = document.getElementById('second_tab');
        if (second_tab.classList.contains('is-visible')) {
            if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight) {
                request  = new Request ({
                    url        : "script/loadImages.php",
                    method     : 'POST',
                    handleAs   : 'json',
                    parameters : { skip : skip, limit : limit },
                    onSuccess  : function(res) {
                                    if (res['message'] === "Images loaded") {
                                        var nb_images = document.getElementById('image_container').getElementsByClassName('column').length;
                                        skip += res['images'].length;
                                        for (var i = 0; i < res['images'].length; i++) {
                                            if (nb_images % 3 === 0) {
                                                var columns = document.createElement('div');
                                                columns.setAttribute('class', 'columns is-vcentered image_columns is-centered');
                                                var container = document.getElementById('image_container');
                                                container.appendChild(columns);
                                            }
                                            var column = createColumn(res['images'][i]);
                                            nb_images++;
                                            var columns = document.getElementsByClassName('image_columns');
                                            columns[columns.length - 1].appendChild(column);
                                        }
                                        if (document.getElementsByClassName('like'))
                                            var like_icons = document.getElementsByClassName('like');
                                    }
                                    else
                                        display_notification("notification", "red", res['message']);
                    },
                    onError    : function(status, res) {
                                    display_notification("notification", "red", status + ": " + message);
                    }
                });
            }
        }
    };

    var tags = document.getElementsByClassName('tag');
    for (var i = 0; i < tags.length; i++) {
        tags[i].addEventListener('click', function(event) {
            var url = new URL(window.location.href);
            var userId = url.searchParams.get("user_id");
            request  = new Request ({
                url        : "script/follow.php",
                method     : 'POST',
                handleAs   : 'json',
                parameters : { userId : userId },
                onSuccess  : function(res) {
                    followerModalBody = document.getElementById('follower_modal_body')
                    if (res['unfollow']) {
                        tags[1].classList.remove('is-success');
                        tags[1].classList.add('is-danger');
                        tags[1].innerHTML = "No";
                        document.getElementById('follower').innerHTML = res['nbFollower'];
                        var userNodeFromModal = getNodeFromModal(res['FollowerLogin'])
                        if (userNodeFromModal != false)
                            userNodeFromModal.remove();
                        if (res['nbFollower'] == 0) {
                            var emptyP = document.createElement('p');
                            emptyP.innerHTML = res['FollowedLogin'] + " has no followers";
                            followerModalBody.appendChild(emptyP);
                        }
                        display_notification("notification", "green", res['message']);
                    }
                    else if (res['follow']) {
                        tags[1].classList.add('is-success');
                        tags[1].classList.remove('is-danger');
                        tags[1].innerHTML = "Yes";
                        display_notification("notification", "green", res['message']);
                        document.getElementById('follower').innerHTML = res['nbFollower'];
                        if (res['nbFollower'] == 1) {
                            var emptyNodeFromModal = getNodeFromModal("has no followers");
                            if (emptyNodeFromModal != false)
                                emptyNodeFromModal.remove();
                        }
                        var newFollower = document.createElement('p');
                        newFollower.innerHTML = "<a href=profile.php?user_id="+res['FollowerId']+">"+res['FollowerLogin']+"</a>";
                        followerModalBody.appendChild(newFollower);
                    } else
                        display_notification("notification", "red", res['message']);
                },
                onError    : function(status, res) {
                                display_notification("notification", "red", status + ": " + message);
                }
            });
        });
    }

    /* ------------ Modal ------------- */

    var html = document.querySelector('html');

    document.getElementById('follower').addEventListener('click', function (event) {
        var modal = document.getElementById('follower_modal');
        event.preventDefault();
        modal.classList.add('is-active');
        html.classList.add('is-clipped');
    
        modal.querySelector('.modal-background').addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.remove('is-active');
            html.classList.remove('is-clipped');
        });

        var follower_cancel = document.getElementsByClassName('follower_cancel');
        for (i = 0; i < follower_cancel.length; i++) {
            follower_cancel[i].addEventListener('click', function(e) {
                e.preventDefault();
                modal.classList.remove('is-active');
                html.classList.remove('is-clipped');
            });
        }
    });

    function getNodeFromModal(name) {
        var node = document.getElementById('follower_modal_body').childNodes;
        for (var i = 0; i < node.length; i++) {
            if (node[i].textContent.indexOf(name) !== -1)
                return node[i];
        }
        return false;
    }
    

    document.getElementById('following').addEventListener('click', function (event) {
        var modal = document.getElementById('following_modal');
        event.preventDefault();
        modal.classList.add('is-active');
        html.classList.add('is-clipped');
    
        modal.querySelector('.modal-background').addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.remove('is-active');
            html.classList.remove('is-clipped');
        });

        var follower_cancel = document.getElementsByClassName('following_cancel');
        for (i = 0; i < follower_cancel.length; i++) {
            follower_cancel[i].addEventListener('click', function(e) {
                e.preventDefault();
                modal.classList.remove('is-active');
                html.classList.remove('is-clipped');
            });
        }
    });

    var first_tab = document.getElementById('first_tab').classList;
    var second_tab = document.getElementById('second_tab').classList;

    document.getElementById('photos').addEventListener('click', function (event) {
        first_tab.remove('is-visible');
        first_tab.add('is-hidden');
        second_tab.remove('is-hidden');
        second_tab.add('is-visible');
        document.getElementById('second_tab_button').classList.add('is-active')
        document.getElementById('first_tab_button').classList.remove('is-active')
    });

});