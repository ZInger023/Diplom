<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title><?= $user->getName() ?></title>
        <style>
                .form-control {
                     width: 20%;
                     margin:auto;
                    }
                     #submit {
                                 width: 20%;
                                 margin:auto;
                                }
        </style>
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
<?php if (!empty($error)): ?>
        <div style="background-color: red;padding: 5px;margin: centre"><?= $error ?></div>
        <?php endif; ?>
<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <main class="px-3">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <main class="px-3">
  <h1>Профиль пользователя</h1>
<p>Имя пользователя: <?php echo htmlspecialchars($user->getName()); ?></p>
<p>Статус: <?php echo htmlspecialchars($user->isOnline()); ?></p>
  <br>
  <?php if (!empty($images)): ?>
    <?php foreach ($images as $image): ?>
    <p><img src="../<?php echo $image->getPath() ?>" alt="Image"></p>
    <?php endforeach; ?>
        <?php endif; ?>
    <br>
    <h2>Статистика пользователя:</h2>
        <p>Дата регистрации : <?php echo htmlspecialchars($user->getCreatedAt()); ?></p>
        <p>Количество заявок : <?php echo $numberOfTickets; ?></p>
        <p>Число сообщений : <?php echo $numberOfChats; ?></p>
<?php if ($isOwner): ?>
<a href="/users/editAccount">Редактировать профиль</a>
<?php endif; ?>
</div>
  </main>
  </div>
</body>
</html>