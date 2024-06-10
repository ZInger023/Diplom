<h1>Редактирование профиля</h1>
<form method="post" action="/users/editAccount" enctype="multipart/form-data">
    <input type="text" name="username" value="<?php echo htmlspecialchars($user->getName()); ?>" required>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
    <input type="file" name="avatar">
    <button type="submit">Сохранить</button>
</form>