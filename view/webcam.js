function unselectAll() {
    var sticker0 = document.getElementById('st0');
    var sticker1 = document.getElementById('st1');
    var sticker2 = document.getElementById('st2');
    var sticker3 = document.getElementById('st3');
    var sticker4 = document.getElementById('st4');
    var sticker5 = document.getElementById('st5');
    var sticker6 = document.getElementById('st6');

    sticker0.style.backgroundColor = 'rgb(205, 215, 225)';
    sticker1.style.backgroundColor = 'rgb(205, 215, 225)';
    sticker2.style.backgroundColor = 'rgb(205, 215, 225)';
    sticker3.style.backgroundColor = 'rgb(205, 215, 225)';
    sticker4.style.backgroundColor = 'rgb(205, 215, 225)';
    sticker5.style.backgroundColor = 'rgb(205, 215, 225)';
    sticker6.style.backgroundColor = 'rgb(205, 215, 225)';
}

function selectFilter0() {

    unselectAll();

    var sticker = document.getElementById('st0');
    sticker.style.backgroundColor = 'rgb(165, 175, 185)';

    var filter = document.getElementById('filter');
    filter.src = "img/filters/filter0.png";
}

function selectFilter1() {

    unselectAll();
    
    var sticker = document.getElementById('st1');
    sticker.style.backgroundColor = 'rgb(165, 175, 185)';

    var filter = document.getElementById('filter');
    filter.src = "img/filters/filter1.png";
}

function selectFilter2() {

    unselectAll();
    
    var sticker = document.getElementById('st2');
    sticker.style.backgroundColor = 'rgb(165, 175, 185)';

    var filter = document.getElementById('filter');
    filter.src = "img/filters/filter2.png";
}

function selectFilter3() {

    unselectAll();
    
    var sticker = document.getElementById('st3');
    sticker.style.backgroundColor = 'rgb(165, 175, 185)';

    var filter = document.getElementById('filter');
    filter.src = "img/filters/filter3.png";
}

function selectFilter4() {

    unselectAll();
    
    var sticker = document.getElementById('st4');
    sticker.style.backgroundColor = 'rgb(165, 175, 185)';

    var filter = document.getElementById('filter');
    filter.src = "img/filters/filter4.png";
}

function selectFilter5() {

    unselectAll();
    
    var sticker = document.getElementById('st5');
    sticker.style.backgroundColor = 'rgb(165, 175, 185)';

    var filter = document.getElementById('filter');
    filter.src = "img/filters/filter5.png";
}

function selectFilter6() {

    unselectAll();
    
    var sticker = document.getElementById('st6');
    sticker.style.backgroundColor = 'rgb(165, 175, 185)';

    var filter = document.getElementById('filter');
    filter.src = "img/filters/filter6.png";
}

window.onload = function() {
    selectFilter0();
};


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
    var width = 320;    // We will scale the photo width to this
    var height = 0;     // This will be computed based on the input stream

    var streaming = false;

    var video = null;
    var canvas = null;
    var photo = null;
    var startbutton = null;

    video = document.getElementById('video');
    canvas = document.getElementById('canvas');

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
}

function clearphoto() {
    canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    context.fillStyle = "#AAA";
    context.fillRect(0, 0, canvas.width, canvas.height);

    // var data = canvas.toDataURL('image/png');
    // photo.setAttribute('src', data);
  }