<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список заявок</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body class="d-flex h-100 text-center text-white bg-dark">

<div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <header class="mb-auto">
    <div>
      <h3 class="float-md-start mb-0">Cover</h3>
      <nav class="nav nav-masthead justify-content-center float-md-end">
      <a class="nav-link" href="/dashboard">На главную</a>
      </nav>
    </div>
  </header>
  <main class="px-3">
  <?php foreach ($tickets as $ticket): ?>
    <h2><a href="/tickets/<?= $ticket->getId() ?>"><?= $ticket->getTitle() ?></a></h2>
    <p><?= $ticket->getText() ?></p>
    <hr>
<?php endforeach; ?>
<?php if(empty($tickets)): ?>
          <h4>Заявок пока нет.Если хотите оставить новую заявку нажмите<a class="nav-link" href="/makeMessage">тут.</a></h4>
          <?php endif; ?>



  </main>

  <footer class="mt-auto text-white-50">
    <p>Cover template for <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
  </footer>
</div>
</body>
</html>
