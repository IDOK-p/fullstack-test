<!DOCTYPE html>
<html>
<head>
    <title>Comments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body { background: #f5f6fa; }

    .card {
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .btn { border-radius: 8px; }

    #pagination button {
        margin: 3px;
        border-radius: 6px;
    }

    .form-control { border-radius: 8px; }

    .error {
        color: red;
        font-size: 14px;
    }
</style>
</head>
<body class="container mt-4">

<h3>Комментарии</h3>
<div class="row mb-3">
    <div class="col-md-3">
        <select id="sort" class="form-control">
            <option value="id">Сортировка по ID</option>
            <option value="date">Сортировка по дате</option>
        </select>
    </div>

    <div class="col-md-3">
        <select id="order" class="form-control">
            <option value="DESC">По убыванию</option>
            <option value="ASC">По возрастанию</option>
        </select>
    </div>
</div>

<div id="comments"></div>
<div id="pagination" class="mt-3"></div>

<hr>

<h4>Добавить комментарий</h4>

<input id="email" class="form-control mb-2" placeholder="Email">
<div id="emailError" class="error mb-2"></div>
<textarea id="text" class="form-control mb-2" placeholder="Текст"></textarea>
<input id="date" class="form-control mb-2" placeholder="Дата">

<button id="addBtn" class="btn btn-primary">Добавить</button>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
let page = 1;
let sort = 'id';
let order = 'DESC';

function loadComments() {
    $.get('/index.php/comments', {page, sort, order}, function(res) {

        let html = '';

        res.data.forEach(c => {
            html += `
                <div class="card mb-2">
                    <div class="card-body">
                        <b>${c.name}</b> (${c.date})
                        <p>${c.text}</p>
                        <button onclick="deleteComment(${c.id})" class="btn btn-danger btn-sm">Удалить</button>
                    </div>
                </div>
            `;
        });

        $('#comments').html(html);

        let pages = Math.ceil(res.total / 3);
        let pagHtml = '';

        for (let i = 1; i <= pages; i++) {
            pagHtml += `<button onclick="setPage(${i})">${i}</button>`;
        }

        $('#pagination').html(pagHtml);
    });
}

function setPage(p) {
    page = p;
    loadComments();
}

function deleteComment(id) {
    $.get('/index.php/comments/delete/' + id, function() {
        loadComments();
    });
}

$('#addBtn').click(function() {

    let email = $('#email').val();
    let text = $('#text').val();
    let date = $('#date').val();

    $('#emailError').text('');

    let re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!re.test(email)) {
        $('#emailError').text('Введите корректный email');
        return;
    }

    if (!text.trim()) {
        alert('Введите текст комментария');
        return;
    }

    $.post('/index.php/comments/add', {
        name: email,
        text: $('#text').val(),
        date: $('#date').val()
    }, function() {
        loadComments();
    });
});

$('#sort').change(function() {
    sort = $(this).val();
    loadComments();
});

$('#order').change(function() {
    order = $(this).val();
    loadComments();
});

loadComments();
</script>

</body>
</html>
