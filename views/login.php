<!DOCTYPE html>

<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <title>Inloggen</title>
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
        <label>email</label>
        <input type="text" name="email">
        <label>password</label>
        <input type="password" name="password">
        <button type="button" class="login">Login</button>
    </form>

    <script>
        const messages = document.getElementById("messages");

        const login = document.querySelector('.login');
        login.addEventListener('click', function handleClick(event) {
            let form = login.form;
            let email = form.elements["email"].value;
            let password = form.elements["password"].value;
            sendDatePost(email, password);
        })

        function sendDatePost(email, password) {
            $.ajax({
                method: "POST",
                url: "../restPages/restLogin.php",
                data: {
                    email: email,
                    password: password
                },
                success: function(data) {
                    let parsedData = JSON.parse(data);
                    console.log(parsedData);
                    switch (parsedData["succes"]) {
                        case "error":
                            $(messages).html(createErrorDiv(parsedData["msg"]));

                            break;
                        case "succes":
                            console.log(parsedData["msg"]);
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