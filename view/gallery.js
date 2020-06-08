function createNewItem(itemNbr, isAuthor, headerData, photoSrc, isLiked, likeCounter, photoAuthor) {

    var galery = document.getElementById('galery');
    var item = document.createElement('div');
    var del = document.createElement('img');
    var header = document.createElement('div');
    var photo = document.createElement('img');
    var footer = document.createElement('div');
    var like = document.createElement('img');
    var counter = document.createElement('div');
    var author = document.createElement('div');
    var commentForm = document.createElement('form');
    var comment = document.createElement('input');
    var hid = document.createElement('input');

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
    counter.innerHTML = likeCounter;

    author.setAttribute("class", "author");
    author.setAttribute("id", 'author' + itemNbr);
    author.innerHTML = photoAuthor;

    comment.type = 'submit';

    hid.type = "hidden";
    hid.name = "photoID";
    hid.value = window.gSnapID[itemNbr];
    hid.setAttribute("id", 'hid' + itemNbr);

    commentForm.method = "GET";
    commentForm.action = "snapPage.php";//?photoID=" + itemNbr;
    commentForm.setAttribute("class", "comments");

    
    commentForm.appendChild(comment);
    commentForm.appendChild(hid);
    footer.appendChild(like);
    footer.appendChild(counter);
    footer.appendChild(author);
    footer.appendChild(commentForm);
    item.appendChild(del);
    item.appendChild(header);
    item.appendChild(photo);
    item.appendChild(footer);
    galery.appendChild(item);

    if (!isAuthor) {
        document.getElementById('delete' + itemNbr).style.display = 'none';
        document.getElementById('author' + itemNbr).style.color = 'rgb(70, 100, 120)';
    } else {
        document.getElementById('author' + itemNbr).style.color = 'rgb(255, 80, 80)';
    }
    document.getElementById( 'delete' + itemNbr ).addEventListener("click", deletePhoto.bind(null, itemNbr));
    document.getElementById( 'like' + itemNbr ).addEventListener("click", likePhoto.bind(null, itemNbr));
}

function shiftItemUp(i) {
    document.getElementById('itemHeader' + i).innerHTML = document.getElementById('itemHeader' + (i + 1)).innerHTML;
    document.getElementById('photo' + i).src = document.getElementById('photo' + (i + 1)).src;
    document.getElementById('like' + i).src = document.getElementById('like' + (i + 1)).src;
    document.getElementById('counter' + i).innerHTML = document.getElementById('counter' + (i + 1)).innerHTML;
    document.getElementById('author' + i).innerHTML = document.getElementById('author' + (i + 1)).innerHTML;
    document.getElementById('delete' + i).style.display = document.getElementById('delete' + (i + 1)).style.display;
    document.getElementById('author' + i).style.color = document.getElementById('author' + (i + 1)).style.color;
    document.getElementById('hid' + i).value = document.getElementById('hid' + (i + 1)).value;
}

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
    imgAmount += imgPerLine * parseInt( 8 / imgPerLine );
    var isNeedToDisplayMoreBlock = 1;


    // Make async request to DB -  
    // we need imgAmount of photos, starts from last one in database
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/getAllPhotos.php");
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
                // This trick is only to be pretty. To not see this when pictures is loading
                // document.getElementById('more').style.opacity = 0.6;
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
                    window.gSnapID[window.gImgAmount] = img['id'];
                    createNewItem(  window.gImgAmount,
                                    img['isAuthor'],
                                    img['name'],
                                    'data:image/gif;base64,' + img['data'],
                                    img['isLiked'],
                                    img['likeCounter'],
                                    img['author']
                                    );
                } else {
                    isNeedToDisplayMoreBlock = 0;
                }
            }

            // display block 'more' if we need it
            if (isNeedToDisplayMoreBlock) {
                document.getElementById('more').style.display = 'block';
            } else {
                document.getElementById('more').style.display = 'none';
            }

            // Enable work permission for all function that listen extern events
            window.gWorkPermission = '';
        }
    }

    xhr.onerror = function() {
        document.getElementById('errorMessage').innerHTML = "Запрос не удался";
        // display block 'more' if we need it
        if (isNeedToDisplayMoreBlock) {
            document.getElementById('more').style.display = 'block';
        } else {
            document.getElementById('more').style.display = 'none';
        }
        // Enable work permission for all function that listen extern events
        window.gWorkPermission = '';
    };
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
            var requestAsync = xhr.response;
            if (!requestAsync) {
                document.getElementById('errorMessage').innerHTML = 'empty async request';
                // Enable work permission for all function that listen extern events
                window.gWorkPermission = '';
                return ;
            }
            document.getElementById('errorMessage').innerHTML = requestAsync.error;
            if (requestAsync.error == '') {

                while ( document.getElementById( 'item' + (i + 1) ) ) {
                    shiftItemUp(i);
                    window.gSnapID[i] = window.gSnapID[i + 1];
                    i++;
                }
                document.getElementById( 'item' + i ).style.display = "none";
                document.getElementById( 'galery' ).removeChild( document.getElementById( 'item' + i ) );
                window.gSnapID.pop();
                window.gImgAmount = window.gImgAmount - 1;


                // Make async request to DB -  
                // Get one photo, to pushback it as last element of current photo list
                let newXhr = new XMLHttpRequest();
                newXhr.open("POST", "scripts/getAllPhotos.php");
                newXhr.responseType = 'json';
                let requestData = new FormData();
                requestData.append("photoAmount", 1);

                // If there is no elements in current photo list - send '' as lastID
                if ((window.gImgAmount) != 0) {
                    requestData.append("lastID", window.gSnapID[window.gImgAmount]);
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
                                window.gImgAmount = window.gImgAmount + 1;
                                window.gSnapID[i] = requestAsync.img1.id;
                                createNewItem(  window.gImgAmount, 
                                                requestAsync.img1.isAuthor,
                                                requestAsync.img1.name, 
                                                'data:image/gif;base64,' + requestAsync.img1.data, 
                                                requestAsync.img1.isLiked,
                                                requestAsync.img1.likeCounter,
                                                requestAsync.img1.author
                                                );
                            } 
                            else {
                                document.getElementById('more').style.display = 'none';
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

function likePhoto(i) {
    // Checking permission for function work
    if (window.gWorkPermission != '') {
        document.getElementById('errorMessage').innerHTML = 'wait 1 second';
        return ;
    }

    // Disable work permission for all function that listen extern events
    window.gWorkPermission = 'denied';

    // Make async request to DB -  
    // we need like photo by its ID
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/asyncLikePhoto.php");
    xhr.responseType = 'json';
    let formData = new FormData();
    formData.append("id", window.gSnapID[i]);
    xhr.send(formData);

    xhr.onload = function() {
		if (xhr.status != 200) {
            document.getElementById('errorMessage').innerHTML = `Ошибка ${xhr.status}: ${xhr.statusText}`;
        } else {
            var requestAsync = xhr.response;
            if (!requestAsync) {
                document.getElementById('errorMessage').innerHTML = 'empty async request';
                // Enable work permission for all function that listen extern events
                window.gWorkPermission = '';
                return ;
            }
            document.getElementById('errorMessage').innerHTML = requestAsync.error;
            if (!requestAsync.error) {
                if (requestAsync.isLiked) {
                    document.getElementById('like' + i).src = "img/liked.png";
                } else {
                    document.getElementById('like' + i).src = "img/like.png";
                }
                document.getElementById('counter' + i).innerHTML = requestAsync.newLikeCounter;
                if (requestAsync['meta1']) {
                    console.log(requestAsync['meta1']);
                }
                if (requestAsync['meta2']) {
                    console.log(requestAsync['meta2']);
                }
            }
            // Enable work permission for all function that listen extern events
            window.gWorkPermission = '';
        }
    }

    xhr.onerror = function() {
        document.getElementById('errorMessage').innerHTML = "Запрос удаления фото не удался";
        // Enable work permission for all function that listen extern events
        window.gWorkPermission = '';
    };
}

window.onload = function() {
    getPuckOfImages();
    document.getElementById('more').addEventListener("click", getPuckOfImages);
    //getNotifications();
    console.log('gallery');
}

var gWorkPermission = '';
var gImgAmount = 0;
var gSnapID = [];