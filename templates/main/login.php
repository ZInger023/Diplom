<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>Вход</title>
        <style>
                .form-control {
                     width: 20%;
                     margin:auto;
                    }
                     #submit {
                                 width: 20%;
                                 margin:auto;
                                }
                .alert-danger {
                    width: 20%;
                    margin:auto;
                    color: red;
                }
        </style>
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <main class="px-3">
  <?php if (!empty($error)): ?>
    <div class="alert-danger">
            <?php foreach($errors->all() as $error) : ?>
                    <li><p style="color: red">Hello</p></li>
                    <?php endforeach ?>
        </div>
        <?php endif; ?>
    <form method="post" action="/users/login">
            <input type="text" name="nickname" id="nickname" class="form-control" placeholder="Enter nickname" value="<?= $_POST['nickname'] ?? '' ?>" required="" autofocus="">
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" value="<?= $_POST['password'] ?? '' ?>" required="">
            <button class="btn btn-lg btn-primary btn-block" id="submit" type="submit">Войти!</button>
        </form>
  </main>

  <footer class="mt-auto text-white-50">
    <p>Cover template for <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
  </footer>
</div>
</body>
</html>
