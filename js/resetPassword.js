document.addEventListener("DOMContentLoaded", function(event) {

    /* -------- Reset Password ------- */

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
});