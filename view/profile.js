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
    if (!isAuthor) {
        document.getElementById('delete' + itemNbr).style.display = 'none';
    }

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
};

document.getElementById('errorMessage').innerHTML = 
        'window.innerWidth ' + window.innerWidth + '  ' + 
        document.documentElement.clientWidth + ' document.documentElement.clientWidth';
