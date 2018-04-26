document.addEventListener("DOMContentLoaded", function(event) {

    /* ---------- Edit Photo --------- */

    if (document.querySelector("#video")) {
        HTMLMediaElement.srcObject
        var ctx;
        var video = document.querySelector("#video");
        var photo_list = document.getElementById("photo_list");
        var constraints = { audio: false, video: true }; 

        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || 
                                navigator.mozGetUserMedia || navigator.msGetUserMedia || 
                                navigator.oGetUserMedia;
        
        if (navigator.getUserMedia)     
            navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
            video.srcObject = stream;
            document.getElementById("take_photos").addEventListener('click', function(e) {
                var applied_filters = document.getElementById('filters');
                var data = {};
                var filters = [];
                canvas = document.createElement("canvas");
                canvas.width = video.videoWidth / 1.7;
                canvas.height = video.videoHeight / 1.7;
                data.width = video.videoWidth / 1.7;
                data.height = video.videoHeight / 1.7;
                if (document.getElementById('uploaded_photo')) {
                    var uploaded_photo = document.getElementById('uploaded_photo');
                    canvas.getContext('2d').drawImage(uploaded_photo, 0, 0, canvas.width, canvas.height);
                    uploaded_photo.remove();
                }
                else
                    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                data.img_data = canvas.toDataURL('image/png');
                for (var i = 0; i < applied_filters.options.length; i++)
                    if (applied_filters.options[i].selected)
                        filters.push(applied_filters.options[i].value); 
                data.filters = filters;
                data.description = document.getElementById('description').value;
                canvas.remove();
                request  = new Request ({
                    url        : "script/mergeImages.php",
                    method     : 'POST',
                    handleAs   : 'json',
                    parameters : { data : JSON.stringify(data) },
                    onSuccess  : function(res) {
                                    if (res['message'] === "Image added to the list") {
                                        display_notification("notification", "green", res['message']);
                                        var img = document.createElement("img");
                                        img.classList.add("cam_photo");
                                        img.setAttribute('src', res['photo']);
                                        photo_list.appendChild(img);
                                        var p = document.createElement("p");
                                        p.innerHTML = res['description'];
                                        photo_list.appendChild(p);
                                        var br = document.createElement("br");
                                        photo_list.appendChild(br);
                                        img.addEventListener('click', removeImage(br, p));
                                        
                                    }
                                    else
                                        display_notification("notification", "red", res['message']);
                    },
                    onError    : function(status, res) {
                                    display_notification("notification", "red", status + ": " + res['message']);
                    }
                });

            });
        }).catch(function (error) {
            document.getElementById('video').style.display = 'none';
            var p = document.createElement('p');
            p.classList.add("has-text-centered");
            p.innerHTML = "Your webcam is not active";
            var first = document.getElementsByClassName('cam_menu')[0].childNodes[2];
            document.getElementsByClassName('cam_menu')[0].insertBefore(p, first);
            document.getElementById('take_photos').disabled = true;
        });

        document.getElementById("cancel_photo").addEventListener('click', function(e) {
            if (photo_list.firstChild) {
                while (photo_list.firstChild) {
                    photo_list.removeChild(photo_list.firstChild);
                }
                display_notification("notification", "green", "Image(s) removed");
            } else
                display_notification("notification", "red", "There is no images to remove");
        });

        document.getElementById("save_photo").addEventListener('click', function(e) {
            var imagesTab = {};
            var images = photo_list.getElementsByTagName('img');
            var descriptions = photo_list.getElementsByTagName('p');
            var j = 0;
            for (var i = 0; i < images.length; i++) {
                var imageInfo = {};
                imageInfo.src = images[i].src;
                imagesTab[j] = imageInfo;
                j++;
            }
            j = 0;
            for (i = 0; i < descriptions.length; i++) {
                imagesTab[j].description = descriptions[i].innerHTML;
                j++;
            }
            while (photo_list.firstChild) {
                photo_list.removeChild(photo_list.firstChild);
            }
            request  = new Request ({
                url        : "script/saveImages.php",
                method     : 'POST',
                handleAs   : 'json',
                parameters : { imagesTab : JSON.stringify(imagesTab) },
                onSuccess  : function(res) {
                                if (res['message'] === "Image(s) saved") {
                                    display_notification("notification", "green", res['message']);
                                }
                                else
                                    display_notification("notification", "red", res['message']);
                },
                onError    : function(status, res) {
                                display_notification("notification", "red", status + ": " + res['message']);
                }
            });
        });

        function removeImage(br, p) {
            return function() {
                this.remove();
                br.remove();
                p.remove();
            }
        }

        document.getElementById('filters').addEventListener('change', function(e) {
            var applied_filters = document.getElementById('filters');
            var inner_container = document.getElementById('inner_container');
            var elements = inner_container.getElementsByTagName("img");
            var has_selected_filter = false;
            for (i = elements.length - 1; i >= 0; i--)
                if (elements[i].id != "uploaded_photo")
                    elements[i].remove();
            for (var i = 0; i < applied_filters.options.length; i++) {
                if (applied_filters[i].selected) {
                    has_selected_filter = true;
                    var img = document.createElement('img');
                    img.src = applied_filters.options[i].value;
                    img.classList.add("video_overlay");
                    inner_container.insertBefore(img, document.getElementById('video'));
                }
            }
            if (has_selected_filter)
                document.getElementById('take_photos').disabled = false;
            else
                document.getElementById('take_photos').disabled = true;
        });


        function createImage() {
            img = new Image();
            img.onload = function imageLoaded() {
                if (document.getElementById("uploaded_photo"))
                    document.getElementById("uploaded_photo").remove();
                var img = document.createElement('img');
                img.src = this.src;
                img.classList.add("video_overlay");
                img.id = "uploaded_photo";
                inner_container.insertBefore(img, inner_container.firstChild);
            };
            img.src = this.result;
        }

        file_input.onchange = function(event) {
            var img = document.getElementById('file_input').files[0];
            var reader = new FileReader();
            reader.onload = createImage;
            reader.readAsDataURL(img); 
        }
    }
});