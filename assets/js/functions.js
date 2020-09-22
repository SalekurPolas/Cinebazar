var RatingButton = document.querySelector('.modal_open');
var Modal = document.querySelector('.modal_bg');
var ModalClose = document.querySelector('.modal_cancel');

RatingButton.addEventListener('click', function() {
    Modal.classList.add('modal_active');
});
ModalClose.addEventListener('click', function() {
    Modal.classList.remove('modal_active');
});


function ShowContent(x) {
    document.getElementById(x).style.display = "block";
}

function HideContent(x) {
	document.getElementById(x).style.display = "none";
}

function AlterDisplayContent (x, y) {
    HideContent(x);
    ShowContent(y);
}

function PreviewImage(x, y) {
    var target = document.getElementById(x);
    var file = document.getElementById(y).files[0];
    var reader = new FileReader();

    reader.onloadend = function () {
        target.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        target.src = "assets/images/upload.jpg";
    }
}

function SendToView (x, y) {
    form = document.createElement('form');
    form.setAttribute('method', 'POST');
    form.setAttribute('action', 'view.php');

    view_id = document.createElement('input');
    view_id.setAttribute('name', 'view_id');
    view_id.setAttribute('type', 'hidden');
    view_id.setAttribute('value', x);
    form.appendChild(view_id);

    view_type = document.createElement('input');
    view_type.setAttribute('name', 'view_type');
    view_type.setAttribute('type', 'hidden');
    view_type.setAttribute('value', y);
    form.appendChild(view_type);

    document.body.appendChild(form);
    form.submit();
}

function RateMovie(x, y) {
    Modal.classList.remove('modal_active');
    
    form = document.createElement('form');
    form.setAttribute('method', 'POST');
    form.setAttribute('action', 'assets/php/rate.php');

    movie_id = document.createElement('input');
    movie_id.setAttribute('name', 'movie_id');
    movie_id.setAttribute('type', 'hidden');
    movie_id.setAttribute('value', x);
    form.appendChild(movie_id);

    movie_rate = document.createElement('input');
    movie_rate.setAttribute('name', 'movie_rate');
    movie_rate.setAttribute('type', 'hidden');
    movie_rate.setAttribute('value', y);
    form.appendChild(movie_rate);

    document.body.appendChild(form);
    form.submit();
}