<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>Регистрация</title>
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
  <h1><?= $ticket->getTitle() ?></h1>
  <h2><?= $ticket->getText() ?></h2>
  <br>
<p><img src="<?php echo $path ?>" alt="Image"></p>
    <h2><a href="/tickets/<?= $ticket->getId()?>/delete"><?= 'Удалить заявку.' ?></a></h2>
    <h2><a href="/tickets/<?= $ticket->getId()?>/edit"><?= 'Редактировать.' ?></a></h2>
    <br>
    <?php if (($user->getRole()=='manager')&&(empty($ticket->getManagerId()))): ?>
        <h2><a href="/tickets/<?= $ticket->getId()?>/setManager"><?= 'Принять заявку на рассмотрение.' ?></a></h2>
    <?php  endif; ?>
    <br>
</div>
    <h2>Чат</h2>
    
    <div id="chatMessages" class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <!-- Сюда будут добавляться сообщения -->
    </div>
    <form id="chatForm" class="cover-container d-flex w-30 h-100 p-3 mx-auto flex-column" style="width : 35%;">
        <input type="text" id="messageInput" name="message" placeholder="Введите ваше сообщение" required>
        <button type="submit">Отправить</button>
    </form>
  </main>

  <footer class="mt-auto text-white-50">
    <p>Cover template for <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
  </footer>
</div>
<!-- ВЫНЕСТИ СКРИПТ В ОТДЕЛЬНЫЙ ФАЙЛ! -->
<script>
//POST-запрос на сервер
function makeRequest(url, data) {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    });
}

// Обработчик события отправки формы
document.getElementById('chatForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const message = document.getElementById('messageInput').value;
    const ticketId = <?= $ticket->getId()?>;
    const url = '/tickets/addToChat/' + ticketId;
    const data = { message: message };

    makeRequest(url, data)
    .then(response => {
        updateChatMessages();
    }) 
    .catch(error => {
        console.error('There was a problem with your fetch operation:', error);
    });
});

// Обновление списка сообщений
function updateChatMessages() {
    const ticketId = <?= $ticket->getId()?>;
    const url = '/tickets/getChatMessages/' + ticketId;

    fetch(url)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(messages => {
        displayChatMessages(messages);
    })
    .catch(error => {
        console.error('There was a problem with your fetch operation:', error);
    });
}

// Отображение сообщений чата
function displayChatMessages(messages) {
    const chatMessagesDiv = document.getElementById('chatMessages');
    chatMessagesDiv.innerHTML = '';
    messages.forEach(message => {
        const messageElement = document.createElement('p');
        messageElement.textContent = message.text;
        chatMessagesDiv.appendChild(messageElement);
    });
}

window.onload = updateChatMessages;
    </script>
</body>
</html>