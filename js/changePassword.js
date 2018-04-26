document.addEventListener("DOMContentLoaded", function(event) {

    /* -------- Change Password ------- */

    if (document.getElementById('reset_password_form')) {
        document.getElementById('reset_password_form').onsubmit = function(e) {
            var mail = this.elements['mail'].value;
            request  = new Request ({
                url        : "script/resetPasswordMail.php",
                method     : 'POST',
                handleAs   : 'json',
                parameters : { mail : mail },
                onSuccess  : function(res) {
                                if (res['valid'])
                                    display_notification("notification", "green", res['message']);
                                else
                                    display_notification("notification", "red", res['message']);
                },
                onError    : function(status, message) {
                                display_notification("notification", "red", status + ": " + message);
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
                onSuccess  : function(res) {
                                if (res['valid']) {
                                    location.reload();
                                } else {
                                    display_notification("notification", "red", res['message']);
                                }
                },
                onError    : function(status, message) {
                                display_notification("notification", "red", status + ": " + message);
                }
            });
            document.getElementById("change_password_form").reset();
            return false;
        }
    }
});