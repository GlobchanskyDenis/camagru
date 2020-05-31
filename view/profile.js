function createNewItem(itemNbr, isAuthor, headerData, photoSrc, isLiked, likeCount, photoAuthor) {

    var galery = document.getElementById('galery');
    var item = document.createElement('div');
    var del = document.createElement('img');
    var header = document.createElement('div');
    var photo = document.createElement('img');
    var footer = document.createElement('div');
    var like = document.createElement('img');
    var counter = document.createElement('div');
    var author = document.createElement('div');
    var comment = document.createElement('img');

    item.setAttribute("class", "item");
    item.setAttribute("id", 'item' + itemNbr);

    del.setAttribute("class", "delete");
    del.setAttribute("id", 'delete' + itemNbr);
    del.src = "img/deleteSnap.png";


    header.setAttribute("class", "itemHeader");
    header.setAttribute("id", 'itemHeader' + itemNbr);
    header.innerHTML = headerData;

    photo.setAttribute("class", "photo");
    photo.setAttribute("id", 'photo' + itemNbr);
    photo.src = photoSrc;

    footer.setAttribute("class", "itemFooter");

    like.setAttribute("class", "like");
    like.setAttribute("id", 'like' + itemNbr);
    if (isLiked) {
        like.src = "img/liked.png";
    } else {
        like.src = "img/like.png";
    }

    counter.setAttribute("class", "counter");
    counter.setAttribute("id", 'counter' + itemNbr);
    counter.innerHTML = likeCount;

    author.setAttribute("class", "author");
    author.setAttribute("id", 'author' + itemNbr);
    author.innerHTML = photoAuthor;

    comment.setAttribute("class", "comments");
    comment.setAttribute("id", 'comments' + itemNbr);
    comment.src = "img/comment.png";

    footer.appendChild(like);
    footer.appendChild(counter);
    footer.appendChild(author);
    footer.appendChild(comment);
    item.appendChild(del);
    item.appendChild(header);
    item.appendChild(photo);
    item.appendChild(footer);
    galery.appendChild(item);

    if (!isAuthor) {
        document.getElementById('delete' + itemNbr).style.display = 'none';
    }
};

function howMuchImgPerLine() {
    // more 1650px 1300px 975px 650px
    // window.innerWidth  - can be incorrect in IE browser
    // document.documentElement.clientWidth
    var windowWidth = window.innerWidth;
    var anotherWidth = document.documentElement.clientWidth;

    // Try to make this calc correctly even in IE browser
    if (windowWidth > anotherWidth + 20 ||
        windowWidth < anotherWidth - 20) {
            windowWidth = anotherWidth;
    }
    if (windowWidth <= 650)
        return (1);
    if (windowWidth <= 975)
        return (2);
    if (windowWidth <= 1300)
        return (3);
    if (windowWidth <= 1650)
        return (4);
    return (5);
}

function getPuckOfImages() {

    // Checking permission for function work 
    if (window.gWorkPermission != '') {
        document.getElementById('errorMessage').innerHTML = 'wait 1 second';
        return ;
    }

    // Disable work permission for all function that listen extern events
    window.gWorkPermission = 'denied';

    // Calc - how much images do we need
    var imgPerLine = howMuchImgPerLine();
    var imgAmount = imgPerLine - window.gImgAmount % imgPerLine;
    var tmp = imgPerLine * ( 5 / imgPerLine );
    // console.log('');
    // console.log('imgPerLine = ' + imgPerLine);
    // console.log('current images in document = ' + window.gImgAmount);
    // console.log('to end line we need = ' + imgAmount);
    // console.log('new images we need = ' + tmp);
    imgAmount += tmp;
    // console.log('total images we need = ' + imgAmount);


    // Make async request to DB -  
    // we need imgAmount of photos, starts from last one in database
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/getPhotosByAuthor.php");
    let formData = new FormData();
    formData.append("photoAmount", imgAmount);

    // If there is no elements in current photo list - send '' as lastID
    if ((window.gImgAmount) != 0) {
        formData.append("lastID", window.gSnapID[window.gImgAmount]);
    } else {
        formData.append("lastID", "");
    }

    xhr.send(formData);

    xhr.onload = function() {
		if (xhr.status != 200) {
            document.getElementById('errorMessage').innerHTML = `Ошибка ${xhr.status}: ${xhr.statusText}`;
        } else {
            var requestAsync = JSON.parse(xhr.response);

            if (!requestAsync) {
                document.getElementById('errorMessage').innerHTML = 'empty async request';
                // Enable work permission for all function that listen extern events
                window.gWorkPermission = '';
                return ;
            }

            document.getElementById('errorMessage').innerHTML = requestAsync['error'];

            // If data in request exists - display it
            for (i = 1; i <= imgAmount; i++) {
                if ((requestAsync['img' + i])) {
                    var img;
                    img = requestAsync['img' + i];
                    window.gImgAmount = gImgAmount + 1;
                    createNewItem(  window.gImgAmount,
                                    1, // isAuthor - redact it in future (only in galery)
                                    img['name'],
                                    'data:image/gif;base64,' + img['data'],
                                    0, // is Liked - redact it in future
                                    2, // likeCount - redact it in future
                                    img['author']
                                    );
                    window.gSnapID[window.gImgAmount] = img['id'];
                }
            }
        }
    }

    xhr.onerror = function() {
        document.getElementById('errorMessage').innerHTML = "Запрос не удался";
    };

    // Enable work permission for all function that listen extern events
    window.gWorkPermission = '';
}

window.onload = function() {
    // createNewItem(1, 1, "header", "img/480_360+placeholder.png", 1, 2, "bsabre-c");
    // window.gImgAmount = 1;
    getPuckOfImages();
    // for (i = 1; i < 11; i++) {
    //     var isAuthor = i % 2;
    //     createNewItem(i, isAuthor, "header", "img/480_360+placeholder.png", !(isAuthor), i * 2, "bsabre-c");
    // }
}

var gWorkPermission = '';
var gImgAmount = 0;
var gSnapID = [];

// setInterval( function() {
//     createNewItem(1, 1, "header", "img/480_360+placeholder.png", 1, 2, "bsabre-c");
//     window.gImgAmount = window.gImgAmount + 1;
//     getPuckOfImages();
//     // document.getElementById("errorMessage").innerHTML = howMuchImgPerLine();
// }, 15000);