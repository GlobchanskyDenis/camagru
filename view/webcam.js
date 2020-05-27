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

function getLastSnaps() {

    // Выполняю пустой асинхронный запрос к скрипту из БД
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/getLastPhoto.php");
    xhr.responseType = 'json';
    xhr.send();
    xhr.onload = function() {
		if (xhr.status != 200) {
			document.getElementById('errorMessage').innerHTML = `Ошибка ${xhr.status}: ${xhr.statusText}`;
        } else {
            var requestAjax = xhr.response;
            if (!requestAjax) {
                document.getElementById('errorMessage').innerHTML = 'empty async request';
                return ;
            }
            if (requestAjax.error != '')
                document.getElementById('errorMessage').innerHTML = requestAjax.error;
            console.log('');
            console.log( 'rx: error='+requestAjax.error);
            console.log( 'rx: img1='+requestAjax.img1.filename);
            console.log( 'rx: img2='+requestAjax.img2.filename);
            console.log( 'rx: img3='+requestAjax.img3.filename);

            // Если в запросе пришла информация о картинке - вывожу ее на экран.
            // console.log("rx: img1 = " + requestAjax.img1);
            if ((requestAjax.img1)) {
                document.getElementById('lastImg1').src = "data:image/gif;base64," + requestAjax.img1.data;
                document.getElementById('lastImg1').style.display = "block";
                // console.log("rx: img1 = something");
            } else {
                document.getElementById('lastImg1').src = '#';
                document.getElementById('lastImg1').style.display = 'none';
                // console.log("rx: img1 = false");
            }

            // console.log("rx: img2 = " + requestAjax.img2);
            if ((requestAjax.img2)) {
                document.getElementById('lastImg2').src = "data:image/gif;base64," + requestAjax.img2.data;
                document.getElementById('lastImg2').style.display = "block";
                // console.log("rx: img2 = something");
            } else {
                document.getElementById('lastImg2').src = '#';
                document.getElementById('lastImg2').style.display = 'none';
                // console.log("rx: img2 = false");
            }

            // console.log("rx: img3 = " + requestAjax.img3);
            if ((requestAjax.img3)) {
                document.getElementById('lastImg3').src = "data:image/gif;base64," + requestAjax.img3.data;
                document.getElementById('lastImg3').style.display = "block";
                // console.log("rx: img3 = something");
            } else {
                document.getElementById('lastImg3').src = '#';
                document.getElementById('lastImg3').style.display = 'none';
                // console.log("rx: img3 = false");
            }
        }
    }
    xhr.onerror = function() {
        document.getElementById('errorMessage').innerHTML = "Запрос не удался";
        document.forms['snapMetadata']['takePhotoPermission'].value = '';
    };
}

function takeshot() {
    var width = 480;
    var height = 0;
    var video = document.getElementById('video');
    var canvas = document.createElement('canvas');
    var filter = document.querySelector('input[name = "sticker"]:checked');

    // Определяю номер использованного фильтра
    if (!filter || filter.value == 0) {
        document.getElementById('errorMessage').innerHTML = 'Cannot take photo. Choose filter first.';
        return;
    }
    // Проверяю, выполнены ли условия для фотографии
    if (document.forms['snapMetadata']['name'].value == '') {
        document.getElementById('errorMessage').innerHTML = 'Cannot take photo. Name your snap first.';
        return;
    }
    // Проверяю, разрешено ли фотографировать
    if (document.forms['snapMetadata']['takePhotoPermission'].value != '') {
        document.getElementById('errorMessage').innerHTML = 'wait 1 sec dude';
        return;
    }

    // Запрещаю использовать кнопку до конца работы функции и очищаю окно сообщений об ошибках
    document.forms['snapMetadata']['takePhotoPermission'].value = 'denied';

    // Сохраняю изображение в объекте canvas
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
    context.drawImage(video, 0, 0, width, height);

    // Выполняю асинхронный запрос
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/createPhoto.php");
    xhr.responseType = 'json';
    let formData = new FormData();
    
    // Дополняю запрос информацией
    formData.append("name", document.forms['snapMetadata']['name'].value);
    formData.append("img", canvas.toDataURL('image/png'));
    formData.append("filter", filter.value);
    xhr.send(formData);

    xhr.onload = function() {
		if (xhr.status != 200) {
			document.getElementById('errorMessage').innerHTML = `Ошибка ${xhr.status}: ${xhr.statusText}`;
		} else {
            var requestAjax= xhr.response;
            document.getElementById('errorMessage').innerHTML = requestAjax.error;
            getLastSnaps();
            // Разрешаю делать новые снимки
            document.forms['snapMetadata']['takePhotoPermission'].value = '';
        }
    }

	xhr.onerror = function() {
        document.getElementById('errorMessage').innerHTML = "Запрос не удался";
        // Разрешаю делать новые снимки
        document.forms['snapMetadata']['takePhotoPermission'].value = '';
	};
}

function deletePhoto(id) {
    // Выполняю пустой асинхронный запрос к скрипту из БД
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/deletePhoto.php");
    xhr.responseType = 'json';
    xhr.send();
    xhr.onload = function() {
		if (xhr.status != 200) {
			document.getElementById('errorMessage').innerHTML = `Ошибка ${xhr.status}: ${xhr.statusText}`;
        } else {
        }
    }
    xhr.onerror = function() {
        document.getElementById('errorMessage').innerHTML = "Запрос удаления фото не удался";
        document.forms['snapMetadata']['takePhotoPermission'].value = '';
    };
}

window.onload = getLastSnaps();
