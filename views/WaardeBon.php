<?php
try {
    $sesionRequired = new \webshop\SessionRequired(2);
    $sesionRequired->validatePage();
?>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="../js/jqReply.js"></script>
        <link href="../CSS/basic.css" rel="stylesheet" type="text/css" media="all">
    </head>
    <a href='index.php?c=WaardeBon&m=loadWaardeBonnenPage'>WaardeBonnen pagina</a>

    <body>
        <div id="messages">
            <div>
                <p>voer een actie uit</p>
            </div>
        </div>
        <?php if (!empty($waardeBon)) {
            foreach ($waardeBon as $waardeBon) {
        ?>
                <form>
                    <input type="number" name="idWaardeBon" style="display: none;" value="<?php echo $waardeBon["idWaardeBon"]; ?>">

                    <label>waardeBon Name</label>
                    <input type="text" name="name" value="<?php echo $waardeBon["name"]; ?>">

                    <label>percentageOff</label>
                    <input type="text" name="percentageOff" value="<?php echo $waardeBon["percentageOff"]; ?>">

                    <label>minOrderValue</label>
                    <input type="text" name="minOrderValue" value="<?php echo $waardeBon["minOrderValue"]; ?>">

                    <label>code</label>
                    <input type="text" name="code" value="<?php echo $waardeBon["code"]; ?>">

                    <label>expires</label>
                    <input type="text" name="expires" value="<?php echo $waardeBon["expires"]; ?>">

                    <button type="button" class="update" name="update">wijzig</button>

                </form>
        <?php
            }
        } else {
            echo "geen waardeBonnen gevonden";
        }
        ?>


        <script>
            const messages = document.getElementById('messages');
            const updateButton = document.querySelector('.update');


            updateButton.addEventListener('click', function handleClick(event) {
                let form = updateButton.form;
                let name = form.elements["name"].value;
                let percentageOff = form.elements["percentageOff"].value;
                let minOrderValue = form.elements["minOrderValue"].value;
                let expires = form.elements["expires"].value;
                let code = form.elements["code"].value;

                let idWaardeBon = form.elements["idWaardeBon"].value;
                sendDataPut(idWaardeBon, name, minOrderValue, expires, percentageOff, code, messages);
            });

            function sendDataPut(idWaardeBon, name, minOrderValue, expires, percentageOff, code, messages) {
                $.ajax({
                    method: "PUT",
                    url: "restWaardeBon.php",
                    data: {
                        idWaardeBon: idWaardeBon,
                        name: name,
                        minOrderValue: minOrderValue,
                        expires: expires,
                        percentageOff: percentageOff,
                        code: code,
                    },
                    success: function(data) {
                        console.log(code);
                        console.log(data);
                        let parsedData = JSON.parse(data);
                        switch (parsedData["succes"]) {
                            case "fout":
                                $(messages).html(createErrorDiv(parsedData["msg"]));

                                break;
                            case "succes":
                                $(messages).html(createSuccesDiv(parsedData["msg"]));

                                break;
                        }
                    },
                    error: function(data) {
                        $(messages).html(createErrorDiv('er is iets mis gegaan'));

                    }
                });
            }
        </script>
    </body>

    </html>
<?php

} catch (Exception $e) {
    echo $e->getMessage();
}

?>