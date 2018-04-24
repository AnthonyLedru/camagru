window.onload = function() {

    /* --------- Burger Menu --------- */

    document.getElementById('burger_menu').onclick = function () {
        document.querySelector('.navbar-menu').classList.toggle('is-active');
    };

    /* --------- Login modal --------- */

    if (document.getElementById('login_button')) {
        var modal = document.getElementById('login_modal');
        var html = document.querySelector('html');
        document.getElementById('login_button').addEventListener('click', function(event) {
            document.querySelector('.navbar-menu').classList.remove('is-active');
            event.preventDefault();
            modal.classList.add('is-active');
            html.classList.add('is-clipped');
        
            modal.querySelector('.modal-background').addEventListener('click', function(e) {
                e.preventDefault();
                modal.classList.remove('is-active');
                html.classList.remove('is-clipped');
            });

            var login_cancel = document.getElementsByClassName('login_cancel');
            for (i = 0; i < login_cancel.length; i++) {
                login_cancel[i].addEventListener('click', function(e) {
                    e.preventDefault();
                    modal.classList.remove('is-active');
                    html.classList.remove('is-clipped');
                });
            }
        });

        var login_form = document.getElementById('login_form');
        login_form.onsubmit = function(e) {
            var login = login_form.elements['login'].value;
            var password = login_form.elements['password'].value;
            request  = new Request ({
                url        : "script/login.php",
                method     : 'POST',
                handleAs   : 'text',
                parameters : { login : login, password : password, wait : true },
                onSuccess  : function(message) {
                                var color = "red";
                                if (message.indexOf("Welcome") !== -1)
                                    location.reload();
                                else {
                                    document.getElementById('login_modal').classList.remove('is-active');
                                    document.querySelector('html').classList.remove('is-clipped');
                                    document.getElementById("login_form").reset();
                                    display_notification("notification", color, message);
                                }
                },
                onError    : function(status, message) {
                                display_notification("notification", "red", "An error occured");
                }
            });

            return false;
        }
    }

    /* --------- Signup Modal --------- */

    if (document.getElementById('signup_button')) {
        document.getElementById('signup_button').addEventListener('click', function(event) {
            document.querySelector('.navbar-menu').classList.remove('is-active');
            event.preventDefault();
            var modal = document.getElementById('signup_modal');
            var html = document.querySelector('html');
            modal.classList.add('is-active');
            html.classList.add('is-clipped');
        
            modal.querySelector('.modal-background').addEventListener('click', function(e) {
                e.preventDefault();
                modal.classList.remove('is-active');
                html.classList.remove('is-clipped');
            });

            var signup_cancel = document.getElementsByClassName('signup_cancel');
            for (i = 0; i < signup_cancel.length; i++) {
                signup_cancel[i].addEventListener('click', function(e) {
                    e.preventDefault();
                    modal.classList.remove('is-active');
                    html.classList.remove('is-clipped');
                });
            }
        });
    
        if (document.getElementById('signup_form')) {
            var signup_form = document.getElementById('signup_form');
            signup_form.onsubmit = function(e) {
                var mail = signup_form.elements['mail'].value;
                var login = signup_form.elements['login'].value;
                var password = signup_form.elements['password'].value;
                var passwordConf = signup_form.elements['passwordConf'].value;
                var lastName = signup_form.elements['lastName'].value;
                var firstName = signup_form.elements['firstName'].value;
                var gender = signup_form.elements['gender'].value;

                request  = new Request ({
                    url        : "script/signup.php",
                    method     : 'POST',
                    handleAs   : 'text',
                    parameters : { mail : mail, login : login, password : password, passwordConf : passwordConf,
                                    lastName : lastName, firstName : firstName, gender: gender, wait : true },
                    onSuccess  : function(message) {
                                    var color = "red";
                                    if (message.indexOf("A confirmation mail to activate your account has been sent to ") !== -1)
                                        color = "green";
                                    display_notification("notification", color, message);
                    },
                    onError    : function(status, message) {
                                    display_notification("notification", "red", status + ": " + message);
                    }
                });
                document.getElementById('signup_modal').classList.remove('is-active');
                document.querySelector('html').classList.remove('is-clipped');
                document.getElementById("signup_form").reset();
                return false;
            }
        }
    }

    /* --------- Notification --------- */

    document.getElementById('notification').addEventListener('click', function(event) {
        hide_notification("notification");
    });

    /* ------------ Logout ------------ */

    if (document.getElementById('logout_button')) {
        document.getElementById('logout_button').addEventListener('click', function(event) {
            request  = new Request ({
                url        : "script/logout.php",
                method     : 'POST',
                handleAs   : 'text',
                parameters : { wait : true },
                onSuccess  : function(message) {
                                var color = "red";
                                if (message === "Good bye !") {
                                    location.reload();
                                }
                                else {
                                    document.getElementById('signup_modal').classList.remove('is-active');
                                    document.querySelector('html').classList.remove('is-clipped');
                                    document.getElementById("signup_form").reset();
                                    display_notification("notification", color, message);
                                }
                },
                onError    : function(status, message) {
                                display_notification("notification", "red", status + ": " + message);
                }
            });
        });
    }

    /* ------------ Profile ----------- */

    if (document.getElementById('second_tab_button')) {
        document.getElementById('second_tab_button').addEventListener('click', function(event) {
            var first_tab = document.getElementById('first_tab').classList;
            var second_tab = document.getElementById('second_tab').classList;
            first_tab.remove('is-visible');
            first_tab.add('is-hidden');
            second_tab.remove('is-hidden');
            second_tab.add('is-visible');
            document.getElementById('second_tab_button').classList.add('is-active')
            document.getElementById('first_tab_button').classList.remove('is-active')
        });
    }

    if (document.getElementById('first_tab_button')) {
        document.getElementById('first_tab_button').addEventListener('click', function(event) {
            var first_tab = document.getElementById('first_tab').classList;
            var second_tab = document.getElementById('second_tab').classList;
            second_tab.remove('is-visible');
            second_tab.add('is-hidden');
            first_tab.remove('is-hidden');
            first_tab.add('is-visible');
            document.getElementById('second_tab_button').classList.remove('is-active')
            document.getElementById('first_tab_button').classList.add('is-active')
        });
    }

    if (document.getElementById('informations_form')) {
        var informations_form = document.getElementById('informations_form');
        informations_form.onsubmit = function(e) {
            var login = informations_form.elements['login'].value;
            var mail = informations_form.elements['mail'].value;
            var password = informations_form.elements['password'].value;
            var newPass1 = informations_form.elements['newPass1'].value;
            var newPass2 = informations_form.elements['newPass2'].value;
            var lastName = informations_form.elements['lastName'].value;
            var firstName = informations_form.elements['firstName'].value;
            var gender = informations_form.elements['gender'].value;
            var bio = informations_form.elements['bio'].value;
            request  = new Request ({
                url        : "script/profileUpdate.php",
                method     : 'POST',
                handleAs   : 'json',
                parameters : { login: login, mail : mail, password : password, newPass1 : newPass1, newPass2 : newPass2,
                                lastName : lastName, firstName : firstName, gender: gender, bio : bio, wait : true },
                onSuccess  : function(res) {
                                if (res['message'] === "Profile updated !") {
                                    display_notification("notification", "green", res['message']);
                                    document.getElementById('profile_name').innerHTML = "Hello, " + res['login'] + " ! 😎";
                                }
                                else
                                    display_notification("notification", "red", res['message']);
                },
                onError    : function(status, message) {
                                display_notification("notification", "red", status + ": " + res['message']);
                }
            });
            informations_form.elements['password'].value = "";
            informations_form.elements['newPass1'].value = "";
            informations_form.elements['newPass2'].value = "";
            return false;
        }
    }

    if (document.getElementById('preferences_form')) {
        var preferences_form = document.getElementById('preferences_form');
        preferences_form.onsubmit = function(e) {
            var notification = preferences_form.elements['notification'].value;
            var theme = preferences_form.elements['theme'].value;
            request  = new Request ({
                url        : "script/userPreferenceUpdate.php",
                method     : 'POST',
                handleAs   : 'text',
                parameters : { notification : notification, theme: theme,  wait : true },
                onSuccess  : function(message) {
                                if (message === "Preferences updated !")
                                    display_notification("notification", "green", message);
                                else
                                    display_notification("notification", "red", message);
                },
                onError    : function(status, message) {
                                display_notification("notification", "red", status + ": " + message);
                }
            });
            return false;
        }
    }

    /* ------------ Galery ----------- */

    if (document.getElementsByClassName('like')) {
        var like_icons = document.getElementsByClassName('like');
        for (i = 0; i < like_icons.length; i++) {
            like_icons[i].addEventListener('click', function(e) {
                var imageId = this.parentNode.children[0].value;
                var nb_like = this.parentNode.children[1];
                var like_icon = this.parentNode.children[2];
                request  = new Request ({
                    url        : "script/like.php",
                    method     : 'POST',
                    handleAs   : 'text',
                    parameters : { imageId : imageId, wait : true },
                    onSuccess  : function(message) {
                                    if (message === "You unliked this image") {
                                        nb_like.innerHTML = parseInt(nb_like.innerHTML) - 1;
                                        like_icon.innerHTML = " like(s) ❤️";
                                    } else if (message === "You liked this image") {
                                        nb_like.innerHTML = parseInt(nb_like.innerHTML) + 1;
                                        like_icon.innerHTML = " like(s) 💔";
                                    } else {
                                        display_notification("notification", "red", message);
                                    }
                    },
                    onError    : function(status, message) {
                                    display_notification("notification", "red", status + ": " + message);
                    }
                });
            });
        }
    }

    if (document.getElementsByClassName('send_message')) {
        var send_message = document.getElementsByClassName('send_message');
        for (i = 0; i < send_message.length; i++) {
            send_message[i].onsubmit = function(e) {
                var imageId = this.elements['imageId'].value;
                var comment = this.elements['comment'].value
                var comment_raw = this.elements['comment'];
                request  = new Request ({
                    url        : "script/comment.php",
                    method     : 'POST',
                    handleAs   : 'json',
                    parameters : { imageId : imageId, comment : comment, wait : true },
                    onSuccess  : function(message) {
                                    if (message['message'] === "Comment sent") {
                                        display_notification("notification", "green", message['message']);
                                    }
                                    else
                                        display_notification("notification", "red", message['message']);
                    },
                    onError    : function(status, message) {
                                    display_notification("notification", "red", status + ": " + message['message']);
                    }
                });
                comment_raw.value = "";
                comment_raw.placeholder = "Comment..."
                return false;
            }
        }
    }

    if (document.getElementById('send_message_photo')) {
        var send_message_photo = document.getElementById('send_message_photo');
        send_message_photo.onsubmit = function(e) {
            var imageId = this.elements['imageId'].value;
            var comment = this.elements['comment'].value
            var comment_raw = this.elements['comment'];
            request  = new Request ({
                url        : "script/comment.php",
                method     : 'POST',
                handleAs   : 'json',
                parameters : { imageId : imageId, comment : comment, wait : true },
                onSuccess  : function(message) {
                                if (message['message'] === "Comment sent") {
                                    var list_comment = document.getElementById('list_comment');
                                    var first_child = document.getElementById('list_comment').childNodes[1];
                                    var div = document.createElement("div");
                                    if (first_child !== undefined)
                                        if (first_child.className !== "div_gray")
                                            div.classList.add('div_gray');
                                    var p = document.createElement("p");
                                    p.innerHTML = "@" + message['login'] + " (" + message['date'] + ") :<br> <i>" + message['comment'] + "</i>";
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
                                    display_notification("notification", "green", message['message']);
                                }
                                else
                                    display_notification("notification", "red", message['message']);
                },
                onError    : function(status, message) {
                                display_notification("notification", "red", status + ": " + message['message']);
                }
            });
            document.getElementById('comment_card').scrollTop = 0;
            comment_raw.value = "";
            comment_raw.placeholder = "Comment..."
            return false;
        }
    }

    /* ---------- Photo edit --------- */

    if (document.querySelector("#video")) {
        HTMLMediaElement.srcObject
        var ctx;
        var video = document.querySelector("#video");
        var photo_list = document.getElementById("photo_list");
        var constraints = { audio: false, video: true }; 

        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || 
                                 navigator.mozGetUserMedia || navigator.msGetUserMedia || 
                                 navigator.oGetUserMedia;
        
        if (navigator.getUserMedia)     
            navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
            video.srcObject = stream;
            document.getElementById("take_photos").addEventListener('click', function(e) {
                var applied_filters = document.getElementById('filters');
                var data = {};
                var filters = [];
                canvas = document.createElement("canvas");
                canvas.width = video.videoWidth / 1.7;
                canvas.height = video.videoHeight / 1.7;
                data.width = video.videoWidth / 1.7;
                data.height = video.videoHeight / 1.7;
                if (document.getElementById('uploaded_photo')) {
                    var uploaded_photo = document.getElementById('uploaded_photo');
                    canvas.getContext('2d').drawImage(uploaded_photo, 0, 0, canvas.width, canvas.height);
                    uploaded_photo.remove();
                }
                else
                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                data.img_data = canvas.toDataURL('image/png');
                for (var i = 0; i < applied_filters.options.length; i++)
                    if (applied_filters.options[i].selected)
                        filters.push(applied_filters.options[i].value); 
                data.filters = filters;
                data.description = document.getElementById('description').value;
                canvas.remove();
                request  = new Request ({
                    url        : "script/imageMerge.php",
                    method     : 'POST',
                    handleAs   : 'json',
                    parameters : { data : JSON.stringify(data) },
                    onSuccess  : function(res) {
                                    if (res['message'] === "Image added to the list") {
                                        display_notification("notification", "green", res['message']);
                                        var img = document.createElement("img");
                                        img.classList.add("cam_photo");
                                        img.setAttribute('src', res['photo']);
                                        photo_list.appendChild(img);
                                        var p = document.createElement("p");
                                        p.innerHTML = res['description'];
                                        photo_list.appendChild(p);
                                        var br = document.createElement("br");
                                        photo_list.appendChild(br);
                                        img.addEventListener('click', removeImage(br, p));
                                        
                                    }
                                    else
                                        display_notification("notification", "red", res['message']);
                    },
                    onError    : function(status, res) {
                                    display_notification("notification", "red", status + ": " + res['message']);
                    }
                });

            });
        }).catch(function (error) {
            document.getElementById('video').style.display = 'none';
            var p = document.createElement('p');
            p.classList.add("has-text-centered");
            p.innerHTML = "Your webcam is not active";
            var first = document.getElementsByClassName('cam_menu')[0].childNodes[2];
            document.getElementsByClassName('cam_menu')[0].insertBefore(p, first);
            document.getElementById('take_photos').disabled = true;
        });

        document.getElementById("cancel_photo").addEventListener('click', function(e) {
            if (photo_list.firstChild) {
                while (photo_list.firstChild) {
                    photo_list.removeChild(photo_list.firstChild);
                }
                display_notification("notification", "green", "Image(s) removed");
            } else
                display_notification("notification", "red", "There is no images to remove");
        });

        document.getElementById("save_photo").addEventListener('click', function(e) {
            var imagesTab = {};
            var images = photo_list.getElementsByTagName('img');
            var descriptions = photo_list.getElementsByTagName('p');
            var j = 0;
            for (var i = 0; i < images.length; i++) {
                var imageInfo = {};
                imageInfo.src = images[i].src;
                imagesTab[j] = imageInfo;
                j++;
            }
            j = 0;
            for (i = 0; i < descriptions.length; i++) {
                imagesTab[j].description = descriptions[i].innerHTML;
                j++;
            }
            while (photo_list.firstChild) {
                photo_list.removeChild(photo_list.firstChild);
            }
            request  = new Request ({
                url        : "script/saveImage.php",
                method     : 'POST',
                handleAs   : 'json',
                parameters : { imagesTab : JSON.stringify(imagesTab) },
                onSuccess  : function(res) {
                                if (res['message'] === "Image(s) saved") {
                                    display_notification("notification", "green", res['message']);
                                }
                                else
                                    display_notification("notification", "red", res['message']);
                },
                onError    : function(status, res) {
                                display_notification("notification", "red", status + ": " + res['message']);
                }
            });
        });

        function removeImage(br, p) {
            return function() {
                this.remove();
                br.remove();
                p.remove();
            }
        }

        document.getElementById('filters').addEventListener('change', function(e) {
            var applied_filters = document.getElementById('filters');
            var inner_container = document.getElementById('inner_container');
            var elements = inner_container.getElementsByTagName("img");
            var has_selected_filter = false;
            for (i = elements.length - 1; i >= 0; i--)
                if (elements[i].id != "uploaded_photo")
                    elements[i].remove();
            for (var i = 0; i < applied_filters.options.length; i++) {
                if (applied_filters[i].selected) {
                    has_selected_filter = true;
                    var img = document.createElement('img');
                    img.src = applied_filters.options[i].value;
                    img.classList.add("video_overlay");
                    inner_container.insertBefore(img, document.getElementById('video'));
                }
            }
            if (has_selected_filter)
                document.getElementById('take_photos').disabled = false;
            else
                document.getElementById('take_photos').disabled = true;
        });


        function createImage() {
            img = new Image();
            img.onload = function imageLoaded() {
                if (document.getElementById("uploaded_photo"))
                    document.getElementById("uploaded_photo").remove();
                var img = document.createElement('img');
                img.src = this.src;
                img.classList.add("video_overlay");
                img.id = "uploaded_photo";
                inner_container.insertBefore(img, inner_container.firstChild);
            };
            img.src = this.result;
        }

        file_input.onchange = function(event) {
            var img = document.getElementById('file_input').files[0];
            var reader = new FileReader();
            reader.onload = createImage;
            reader.readAsDataURL(img); 
        }
    }

    /* -------- Password reset ------- */

    if (document.getElementById('reset_password_form')) {
        document.getElementById('reset_password_form').onsubmit = function(e) {
            var mail = this.elements['mail'].value;
            request  = new Request ({
                url        : "script/resetPasswordMail.php",
                method     : 'POST',
                handleAs   : 'json',
                parameters : { mail : mail },
                onSuccess  : function(message) {
                                if (message['message'] === "A mail to reset your password has been sent")
                                    display_notification("notification", "green", message['message']);
                                else
                                    display_notification("notification", "red", message['message']);
                },
                onError    : function(status, message) {
                                display_notification("notification", "red", "An error occured");
                }
            });
            document.getElementById("reset_password_form").reset();
            return false;
        }
    }

    if (document.getElementById('change_password_form')) {
        document.getElementById('change_password_form').onsubmit = function(e) {
            var passwordToken = this.elements['passwordToken'].value;            
            var password = this.elements['password'].value;
            var passwordConf = this.elements['passwordConf'].value;
            request  = new Request ({
                url        : "script/changePassword.php",
                method     : 'POST',
                handleAs   : 'json',
                parameters : { passwordToken : passwordToken, password : password, passwordConf : passwordConf},
                onSuccess  : function(message) {
                                if (message['message'] === "Password changed") {
                                    location.reload();
                                } else {
                                    display_notification("notification", "red", message['message']);
                                    document.getElementById("change_password_form").reset();
                                }
                },
                onError    : function(status, message) {
                                display_notification("notification", "red", "An error occured");
                                document.getElementById("change_password_form").reset();
                }
            });
            return false;
        }
    }
};