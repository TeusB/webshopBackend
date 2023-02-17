<!DOCTYPE html>

<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <title>Inloggen</title>
</head>

<body>
    <?php if (!empty($users)) {
        foreach ($users as $user) {
    ?>
            <form action="index.php?c=user&m=userHandlr&idUser=<?php echo $user["idUser"]; ?>" method="POST">
                <label>First Name<input type="text" name="firstName" maxlength="40" value="<?php echo $user["firstName"]; ?>"></label>
                <label>Last Name<input type="text" name="lastName" maxlength="40" value="<?php echo $user["lastName"]; ?>"></label>
                <label>Email<input type="text" name="email" maxlength="32" value="<?php echo $user["email"]; ?>"></label>
                <input type="submit" name="adjust" value="wijzig">
                <input type="submit" name="remove" value="verwijder">
            </form>
    <?php
        }
    }

    ?>
</body>

</html>