document.addEventListener("DOMContentLoaded", function(event) {

   /* ------------ Account ----------- */

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
                url        : "script/updateAccount.php",
                method     : 'POST',
                handleAs   : 'json',
                parameters : { login: login, mail : mail, password : password, newPass1 : newPass1, newPass2 : newPass2,
                                lastName : lastName, firstName : firstName, gender: gender, bio : bio, wait : true },
                onSuccess  : function(res) {
                                if (res['message'] === "Account updated !") {
                                    display_notification("notification", "green", res['message']);
                                    document.getElementById('account_name').innerHTML = "Hello, " + res['login'] + " ! 😎";
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
                url        : "script/updateUserPreference.php",
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
});