<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <title>Registreer</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../js/jqReply.js"></script>
    <link href="../CSS/basic.css" rel="stylesheet" type="text/css" media="all">
</head>

<body>
    <div id="messages">
        <div>
            <p>voer een actie uit</p>
        </div>
    </div>
    <form>
        <label>email<label>
                <input type="text" name="email">
                <label>password</label>
                <input type="password" name="password">
                <label>confirmPassword</label>
                <input type="password" name="confirmPassword">
                <button type="button" class="register">Registreer</button>
    </form>

    <script>
        const messages = document.getElementById("messages");

        const register = document.querySelector('.register');
        register.addEventListener('click', function handleClick(event) {
            let form = register.form;
            let email = form.elements["email"].value;
            let password = form.elements["password"].value;
            let confirmPassword = form.elements["confirmPassword"].value;

            sendDatePost(email, password, confirmPassword);
        })

        function sendDatePost(email, password, confirmPassword) {
            $.ajax({
                method: "POST",
                url: "../restPages/registerRest.php",
                data: {
                    email: email,
                    password: password,
                    confirmPassword: confirmPassword,
                },
                success: function(data) {
                    let parsedData = JSON.parse(data);
                    console.log(parsedData);
                    switch (parsedData["succes"]) {
                        case "error":
                            $(messages).html(createErrorDiv(parsedData["msg"]));

                            break;
                        case "link":
                            window.location.href = 'shop.php';

                            break;
                    }
                },
                error: function(xhr, status, error) {
                    $(messages).html(createErrorDiv("er is iets mis gegaan"));
                }
            });
        }
    </script>
</body>

</html>