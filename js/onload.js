window.onload = function() {

    /* --------- Burger Menu --------- */

    document.getElementById('burger_menu').onclick = function () {
        document.querySelector('.navbar-menu').classList.toggle('is-active');
    };

    /* --------- Login modal --------- */

    if (document.getElementById('login_button')) {
        document.getElementById('login_button').addEventListener('click', function(event) {
            document.querySelector('.navbar-menu').classList.remove('is-active');
            event.preventDefault();
            var modal = document.getElementById('login_modal');
            var html = document.querySelector('html');
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

    if (document.getElementById('preferences_button')) {
        document.getElementById('preferences_button').addEventListener('click', function(event) {
            var informations_class = document.getElementById('informations').classList;
            var preferences_class = document.getElementById('preferences').classList;
            informations_class.remove('is-visible');
            informations_class.add('is-hidden');
            preferences_class.remove('is-hidden');
            preferences_class.add('is-visible');
            document.getElementById('preferences_button').classList.add('is-active')
            document.getElementById('informations_button').classList.remove('is-active')
        });
    }

    if (document.getElementById('informations_button')) {
        document.getElementById('informations_button').addEventListener('click', function(event) {
            var informations_class = document.getElementById('informations').classList;
            var preferences_class = document.getElementById('preferences').classList;
            preferences_class.remove('is-visible');
            preferences_class.add('is-hidden');
            informations_class.remove('is-hidden');
            informations_class.add('is-visible');
            document.getElementById('preferences_button').classList.remove('is-active')
            document.getElementById('informations_button').classList.add('is-active')
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
                handleAs   : 'text',
                parameters : { login: login, mail : mail, password : password, newPass1 : newPass1, newPass2 : newPass2,
                                lastName : lastName, firstName : firstName, gender: gender, bio : bio, wait : true },
                onSuccess  : function(message) {
                                if (message === "Profile updated !") {
                                    display_notification("notification", "green", message);
                                    document.getElementById('profile_name').innerHTML = "Hello, " + htmlEntities(login) + " ! 😎";
                                }
                                else
                                    display_notification("notification", "red", message);
                },
                onError    : function(status, message) {
                                display_notification("notification", "red", status + ": " + message);
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
            console.log(preferences_form);
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

};