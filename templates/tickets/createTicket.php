<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>Создание заявки</title>
        <style>
                
        </style>
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
<?php if (!empty($error)): ?>
        <div style="background-color: red;padding: 5px;margin: centre"><?= $error ?></div>
        <?php endif; ?>
        <header class="mb-auto">
    <div>
      <nav class="nav nav-masthead justify-content-center float-md-end">
      <h3 class="float-md-start mb-2"><a class="nav-link" href="/dashboard">На главную</a></h3>
      </nav>
    </div>
  </header>
<div class="cover-container d-flex w-80 h-100 p-3 mx-auto flex-column">
  <main class="px-3">
  <form method="post" action="/tickets/insert" enctype="multipart/form-data">
        <input type="text" name="title" id="title" class="form-control" placeholder="Введите имя заявки" required="" autofocus="">
        <textarea name="text" class="form-control" rows="10" cols="100" placeholder="Введите текст заявки" required=""></textarea>
        <input class="btn btn-lg btn-primary btn-block" type="file" multiple name="image[]" width=20%;><br><br><br>
        <button class="btn btn-lg btn-primary btn-block" type="submit" width=20%;>Отправить</button>
    </form>
  </main>

  <footer class="mt-auto text-white-50">
    <p>Cover template for <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
  </footer>
</div>
</body>
</html>
