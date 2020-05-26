function selectFilter0() {
    document.getElementById('filter').src = "img/filters/filter0.png";
}

function selectFilter1() {
    document.getElementById('filter').src = "img/filters/filter1.png";
}

function selectFilter2() {
    document.getElementById('filter').src = "img/filters/filter2.png";
}

function selectFilter3() {
    document.getElementById('filter').src = "img/filters/filter3.png";
}

function selectFilter4() {
    document.getElementById('filter').src = "img/filters/filter4.png";
}

function selectFilter5() {
    document.getElementById('filter').src = "img/filters/filter5.png";
}

function selectFilter6() {
    document.getElementById('filter').src = "img/filters/filter6.png";
}

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

function takeshot() {
    var width = 480;
    var height = 0;
    var video = document.getElementById('video');
    var canvas = document.createElement('canvas');
    var message = document.getElementById('errorMessage');

    message.innerHTML = '';

    // Определяю номер использованного фильтра
    // var message = document.getElementById('errorMessage');
    // var filterSrc = document.getElementById('filter').src;
    // message.innerHTML = filterSrc[filterSrc.length - 5];

    // Определяю номер использованного фильтра
    var filter = document.querySelector('input[name = "sticker"]:checked');
    if (!filter || filter.value == 0) {
        message.innerHTML = 'Cannot take photo. Choose filter first.';
        return;
    }
    if (document.forms['snapMetadata']['name'].value == '') {
        message.innerHTML = 'Cannot take photo. Name your snap first.';
        return;
    }
    if (document.forms['snapMetadata']['takePhotoPermission'].value != '') {
        message.innerHTML = 'wait 1 sec dude';
        return;
    }
    document.forms['snapMetadata']['takePhotoPermission'].value = 'denied';

    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
    .then(function(stream) {
        video.srcObject = stream;
        video.play();
    })
    .catch(function(err) {
        console.log("An error occurred: " + err);
    });
    oldHeight = video.videoHeight;
    height = video.videoHeight / (video.videoWidth/width);
      
    // video.setAttribute('width', width);
    // video.setAttribute('height', height);

    var context = canvas.getContext('2d');
    canvas.width = width;
    canvas.height = height;
    context.drawImage(video, 0, 0, width, height);

    // video.setAttribute('height', oldHeight / 1.3333);
    //   var data = canvas.toDataURL('image/png');
    //   photo.setAttribute('src', data);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/createPhoto.php");
    xhr.responseType = 'json';
    let formData = new FormData();
    
    formData.append("name", document.forms['snapMetadata']['name'].value);
    formData.append("img", canvas.toDataURL('image/png'));
    formData.append("filter", filter.value);
    xhr.send(formData);
    xhr.onload = function() {
		if (xhr.status != 200) {
			message = `Ошибка ${xhr.status}: ${xhr.statusText}`;
		} else {
            var requestAjax= xhr.response;
			var message = document.getElementById("errorMessage");

			if (requestAjax.error != '')
                message.innerHTML = requestAjax.error;
            console.log( 'rx: error='+requestAjax.error);
            document.forms['snapMetadata']['takePhotoPermission'].value = '';
        }
    }
	xhr.onerror = function() {
          message = "Запрос не удался";
          document.forms['snapMetadata']['takePhotoPermission'].value = '';
	};
}

// function clearphoto() {
//     canvas = document.getElementById('canvas');
//     var context = canvas.getContext('2d');
//     context.fillStyle = "#AAA";
//     context.fillRect(0, 0, canvas.width, canvas.height);

//     // var data = canvas.toDataURL('image/png');
//     // photo.setAttribute('src', data);
// }
