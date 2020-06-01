function selectFilter0() {
    document.getElementById('filter').style.display = "none";
}

function selectFilter1() {
    document.getElementById('filter').src = "img/filters/filter1.png";
    document.getElementById('filter').style.display = "block";
}

function selectFilter2() {
    document.getElementById('filter').src = "img/filters/filter2.png";
    document.getElementById('filter').style.display = "block";
}

function selectFilter3() {
    document.getElementById('filter').src = "img/filters/filter3.png";
    document.getElementById('filter').style.display = "block";
}

function selectFilter4() {
    document.getElementById('filter').src = "img/filters/filter4.png";
    document.getElementById('filter').style.display = "block";
}

function selectFilter5() {
    document.getElementById('filter').src = "img/filters/filter5.png";
    document.getElementById('filter').style.display = "block";
}

function selectFilter6() {
    document.getElementById('filter').src = "img/filters/filter6.png";
    document.getElementById('filter').style.display = "block";
}

function getSnapsFromDB() {

    // Checking permission for function work 
    if (window.gWorkPermission != '') {
        document.getElementById('errorMessage').innerHTML = 'wait 1 second';
        return ;
    }

    // Disable work permission for all function that listen extern events
    window.gWorkPermission = 'denied';

    // Make async request to DB -  
    // we need 3 photos, starts from last one in database
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/getPhotosByAuthor.php");
    let formData = new FormData();
    formData.append("photoAmount", 3);
    formData.append("lastID", "");
    xhr.send(formData);
    xhr.onload = function() {
		if (xhr.status != 200) {
            document.getElementById('errorMessage').innerHTML = `Ошибка ${xhr.status}: ${xhr.statusText}`;
        } else {
            var requestAjax = JSON.parse(xhr.response);

            if (!requestAjax) {
                document.getElementById('errorMessage').innerHTML = 'empty async request';
                // Enable work permission for all function that listen extern events
                window.gWorkPermission = '';
                return ;
            }

            document.getElementById('errorMessage').innerHTML = requestAjax['error'];

            // If data in request exists - display it
            for (i = 1; i < 4; i++) {
                if ((requestAjax['img' + i])) {
                    var img;
                    img = requestAjax['img' + i];
                    document.getElementById('snap' + i).src = "data:image/gif;base64," + img['data'];
                    document.getElementById('snapBox' + i).style.display = "block";
                    window.gSnapID[i] = img['id'];
                } else {
                    document.getElementById('snap' + i).src = '#';
                    document.getElementById('snapBox' + i).style.display = 'none';
                }
            }
        }
        // Enable work permission for all function that listen extern events
        window.gWorkPermission = '';
    };

    xhr.onerror = function() {
        document.getElementById('errorMessage').innerHTML = "Запрос не удался";
        // Enable work permission for all function that listen extern events
        window.gWorkPermission = '';
    };
}

function takeshot() {
    var width = 480;
    var height = 0;
    var video = document.getElementById('video');
    var image = document.getElementById('uploadPhoto');
    var canvas = document.createElement('canvas');
    var filter = document.querySelector('input[name = "sticker"]:checked');

    // Checking conditions to take photo. Is filter present?
    if (!filter || filter.value == 0) {
        document.getElementById('errorMessage').innerHTML = 'Cannot take photo. Choose filter first.';
        return;
    }
    // Checking conditions to take photo. Is name present?
    if (document.forms['snapMetadata']['name'].value == '') {
        document.getElementById('errorMessage').innerHTML = 'Cannot take photo. Name your snap first.';
        return;
    }
    // Checking permission for function work
    if ( window.gWorkPermission != '' ) {
        document.getElementById('errorMessage').innerHTML = 'wait 1 sec';
        return;
    }

    // Disable work permission for all function that listen extern events
    window.gWorkPermission = 'denied';

    // Save image to canvas object
    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
    .then(function(stream) {
        video.srcObject = stream;
        video.play();
    })
    .catch(function(err) {
        console.log("An error occurred: " + err);
    });
    height = video.videoHeight / (video.videoWidth/width);
    var context = canvas.getContext('2d');
    canvas.width = width;
    canvas.height = height;
    if (image.style.display != "block") {
        context.drawImage(video, 0, 0, width, height);
    } else {
        context.drawImage(image, 0, 0, width, height);
    }

    // Make async request to DB - to create new photo
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/createPhoto.php");
    xhr.responseType = 'json';
    let formData = new FormData();
    
    // Add data to request
    formData.append("name", document.forms['snapMetadata']['name'].value);
    formData.append("img", canvas.toDataURL('image/png'));
    formData.append("filter", filter.value);
    xhr.send(formData);

    xhr.onload = function() {
		if (xhr.status != 200) {
            document.getElementById('errorMessage').innerHTML = `Ошибка ${xhr.status}: ${xhr.statusText}`;
		} else {
            var requestAjax = xhr.response;
            if (!requestAjax) {
                document.getElementById('errorMessage').innerHTML = 'empty async request';
                // Enable work permission for all function that listen extern events
                window.gWorkPermission = '';
                return ;
            }
            document.getElementById('errorMessage').innerHTML = requestAjax.error;

            // getSnapsFromDB();
            // Make async request to DB - we need one last photo
            let newXhr = new XMLHttpRequest();
            newXhr.open("POST", "scripts/getPhotosByAuthor.php");
            newXhr.responseType = 'json';
            let requestData = new FormData();
            requestData.append("photoAmount", 1);
            requestData.append("lastID", "");
            newXhr.send(requestData);

            newXhr.onload = function() {
                if (newXhr.status != 200) {
                    document.getElementById('errorMessage').innerHTML = `Ошибка ${newXhr.status}: ${newXhr.statusText}`;
                } else {
                    var requestAjax = newXhr.response;
                    if (!requestAjax) {
                        document.getElementById('errorMessage').innerHTML = 'empty async request';
                        // Enable work permission for all function that listen extern events
                        window.gWorkPermission = '';
                        return ;
                    }
                    if (requestAjax.img1) {
                        // shift all photos and its metadata down
                        for (i = 3; i > 1; i--) {
                            document.getElementById('snap' + i).src = document.getElementById('snap' + (i - 1)).src;
                            document.getElementById('snapBox' + i).style.display = 
                                    document.getElementById('snapBox' + (i - 1)).style.display;
                            window.gSnapID[i] = window.gSnapID[i - 1];
                        }
                        // display request data as first element of photo list
                        document.getElementById('snap1').src = "data:image/gif;base64," + requestAjax.img1.data;
                        document.getElementById('snapBox1').style.display = "block";
                        window.gSnapID[1] = requestAjax.img1.id;
                    }
                }
                // Enable work permission for all function that listen extern events
                window.gWorkPermission = '';
            }

            newXhr.onerror = function() {
                document.getElementById('errorMessage').innerHTML = "Запрос не удался";
                // Enable work permission for all function that listen extern events
                window.gWorkPermission = '';
            };
        }
    }

	xhr.onerror = function() {
        document.getElementById('errorMessage').innerHTML = "Запрос не удался";
        // Enable work permission for all function that listen extern events
        window.gWorkPermission = '';
    };

    image.style.display = "none";
    video.style.opacity = 1;
}

function deletePhoto(i) {

    // Checking permission for function work
    if (window.gWorkPermission != '') {
        document.getElementById('errorMessage').innerHTML = 'wait 1 second';
        return ;
    }

    // Disable work permission for all function that listen extern events
    window.gWorkPermission = 'denied';

    // Make async request to DB -  
    // we need delete photo by its ID
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/asyncDeletePhoto.php");
    xhr.responseType = 'json';
    let formData = new FormData();
    formData.append("id", window.gSnapID[i]);
    xhr.send(formData);
    xhr.onload = function() {
		if (xhr.status != 200) {
            document.getElementById('errorMessage').innerHTML = `Ошибка ${xhr.status}: ${xhr.statusText}`;
        } else {
            var requestAjax = xhr.response;
            if (!requestAjax) {
                document.getElementById('errorMessage').innerHTML = 'empty async request';
                // Enable work permission for all function that listen extern events
                window.gWorkPermission = '';
                return ;
            }
            document.getElementById('errorMessage').innerHTML = requestAjax.error;
            if (requestAjax.error == '') {

                while ( document.getElementById( 'snap' + (i + 1) ) != null &&
                        document.getElementById( 'snapBox' + (i + 1) ).style.display != 'none' ) {
                    document.getElementById( 'snap' + i ).src = document.getElementById( 'snap' + (i + 1) ).src;
                    window.gSnapID[i] = window.gSnapID[i + 1];
                    i++;
                }
                document.getElementById( 'snapBox' + i ).style.display = "none";
                window.gSnapID.pop();

                // Make async request to DB -  
                // Get one photo, to pushback it as last element of current photo list
                let newXhr = new XMLHttpRequest();
                newXhr.open("POST", "scripts/getPhotosByAuthor.php");
                newXhr.responseType = 'json';
                let requestData = new FormData();
                requestData.append("photoAmount", 1);

                // If there is no elements in current photo list - send '' as lastID
                if ((i - 1) != 0) {
                    requestData.append("lastID", window.gSnapID[i - 1]);
                } else {
                    requestData.append("lastID", "");
                }
                
                newXhr.send(requestData);

                newXhr.onload = function() {
                    if (newXhr.status != 200) {
                        document.getElementById('errorMessage').innerHTML = `Ошибка ${newXhr.status}: ${newXhr.statusText}`;
                    } else {
                        var requestAsync = newXhr.response;
                        if (requestAsync) {
                            document.getElementById('errorMessage').innerHTML = requestAsync.error;
                            if ((requestAsync.img1)) {
                                document.getElementById('snap'+i).src = "data:image/gif;base64," + requestAsync.img1.data;
                                document.getElementById('snapBox'+i).style.display = "block";
                                window.gSnapID[i] = requestAsync.img1.id;
                            } else {
                                document.getElementById('snap'+i).src = '#';
                                document.getElementById('snapBox'+i).style.display = 'none';
                            }
                        } else {
                            document.getElementById('errorMessage').innerHTML = 'empty async request';
                        }
                    }
                    // Enable work permission for all function that listen extern events
                    window.gWorkPermission = '';
                };

                newXhr.onerror = function() {
                    document.getElementById('errorMessage').innerHTML = "Запрос не удался";
                    // Enable work permission for all function that listen extern events
                    window.gWorkPermission = '';
                };
            }
        }
    }
    xhr.onerror = function() {
        document.getElementById('errorMessage').innerHTML = "Запрос удаления фото не удался";
        // Enable work permission for all function that listen extern events
        window.gWorkPermission = '';
    };
}

function hangEventListeners() {
    var i = 1;
    while ( document.getElementById( 'del' + i ) ) {
        document.getElementById( 'del' + i ).addEventListener("click", deletePhoto.bind(null, i));
        i++;
    }
}

function setVideoON() {
    navigator.getUserMedia = navigator.getUserMedia ||  navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    if (navigator.getUserMedia) {
        navigator.getUserMedia({ audio: true, video: true},
        function(stream) {
            var video = document.querySelector('video');
            video.srcObject = stream;
            video.onloadedmetadata = function(e) {
            video.play();
            };
        },
        function(err) {
            console.log("The following error occurred: " + err.name);
        }
        );
    } else {
        console.log("getUserMedia not supported");
    }
}

var gWorkPermission = '';
var gSnapID = [];

window.onload = function() {
    setVideoON();
    getSnapsFromDB();
    hangEventListeners();
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        image = document.getElementById('uploadPhoto');
        image.style.display = "block";
        document.getElementById('video').style.opacity = 0;
        reader.onload = function(e) {
            image.setAttribute('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
    image_statut = true;
}