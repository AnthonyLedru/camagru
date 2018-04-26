document.addEventListener("DOMContentLoaded", function(event) {

    /* ------------- Tab -------------- */
        
    var first_tab = document.getElementById('first_tab').classList;
    var second_tab = document.getElementById('second_tab').classList;

    document.getElementById('first_tab_button').addEventListener('click', function(event) {
        second_tab.remove('is-visible');
        second_tab.add('is-hidden');
        first_tab.remove('is-hidden');
        first_tab.add('is-visible');
        document.getElementById('second_tab_button').classList.remove('is-active')
        document.getElementById('first_tab_button').classList.add('is-active')
    });

    document.getElementById('second_tab_button').addEventListener('click', function(event) {
        first_tab.remove('is-visible');
        first_tab.add('is-hidden');
        second_tab.remove('is-hidden');
        second_tab.add('is-visible');
        document.getElementById('second_tab_button').classList.add('is-active')
        document.getElementById('first_tab_button').classList.remove('is-active')
    });
});