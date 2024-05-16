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
  <h1>Список заявок</h1>
    <div id="ticketsList">
        <!-- Здесь будут отображаться заявки -->
    </div>
    <button onclick="sortTickets('open')">Показать свободные заявки</button>
    <button onclick="sortTickets('answered')">Показать заявки с ответом менеджера</button>
    <button onclick="sortTickets('closed')">Показать закрытые заявки</button>

    <script>
const tickets = <?php echo json_encode($tickets); ?>;
//console.log(tickets[0].status)

function displayTickets(ticketsArray) {
    const ticketsListDiv = document.getElementById("ticketsList");
    ticketsListDiv.innerHTML = "";

    ticketsArray.forEach(ticket => {
        const ticketDiv = document.createElement("div");
        ticketDiv.textContent = `${ticket.title} - ${ticket.status}`;
        ticketsListDiv.appendChild(ticketDiv);
    });
}


function sortTickets(status) {
    const sortedTickets = tickets.filter(ticket => ticket.status === status);
    displayTickets(sortedTickets);
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