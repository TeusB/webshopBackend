<?php
try {
    $sesionRequired = new \webshop\SessionRequired(2);
    $sesionRequired->validatePage();
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


        <form>
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

            <button type="button" id="addProduct">voeg product toe</button>
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
                            <td><?php echo $product["name"]; ?></td>
                            <td><?php echo $product["category"]; ?></td>
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

        <a href="index.php?c=user&m=logOut">logout</a>

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
                    url: "restProduct.php",
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


            function sendDataGet(idProduct) {
                $.ajax({
                    method: "GET",
                    url: "restProduct.php",
                    data: {
                        idProduct: idProduct,
                    },
                    success: function(data) {
                        let parsedData = JSON.parse(data);
                        switch (parsedData["succes"]) {
                            case "fout":
                                $(messages).html(createErrorDiv(parsedData["msg"]));

                                break;
                            case "succes":
                                window.location.href = parsedData["data"];

                                break;
                        }
                    },
                    error: function(xhr, status, error) {
                        $(messages).html(createErrorDiv("er is iets mis gegaan"));
                    }
                });
            }
        </script>


        <script>
            const addProductButton = document.getElementById('addProduct');
            addProductButton.addEventListener('click', function handleClick(event) {
                let form = addProductButton.form;
                let name = form.elements["name"].value;
                let descr = form.elements["descr"].value;
                let categoryFix = form.elements["idCategory"];
                let idCategory = categoryFix.value;
                let categoryName = categoryFix.options[categoryFix.selectedIndex].text;
                let price = form.elements["price"].value;
                let stock = form.elements["stock"].value;
                sendDataPost(name, descr, idCategory, categoryName, price, stock, products, messages);
            });

            function sendDataPost(name, descr, idCategory, categoryName, price, stock, products, messages) {
                $.ajax({
                    method: "POST",
                    url: "restProduct.php",
                    data: {
                        name: name,
                        descr: descr,
                        idCategory: idCategory,
                        price: price,
                        stock: stock,
                    },
                    success: function(data) {
                        let parsedData = JSON.parse(data);
                        switch (parsedData["succes"]) {
                            case "fout":
                                $(messages).html(createErrorDiv(parsedData["msg"]));

                                break;
                            case "succes":
                                $(messages).html(createSuccesDiv(parsedData["msg"]));
                                let tr = document.createElement("tr");
                                let tds = [parsedData["data"], name, categoryName, price, stock];

                                tds.forEach(value => {
                                    let td = document.createElement("td");
                                    td.innerHTML = value;
                                    tr.append(td);
                                });


                                let buttonTD = document.createElement("td");

                                let bekijkButton = document.createElement("button");
                                bekijkButton.setAttribute("type", "button");
                                bekijkButton.innerHTML = "bekijk / wijzig";
                                bekijkButton.classList.add("bekijk");
                                addBekijkEvent(bekijkButton);
                                buttonTD.append(bekijkButton);


                                let deActivateButton = document.createElement("button");
                                deActivateButton.setAttribute("type", "button");
                                deActivateButton.innerHTML = "de activeer";
                                deActivateButton.classList.add("deActivate");
                                addDeleteEvent(deActivateButton);
                                buttonTD.append(deActivateButton);

                                tr.append(buttonTD);
                                products.prepend(tr);

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