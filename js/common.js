document.addEventListener("DOMContentLoaded", function(event) {

    var html = document.querySelector('html');

    /* -------- Notification --------- */
    
    document.getElementById('notification').addEventListener('click', function(event) {
        hide_notification("notification");
    });

    /* --------- Burger Menu --------- */

    document.getElementById('burger_menu').onclick = function () {
        document.querySelector('.navbar-menu').classList.toggle('is-active');
    };

    /* --------- Login modal --------- */

    if (document.getElementById('login_button')) {

        var modal = document.getElementById('login_modal');

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
                parameters : { login : login, password : password },
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
                                    lastName : lastName, firstName : firstName, gender: gender },
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

    /* ------------ Logout ------------ */

    if (document.getElementById('logout_button')) {
        document.getElementById('logout_button').addEventListener('click', function(event) {
            request  = new Request ({
                url        : "script/logout.php",
                method     : 'POST',
                handleAs   : 'text',
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

    /* ------------- Tab -------------- */

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
});

function display_notification(div_name, color, message) {
    var notification_div = document.getElementById(div_name);
    notification_div.style.display = "block";
    notification_div.innerHTML = message;
    notification_div.style.backgroundColor = color;
    setTimeout(
        function() {
            notification_div.style.display = "none";
        }, 10000);
}

function hide_notification(div_name) {
    var notification_div = document.getElementById(div_name);
    notification_div.style.display = "none";
}