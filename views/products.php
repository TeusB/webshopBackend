<?php
require_once("../vendor/autoload.php");

use controllers\Category;
use controllers\User;
use main\Error;
use main\Session;
use controllers\Product;

$product = new Product();
$session = new Session();
$category = new Category();
if (!$session->checkSessionExist()) {
    echo "er is geen sessie";
    return;
}
if (!$session->checkSessionLevel(2)) {
    echo "te laag level voor deze pagina";
    return;
}
$categories = $category->getAllCategories(["idCategory", "name"]);
$products = $product->getActiveProductsJoincategory();
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <title>products</title>
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

    <form id="data" enctype="multipart/form-data" method="post">
        <label>naam Product</label>
        <input type="text" name="name" required>

        <label>description</label>
        <input type="text" name="descr" required>

        <label>category</label>
        <select name="idCategory" required>
            <?php
            if (!empty($categories)) {
                foreach ($categories as $category) {
            ?>
                    <option value="<?php echo $category["idCategory"]; ?>"><?php echo $category["name"]; ?></option>
            <?php
                }
            }
            ?>
        </select>

        <label>prijs</label>
        <input type="number" name="price" required>

        <label>stock</label>
        <input type="number" name="stock" required>
        <label for="imageURL">foto</label>
        <input name="imageURL" type="file">

        <input type="submit">voeg product toe</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>idProduct</th>
                <th>naam Product</th>
                <th>categorie</th>
                <th>prijs</th>
                <th>stock</th>
                <th>acties</th>
            </tr>
        </thead>

        <tbody id="products">
            <?php if (!empty($products)) {
                foreach ($products as $product) {
            ?>
                    <tr>
                        <td><?php echo $product["idProduct"]; ?></td>
                        <td><?php echo $product["productName"]; ?></td>
                        <td><?php echo $product["categoryName"]; ?></td>
                        <td><?php echo $product["price"]; ?></td>
                        <td><?php echo $product["stock"]; ?></td>
                        <td>
                            <button type="button" class="bekijk">bekijk / wijzig</button><button type="button" class="deActivate">de activeer</button>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>

    <!-- <a href="index.php?c=user&m=logOut">logout</a> -->

    <script>
        const messages = document.getElementById("messages");
        const products = document.getElementById("products");


        function addDeleteEvent(deleteButton) {
            deleteButton.addEventListener('click', function handleClick(event) {
                let currentRow = $(this).closest("tr");
                let idProduct = currentRow.find("td:eq(0)").text();
                sendDataDelete(idProduct, currentRow, messages);
            })
        }

        const deActivateButtons = document.querySelectorAll('.deActivate');
        deActivateButtons.forEach(deActivateButton => {
            addDeleteEvent(deActivateButton);
        });

        function sendDataDelete(idProduct, currentRow, messages) {
            $.ajax({
                method: "SOFTDELETE",
                url: "../restPages/restProduct.php",
                data: {
                    idProduct: idProduct,
                },
                success: function(data) {
                    console.log(data);
                    let parsedData = JSON.parse(data);
                    switch (parsedData["succes"]) {
                        case "fout":
                            $(messages).html(createErrorDiv(parsedData["msg"]));

                            break;
                        case "succes":
                            $(messages).html(createSuccesDiv(parsedData["msg"]));
                            currentRow.remove();
                            break;
                    }
                },
                error: function(data) {
                    $(messages).html(createErrorDiv('er is iets mis gegaan'));

                }
            });
        }
    </script>

    <script>
        function addBekijkEvent(bekijkButton) {
            bekijkButton.addEventListener('click', function handleClick(event) {
                let currentRow = $(this).closest("tr");
                let idProduct = currentRow.find("td:eq(0)").text();
                sendDataGet(idProduct);
            })
        }

        const bekijkButtons = document.querySelectorAll('.bekijk');
        bekijkButtons.forEach(bekijkButton => {
            addBekijkEvent(bekijkButton);
        });
        $.ajax({
            method: "GETALLJSON",
            url: "../restPages/restProduct.php",
            success: function(data) {

                const jsonObject = JSON.parse(data);

                console.log(jsonObject);
                const dataArray = JSON.parse(jsonObject.data);

                console.log(dataArray);

            }
        })

        function sendDataGet(idProduct) {
            $.ajax({
                method: "GET",
                url: "../restPages/restProduct.php",
                data: {
                    idProduct: idProduct,
                },
                success: function(data) {
                    console.log(data);
                    let parsedData = JSON.parse(data);
                    switch (parsedData["succes"]) {
                        case "error":
                            $(messages).html(createErrorDiv(parsedData["msg"]));
                            break;
                        case "succes":
                            window.location.href = parsedData["link"];
                            break;
                    }
                },
                error: function(xhr, status, error) {
                    $(messages).html(createErrorDiv("er is iets mis gegaan"));
                }
            });
        }
    </script>


    <!-- <script>
        $("form#data").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                method: "POST",
                url: "../restPages/restProduct.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    console.log(data);
                },
                error: function(data) {
                    $(messages).html(createErrorDiv('er is iets mis gegaan'));
                }
            });
        })
    </script> -->

</body>

</html>