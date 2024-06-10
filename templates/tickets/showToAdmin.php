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
      <a class="nav-link" href="users/account/<?= $user->getId() ?>">Мой профиль.</a>
      </nav>
    </div>
  </header>
  <main class="px-3">
  <h1>Список заявок</h1>
    <div id="ticketsList">
        <!-- Здесь будут отображаться заявки -->
    </div>
    <button onclick="sortTickets('open')">Показать свободные заявки</button>
    <button onclick="sortTicketsByManager()">Показать рассматриваемые мной заявки</button>
    <button onclick="sortTickets('closed')">Показать закрытые заявки</button>
    <button onclick="sortTicketsByDate()">Сначала новые</button>

    <script>

const tickets = <?php echo json_encode($tickets); ?>;

// Функция для отображения отсортированных заявок
function displayTickets(ticketsArray) {
    const ticketsListDiv = document.getElementById("ticketsList");
    ticketsListDiv.innerHTML = ""; // Очищаем содержимое списка заявок

    ticketsArray.forEach(ticket => {
        const ticketDiv = document.createElement("div");
        const ticketLink = document.createElement("a");
        ticketLink.href = `/tickets/${ticket.id}`;
        ticketLink.textContent = ticket.title;
        ticketDiv.appendChild(ticketLink);
        ticketsListDiv.appendChild(ticketDiv);
    });
}

// Функция для сортировки заявок по статусу
function sortTickets(status) {
    const sortedTickets = tickets.filter(ticket => ticket.status === status);
    displayTickets(sortedTickets);
}

function sortTicketsByDate() {
    const openTickets = tickets.filter(ticket => ticket.status === 'open');
    const sortedByDate = openTickets.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    displayTickets(sortedByDate);
}

function sortTicketsByManager() {
    const answeredTickets = tickets.filter(ticket => ticket.manager_id == <?php echo $user->getId() ?> );
    displayTickets(answeredTickets);
}

displayTickets(tickets);


</script>




  </main>

  <footer class="mt-auto text-white-50">
    <p>Cover template for <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
  </footer>
</div>
</body>
</html>