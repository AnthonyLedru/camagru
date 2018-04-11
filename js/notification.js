function display_notification(div_name, color, message) {
    var notification_div = document.getElementById(div_name);
    notification_div.style.display = "block";
    notification_div.innerHTML = message;
    notification_div.style.backgroundColor = color;
    setTimeout(
        function() {
            notification_div.style.display = "none";
        }, 5000);
}

function hide_notification(div_name) {
    var notification_div = document.getElementById(div_name);
    notification_div.style.display = "none";
}