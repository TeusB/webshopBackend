<?php

?>
<head>
    <meta charset="UTF-8"/>
    <title>Shop</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../js/jqReply.js"></script>
    <link href="../CSS/basic.css" rel="stylesheet" type="text/css" media="all">
</head>
<h1>Shop</h1>
<button onclick="sendPost(true,2)" style="width: 200px; height: 100px" type="submit">+1</button>
<button onclick="sendPost(false,2)" style="width: 200px; height: 100px" type="submit">-1</button>
<button onclick="getCart()">cart ophalen</button>
<script>
    function sendPost(plus, idproduct) {
        $.ajax({
            method: "POST",
            url: "../restPages/shoppingCartItemRest.php",
            data: {
                idProduct: idproduct,
                plusMinus: plus
            },
            success: function (data) {
                let parsedData = JSON.parse(data);
                console.log(parsedData);
                switch (parsedData["succes"]) {
                    case "error":
                        $(messages).html(createErrorDiv(parsedData["msg"]));

                        break;
                    case "succes":
                        console.log('jaaaa');
                        break;
                }
            },
            error: function (xhr, status, error) {
                $(messages).html(createErrorDiv("er is iets mis gegaan"));
            }
        });
    }

    function getCart() {
        $.ajax({
            method: "GET",
            url: "../restPages/shoppingCartItemRest.php",
            success: function (data) {
                let parsedData = JSON.parse(data);
                console.log(parsedData);
                switch (parsedData["succes"]) {
                    case "error":
                        $(messages).html(createErrorDiv(parsedData["msg"]));
                        break;
                    case "succes":
                        console.log('jaaaa');
                        break;
                }
            },
            error: function (xhr, status, error) {
                $(messages).html(createErrorDiv("er is iets mis gegaan"));
            }
        });
    }
</script>