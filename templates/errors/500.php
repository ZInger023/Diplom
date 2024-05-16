<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ошибка</title>
</head>
<body>
    <?php
    if (isset($_GET['error_message'])) {
        $errorMessage = urldecode($_GET['error_message']);
        echo "<p>Произошла ошибка: " . htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') . "</p>";
    } else {
        echo "<p>Неизвестная ошибка.</p>";
    }
    ?>
</body>
</html>