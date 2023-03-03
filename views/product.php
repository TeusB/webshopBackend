<?php
require_once("../vendor/autoload.php");

use controllers\User;
use main\Error;
use main\Session;
use main\Product;

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

<body>
    <div id="messages">
        <div>
            <p>voer een actie uit</p>
        </div>
    </div>
    <?php if (!empty($products)) {
        foreach ($products as $product) {
    ?>
            <form>
                <input type="number" name="idProduct" style="display: none;" value="<?php echo $product["idProduct"]; ?>">

                <label>product Name</label>
                <input type="text" name="name" value="<?php echo $product["name"]; ?>">

                <label>description</label>
                <input type="text" name="descr" value="<?php echo $product["descr"]; ?>">

                <label>category</label>
                <select name="idCategory" required>
                    <?php
                    if (!empty($categories)) {
                        foreach ($categories as $category) {
                    ?>
                            <option <?php if ($category["idCategory"] === $product["idCategory"]) {
                                        echo "selected";
                                    } ?> value="<?php echo $category["idCategory"]; ?>"><?php echo $category["name"]; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>

                <label>price</label>
                <input type="text" name="price" value="<?php echo $product["price"]; ?>">

                <label>stock</label>
                <input type="text" name="stock" value="<?php echo $product["stock"]; ?>">


                <button type="button" class="update" name="update">wijzig</button>

            </form>
    <?php
        }
    } else {
        echo "geen product gevonden";
    }
    ?>


    <script>
        const messages = document.getElementById('messages');
        const updateButton = document.querySelector('.update');


        updateButton.addEventListener('click', function handleClick(event) {
            let form = updateButton.form;
            let name = form.elements["name"].value;
            let descr = form.elements["descr"].value;
            let idCategory = form.elements["idCategory"].value;
            console.log(idCategory);
            let price = form.elements["price"].value;
            let stock = form.elements["stock"].value;
            let idProduct = form.elements["idProduct"].value;
            console.log(idCategory);
            sendDataPut(idProduct, name, descr, idCategory, price, stock, messages);
        });

        function sendDataPut(idProduct, name, descr, idCategory, price, stock, messages) {
            $.ajax({
                method: "PUT",
                url: "restProduct.php",
                data: {
                    name: name,
                    descr: descr,
                    idProduct: idProduct,
                    idCategory: idCategory,
                    price: price,
                    stock: stock
                },
                success: function(data) {

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