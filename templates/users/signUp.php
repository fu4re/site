<?php include __DIR__ . '/../header.php'; ?>
    <div style="text-align: center;">
        <h1>Регистрация</h1>
        <?php if (!empty($error)): ?>
            <div style="background-color: red;padding: 5px;margin: 15px"><?= $error ?></div>
        <?php endif; ?>
        <form action="/users/register" method="post">
            <label>Nickname <input type="text" name="nickname" value="<?= $_POST['nickname'] ?? '' ?>"></label>
            <br><br>
            <label>First Name <input type="text" name="first_name" value="<?= $_POST['first_name'] ?? '' ?>"></label>
            <br><br>
            <label>Second Name <input type="text" name="second_name" value="<?= $_POST['second_name'] ?? '' ?>"></label>
            <br><br>
            <br><br>
            <label>Email <input type="text" name="email" value="<?= $_POST['email'] ?? '' ?>"></label>
            <br><br>
            <label>Пароль <input type="password" name="password0" value=""></label>
            <label>Потвердите <input type="password" name="password1" value=""></label>
            <br><br>
            <input type="submit" value="Зарегистрироваться">
        </form>
    </div>
<?php include __DIR__ . '/../footer.php'; ?>