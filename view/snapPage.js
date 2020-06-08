function createNewItem(itemNbr, commentText) {

    var snap = document.getElementById('snap');
    var item = document.createElement('div');
    var comment = document.createElement('div');

	item.setAttribute("class", "comment");
	item.setAttribute("id", "comment"+itemNbr);
	comment.setAttribute("class", "commentBody");
	comment.setAttribute("id", "commentBody"+itemNbr);
	comment.innerHTML = commentText;

    item.appendChild(comment);
    snap.appendChild(item);
}

function shiftCommentsDown() {
    for (i = window.gCommentAmount - 1; i > 0; i--) {
        document.getElementById('commentBody' + (i + 1)).innerHTML = document.getElementById('commentBody' + i).innerHTML;
        window.gCommentID[i+1] = window.gCommentID[i];
    }
}

function addComment() {
    var text = document.getElementById('commentField').value;

    if (!text) {
        document.getElementById('errorMessage').innerHTML = 'Your comment is empty';
        return;
    }

    // Checking permission for function work
    if (window.gWorkPermission != '') {
        document.getElementById('errorMessage').innerHTML = 'wait 1 second';
        return ;
    }

    // Disable work permission for all function that listen extern events
    window.gWorkPermission = 'denied';

    // Make async request to DB -
    // we need imgAmount of photos, starts from last one in database
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/addComment.php");
    let formData = new FormData();
    formData.append("photoID", window.gPhotoID);
    formData.append("text", text);

    if (window.gCommentAmount) {
        formData.append("lastID", window.gCommentID[1]);
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

            if (!requestAsync.error) {
                var i = 1;
                // console.log(requestAsync);
                while (requestAsync['comment'+i]) {
                    // console.log('comment '+i);
                    window.gCommentAmount = window.gCommentAmount + 1;
                    createNewItem(  window.gCommentAmount, '' );
                    shiftCommentsDown();
                    document.getElementById('commentBody1').innerHTML = requestAsync['comment'+i].date + ' : ' + 
                                                                        requestAsync['comment'+i].author + ' : ' + 
                                                                        requestAsync['comment'+i].text;
                    window.gCommentID[1] = requestAsync['comment'+i].id;
                    i++;
                }
                document.getElementById('commentField').value = '';
            }

            // Enable work permission for all function that listen extern events
            window.gWorkPermission = '';
        }
    }

    xhr.onerror = function() {
        document.getElementById('errorMessage').innerHTML = "Запрос не удался";
        // Enable work permission for all function that listen extern events
        window.gWorkPermission = '';
    };
}

function getPuckOfComments() {

    var commentAmount = 10;

    // Checking permission for function work
    if (window.gWorkPermission != '') {
        document.getElementById('errorMessage').innerHTML = 'wait 1 second';
        return ;
    }

    // Disable work permission for all function that listen extern events
    window.gWorkPermission = 'denied';

    var isNeedToDisplayMoreBlock = 1;

    // Make async request to DB -
    // we need imgAmount of photos, starts from last one in database
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/getComments.php");
    let formData = new FormData();
	formData.append("commentAmount", commentAmount);
	formData.append("photoID", window.gPhotoID);

    // If there is no elements in current photo list - send '' as lastID
    if ((window.gCommentAmount) != 0) {
        formData.append("lastID", window.gCommentID[window.gCommentAmount]);
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
            for (i = 1; i <= commentAmount; i++) {
                if ((requestAsync['comment' + i])) {
                    var newComment;
                    newComment = requestAsync['comment' + i];
                    window.gCommentAmount = window.gCommentAmount + 1;
                    window.gCommentID[window.gCommentAmount] = newComment['id'];
                    createNewItem(  window.gCommentAmount,
									newComment['date'] + ' : ' + newComment['author'] + ' : ' + newComment['text']
                                    );
                    window.gCommentID[window.gCommentAmount] = newComment['id'];
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

function downloadPhoto() {

    var url_string = window.location.href;

    var url = new URL(url_string);

	var photoID = url.searchParams.get("photoID");

	if (!photoID) {
		document.getElementById('errorMessage').innerHTML = 'cannot find photo id';
		return ;
	}

    // Checking permission for function work 
    if (window.gWorkPermission != '') {
        document.getElementById('errorMessage').innerHTML = 'wait 1 second';
        return ;
    }

    // Disable work permission for all function that listen extern events
    window.gWorkPermission = 'denied';


    // Make async request to DB -  
    // we need imgAmount of photos, starts from last one in database
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/getPhotoByID.php");
    let formData = new FormData();
    formData.append("photoID", photoID);

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
            if (!requestAsync['error']) {
                document.getElementById('snapImg').src = 'data:image/gif;base64,' + requestAsync['img'];
                document.getElementById('snap').style.display = 'block';
                window.gPhotoID = photoID;
            }

            // Enable work permission for all function that listen extern events
			window.gWorkPermission = '';
			
			getPuckOfComments();
        }
    }

    xhr.onerror = function() {
        document.getElementById('errorMessage').innerHTML = "Запрос не удался";
        // Enable work permission for all function that listen extern events
        window.gWorkPermission = '';
    };
}



var gWorkPermission = '';
var gCommentAmount = 0;
var gCommentID = [];
var gPhotoID = 0;

window.onload = function() {
	downloadPhoto();
    document.getElementById('more').addEventListener("click", getPuckOfComments);
    document.getElementById('submit').addEventListener("click", addComment);
}
