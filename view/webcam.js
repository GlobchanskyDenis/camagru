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

    height = video.videoHeight / (video.videoWidth/width);
      
    video.setAttribute('width', width);
    video.setAttribute('height', height);

    var context = canvas.getContext('2d');
    canvas.width = width;
    canvas.height = height;
    context.drawImage(video, 0, 0, width, height);
    
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