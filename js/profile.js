document.addEventListener("DOMContentLoaded", function(event) {


    function createColumn(image) {
        var column = document.createElement('div');
        column.setAttribute('class', 'column is-one-third');
        column.innerHTML = '<figure class="image is-4by3">\
                                    <a href="photo.php?image_id=' + image.imageId + '">\
                                        <img src="' + image.path + '" alt="profile image">\
                                    </a>\
                            </figure>';
        return column;
    }

    var skip = 6;
    const limit = 6;

    window.onscroll = function() {
        var second_tab = document.getElementById('second_tab');
        if (second_tab.classList.contains('is-visible')) {
            if ((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight) {
                request  = new Request ({
                    url        : "script/loadImages.php",
                    method     : 'POST',
                    handleAs   : 'json',
                    parameters : { skip : skip, limit : limit },
                    onSuccess  : function(res) {
                                    if (res['message'] === "Images loaded") {
                                        var nb_images = document.getElementById('image_container').getElementsByClassName('column').length;
                                        skip += res['images'].length;
                                        for (var i = 0; i < res['images'].length; i++) {
                                            if (nb_images % 3 === 0) {
                                                var columns = document.createElement('div');
                                                columns.setAttribute('class', 'columns is-vcentered image_columns is-centered');
                                                var container = document.getElementById('image_container');
                                                container.appendChild(columns);
                                            }
                                            var column = createColumn(res['images'][i]);
                                            nb_images++;
                                            var columns = document.getElementsByClassName('image_columns');
                                            columns[columns.length - 1].appendChild(column);
                                        }
                                        if (document.getElementsByClassName('like'))
                                            var like_icons = document.getElementsByClassName('like');
                                    }
                                    else
                                        display_notification("notification", "red", res['message']);
                    },
                    onError    : function(status, res) {
                                    display_notification("notification", "red", status + ": " + res['message']);
                    }
                });
            }
        }
    };

});