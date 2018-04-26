document.addEventListener("DOMContentLoaded", function(event) {

   /* ------------ Account ----------- */

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
                            lastName : lastName, firstName : firstName, gender: gender, bio : bio },
            onSuccess  : function(res) {
                            if (res['valid'] === true) {
                                display_notification("notification", "green", res['message']);
                                document.getElementById('account_name').innerHTML = "Hello, " + res['login'] + " ! 😎";
                            }
                            else
                                display_notification("notification", "red", res['message']);
            },
            onError    : function(status, message) {
                            display_notification("notification", "red", status + ": " + message);
            }
        });
        informations_form.elements['password'].value = null;
        informations_form.elements['newPass1'].value = null;
        informations_form.elements['newPass2'].value = null;
        return false;
    }

    var preferences_form = document.getElementById('preferences_form');
    preferences_form.onsubmit = function(e) {
        var notification = preferences_form.elements['notification'].value;
        var theme = preferences_form.elements['theme'].value;
        request  = new Request ({
            url        : "script/updateUserPreference.php",
            method     : 'POST',
            handleAs   : 'json',
            parameters : { notification : notification, theme: theme },
            onSuccess  : function(res) {
                            if (res['valid'] === true)
                                display_notification("notification", "green", res['message']);
                            else
                                display_notification("notification", "red", res['message']);
            },
            onError    : function(status, message) {
                            display_notification("notification", "red", status + ": " + message);
            }
        });
        return false;
    }
});