<?php
	include "config/base_url.php";
    include "config/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Редактирование профиля</title>
    <?php include "views/head.php"; ?>
</head>
<body>

<?php include "views/header.php"; ?>
	<section class="container page">
		<div class="auth-form">
            <?php
                $id = $_SESSION["user_id"];
                $userquery = mysqli_query($con, 
                "SELECT * FROM users WHERE id=$id");
                $user = mysqli_fetch_assoc($userquery);
            ?>
            <h1>Редактирование профиля</h1>
			<form class="form" action="<?=$BASE_URL?>/api/user/update.php" method="POST">
                <fieldset class="fieldset">
                    <input class="input" type="text" name="email" value="<?=$user["email"]?>" placeholder="Введите email">
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" value="<?=$user["full_name"]?>" type="text" name="full_name" placeholder="Полное имя">
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" type="text" value="<?=$user["nickname"]?>" name="nickname" placeholder="Nickname">
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" type="password"  name="password" placeholder="Введите текущий пароль">
                </fieldset>
                <fieldset class="fieldset">
                    <input class="input" type="password"  name="new_password" placeholder="Введите новый пароль">
                </fieldset>

                <fieldset class="fieldset">
                    <textarea name="about" id="" cols="51" rows="10" placeholder="Enter info about yourself"></textarea>
                </fieldset>

                <fieldset class="fieldset">
                    <button class="button" type="submit">Сохранить</button>
                </fieldset>
			</form>
		</div>
	</section>
</body>
</html>