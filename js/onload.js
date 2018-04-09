window.onload = function() {

    /* --------- Burger Menu --------- */

    document.getElementById('burger_menu').onclick = function () {
        document.querySelector('.navbar-menu').classList.toggle('is-active');
    };

    /* --------- Login modal --------- */
    document.getElementById('login_button').addEventListener('click', function(event) {
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

    /* --------- Signup Modal --------- */
    document.querySelector('a#signup_button').addEventListener('click', function(event) {
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
};