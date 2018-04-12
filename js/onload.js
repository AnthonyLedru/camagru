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

    /* --------- Notification --------- */

    document.getElementById('notification').addEventListener('click', function(event) {
        hide_notification("notification");
    });

    /* ------------ Logout ------------ */

    if (document.getElementById('logout_button')) {
        var need_reload = false;
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
            return false;
        });
    }

};