// Database ingelogde deel shopping cart //

function addDeleteToCart(productID, add = true, type) {
    // ADD IS BOOL, ALS ADD FALSE IS GAAT HET UIT VAN DELETE PRODUCT
    // type = 'shoppingcart': laad displayCart(shoppingcart) || type = 'checkoutcart' : laad displayCart(checkoutcart)
    $.get('views/checkSession.php', function (data) { // checkt via checkSession.php of de gebruiker is ingelogd.
        if (data) {
            let idUser = data;
            if (add) { // bij toevoegen
                $.ajax({
                    type: 'POST',
                    url: '../index.php?c=cart&m=cartEntry',
                    data: {productID: productID, idUser: idUser},
                    success: function (data) {
                        if (data) {
                            alert('Product is toegevoegd');
                            if (type === 'shoppingcart') {
                                displayCart('shoppingcart');
                            }
                            if (type === 'checkoutcart') {
                                displayCart('checkoutcart');
                            }
                        }
                    },
                    error: function (data) {
                        console.log('error');
                        console.log(data);
                    }
                });
            } else { // bij verwijderen
                $.ajax({
                    method: 'POST',
                    url: '../index.php?c=cart&m=removeFromCart&productID',
                    data: {productID: productID, idUser: idUser},
                    dataType: "JSON",
                    success: function () {
                        if (type === 'shoppingcart') {
                            displayCart('shoppingcart');
                        }
                        if (type === 'checkoutcart') {
                            displayCart('checkoutcart')
                        }
                    },
                    error: function (response) {
                        console.log('error');
                        console.log(response)
                    }
                });

            }
        } else { // als gebruiker niet is ingelogd
            prodDataToLS(productID);
        }
    });
}

function shoppingCartPlusMinus(productID, add, type) {
    // als add true is dan gaat het uit van +, false gaat uit van -
    // type = 'shoppingcart': laad displayCart(shoppingcart) || type = 'checkoutcart' : laad displayCart(checkoutcart)
    $.get('views/checkSession.php', function (data) { // checkt via checkSession.php of de gebruiker is ingelogd.
        if (data) {
            $.ajax({
                method: 'POST',
                url: '../index.php?c=cart&m=PlusMinShoppingCart',
                data: {productID: productID, idUser: data, add: add},
                dataType: "JSON",
                success: function (response) {
                    if (type === 'shoppingcart') {
                        displayCart('shoppingcart');
                    }
                    if (type === 'checkoutcart') {
                        displayCart('checkoutcart');
                    }
                },
                error: function (response) {
                    console.log('error');
                    console.log(response)
                }
            });
        }
    });
}

// localstorage niet ingelogd gedeelte shoppingcart //

function deleteFromLS(productID, type) { // type = 'shoppingcart': laad displayCart(shoppingcart) || type = 'checkoutcart' : laad displayCart(checkoutcart)
    if (localStorage.getItem(productID)) {
        localStorage.removeItem(productID);
        if (type === 'shoppingcart') {
            displayCart('shoppingcart');
        }
        if (type === 'checkoutcart') {
            displayCart('checkoutcart');
        }
    }
}

function LSPlusMinus(productID, add, type) { // ADD true = +1 || add false = -1     // type = 'shoppingcart': laad displayCart(shoppingcart) || type = 'checkoutcart' : laad displayCart(checkoutcart)

    let item = localStorage.getItem(productID);
    if (item) {
        let parsedObj = JSON.parse(item);
        if (add) {
            parsedObj[0]['amount'] += 1;
        } else {
            if (parsedObj[0]['amount'] != 1) {
                parsedObj[0]['amount'] -= 1;
            } else {
                localStorage.removeItem(productID);
                if (type === 'shoppingcart') {
                    displayCart('shoppingcart');
                }
                if (type === 'checkoutcart') {
                    displayCart('checkoutcart')
                }
                return;
            }
        }
        localStorage.setItem(productID, JSON.stringify(parsedObj));
        if (type === 'shoppingcart') {
            displayCart('shoppingcart');
        }
        if (type === 'checkoutcart') {
            displayCart('checkoutcart')
        }
    }
}

function prodDataToLS(productID) {
    let productName = $("h5." + productID).html();
    let productDescr = $("p." + productID).html();
    let productPrice = $("span." + productID).html();
    let prodObject = [{
        prodID: productID,
        prodName: productName,
        prodDescr: productDescr,
        prodPrice: productPrice,
        amount: 1
    }];
    if (localStorage.length === 0) {
        localStorage.setItem(prodObject[0]['prodID'], JSON.stringify(prodObject));
        alert('Product toegevoegd');
    } else {
        let getItem = localStorage.getItem(productID)
        if (getItem === null) {
            localStorage.setItem(prodObject[0]['prodID'], JSON.stringify(prodObject));
            alert('Product toegevoegd');
        } else {
            let parsedObj = JSON.parse(getItem);
            parsedObj[0]['amount'] += 1;
            localStorage.setItem(parsedObj[0]['prodID'], JSON.stringify(parsedObj));
            alert('Product toegevoegd');
        }
    }
    displayCart('shoppingcart');
}

function displayCart(type) { // als type = schoppingcart : schrijft data naar shoppingcart op de webshop pagina || type = checkoutcart : schrijft data naar de checkout pagina
    $.get('views/checkSession.php', function (data) { // checkt via checkSession.php of de gebruiker is ingelogd.
        if (data) {
            let idUser = data;
            let arrayFromDB = [];
            let html = '';
            $.ajax({
                url: '../index.php?c=cart&m=retrieveShoppingCart&idUser=' + idUser,
                method: 'GET',
                dataType: "JSON",
                async: false,
                success: function (response) {
                    if (response['data'] !== undefined) {
                        arrayFromDB = JSON.parse(JSON.stringify(response['data']));
                    } else {
                        arrayFromDB = [];
                    }
                },
                error: function (response) {
                    console.log('failed');
                    console.log(response)
                },
            }).promise().done(function (response) {  // als ajax call klaar is

                // type defineert waar de data natoe wordt geschreven
                if (type === 'shoppingcart') {
                    const shoppingCartItemWrapper = document.getElementById('shoppingCartItemWrapper');
                    const shoppingCartTop = document.getElementById('shoppingCartTop');

                    shoppingCartTop.innerHTML = '<div id="shoppingCartInner" class="btn btn-outline-dark"><i class="bi-cart-fill me-1"></i>Cart <span class="badge bg-dark text-white ms-1 rounded-pill">' + arrayFromDB.length + '</span></div>'
                    let html = '';
                    carttype = 'shoppingcart';
                    for (let i = 0; i < arrayFromDB.length; i++) {
                        html +=
                            '<div class="shoppingCartItem"><div class="shoppingCartSpan"><span><b>' + arrayFromDB[i]['name'] + '</b></span></div> <div class="shoppingCartCounter"><span><b>Aantal:</b>' + arrayFromDB[i]['amount'] + '</span> </div> <div class="shoppingCartDescr"> <span>' + arrayFromDB[i]['descr'] + '</span></div> <div class="shoppingCartAction"> <div class="shoppingCartPlus"><button onclick="shoppingCartPlusMinus(' + arrayFromDB[i]['idProduct'] + ',true,carttype)">+</button> </div> <div class="shoppingCartMin"> <button onclick="shoppingCartPlusMinus(' + arrayFromDB[i]['idProduct'] + ',false, carttype)" >-</button> </div> <div class="shoppingCartDelete"> <button onclick="addDeleteToCart(' + arrayFromDB[i]['idProduct'] + ',false,carttype)">X</button> </div> </div> <div style="float: right; position: relative; bottom: 5.5vh; left: 30px; font-size: 90%" class="shoppingCartPrices"> <div class="shoppingCartPS"><b>$p.s: </b>' + arrayFromDB[i]['price'] + '</div></div> </div>'
                    }
                    shoppingCartItemWrapper.innerHTML = html;
                }


                if (type === 'checkoutcart') {
                    let checkoutCartItemWrapper = document.getElementById('CheckoutItemWrapper');
                    let totalOrderPriceWrapper = document.getElementById('totalprice');
                    let totalOrderPrice = 0;
                    carttype = 'checkoutcart';
                    for (let i = 0; i < arrayFromDB.length; i++) {
                        totalOrderPrice = totalOrderPrice + arrayFromDB[i]['totalPrice'];
                        html += '<div class="card rounded-3 mb-4"><div class="card-body p-4"><div class="row d-flex justify-content-between align-items-center"><div class="col-md-2 col-lg-2 col-xl-2"> </div> <div class="col-md-3 col-lg-3 col-xl-3"> <p class="lead fw-normal mb-2">' + arrayFromDB[i]['name'] + '</p> <p><span class="text-muted">Prijs P.S: </span>' + arrayFromDB[i]['price'] + '</span></p> </div> <div class="col-md-3 col-lg-3 col-xl-2 d-flex"> <button class="btn btn-link px-2"<i class="fas fa-minus"></i></button><button onclick="shoppingCartPlusMinus(' + arrayFromDB[i]['idProduct'] + ',true, carttype)" class="checkoutCartPlus">+</button><input id="form1" min="0" name="quantity" disabled value="' + arrayFromDB[i]['amount'] + '" type="number" class="form-control form-control-sm"/><button onclick="shoppingCartPlusMinus(' + arrayFromDB[i]['idProduct'] + ',false, carttype)" class="checkoutCartMin">-</button><button class="btn btn-link px-2"<i class="fas fa-plus"></i></button></div><div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1"> <h5 class="mb-0">$' + arrayFromDB[i]['totalPrice'] + '</h5> </div> <div class="col-md-1 col-lg-1 col-xl-1 text-end"> <a href="#!" class="text-danger"><i class="fas fa-trash fa-lg"></i></a> </div> </div> </div> </div>'
                    }
                    totalOrderPriceWrapper.innerHTML = '<div class="card" style="position: relative; bottom: 2.3vh; height: 50px"><h4 id="totaalOrderPrijsSpan" style="margin-top: 1%;margin-left: 1%">Totaal prijs: $' + totalOrderPrice.toFixed(2) + '</h4><h4 id="totaalOrderPrijsSpanNieuw" style="margin-top: 1%;margin-left: 1%; display: none"></h4></div>'
                    checkoutCartItemWrapper.innerHTML = html;
                }

            });
        } else { // gebruiker niet ingelogd
            let html = '';
            let arr = [];
            let parsedArr = JSON.parse(JSON.stringify(localStorage));
            const numberArr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20'];

            if (parsedArr.length !== 0) {
                for (const key in parsedArr) {
                    if (numberArr.includes(key)) {
                        arr.push(JSON.parse(parsedArr[key]));
                    }
                }

                // type defineert waar de data natoe wordt geschreven
                if (type === 'shoppingcart') {
                    const shoppingCartItemWrapper = document.getElementById('shoppingCartItemWrapper');
                    const shoppingCartTop = document.getElementById('shoppingCartTop');
                    carttype = 'shoppingcart';
                    if (localStorage.length !== 0) {
                        for (let i = 0; i < arr.length; i++) {
                            html += '<div class="shoppingCartItem"><div class="shoppingCartSpan"><span><b>' + arr[i][0]['prodName'] + '</b></span></div> <div class="shoppingCartCounter"><span><b>Aantal:</b>' + arr[i][0]['amount'] + '</span> </div> <div class="shoppingCartDescr"> <span>' + arr[i][0]['prodDescr'] + '</span></div> <div class="shoppingCartAction"> <div class="shoppingCartPlus"><button onclick="LSPlusMinus(' + arr[i][0]['prodID'] + ',true,carttype)">+</button> </div> <div class="shoppingCartMin"> <button onclick="LSPlusMinus(' + arr[i][0]['prodID'] + ',false,carttype)">-</button> </div> <div class="shoppingCartDelete"> <button onclick="deleteFromLS(' + arr[i][0]['prodID'] + ',carttype)">X</button> </div> </div> <div style="float: right; position: relative; bottom: 5.5vh; left: 30px; font-size: 90%" class="shoppingCartPrices"> <div class="shoppingCartPS"><b>$p.s: </b>' + arr[i][0]['prodPrice'] + '</div></div> </div>'
                        }
                        shoppingCartTop.innerHTML = '<div id="shoppingCartInner" class="btn btn-outline-dark"><i class="bi-cart-fill me-1"></i>Cart <span class="badge bg-dark text-white ms-1 rounded-pill">' + arr.length + '</span></div>'
                        shoppingCartItemWrapper.innerHTML = html;
                    } else {
                        shoppingCartTop.innerHTML = '<div id="shoppingCartInner" class="btn btn-outline-dark"><i class="bi-cart-fill me-1"></i>Cart <span class="badge bg-dark text-white ms-1 rounded-pill">0</span></div>'
                        shoppingCartItemWrapper.innerHTML = '';
                    }
                }

                if (type === 'checkoutcart') {
                    const checkoutCartItemWrapper = document.getElementById('CheckoutItemWrapper');
                    const totalOrderPriceWrapper = document.getElementById('totalprice');
                    carttype = 'checkoutcart';
                    let totalOrderPrice = 0;
                    for (let i = 0; i < arr.length; i++) {
                        let totalProdPrice = arr[i][0]['amount'] * arr[i][0]['prodPrice'].substring(1);
                        totalOrderPrice = totalOrderPrice + totalProdPrice;
                        html += '<div class="card rounded-3 mb-4"><div class="card-body p-4"><div class="row d-flex justify-content-between align-items-center"><div class="col-md-2 col-lg-2 col-xl-2"> </div><div class="col-md-3 col-lg-3 col-xl-3"> <p class="lead fw-normal mb-2">' + arr[i][0]['prodName'] + '</p> <p><span class="text-muted">Prijs P.S: </span>' + arr[i][0]['prodPrice'] + '</span></p> </div> <div class="col-md-3 col-lg-3 col-xl-2 d-flex"> <button class="btn btn-link px-2"<i class="fas fa-minus"></i></button><button onclick="LSPlusMinus(' + arr[i][0]['prodID'] + ',true,carttype)" class="checkoutCartPlus">+</button><input id="form1" min="0" name="quantity" disabled value="' + arr[i][0]['amount'] + '" type="number" class="form-control form-control-sm"/><button onclick="LSPlusMinus(' + arr[i][0]['prodID'] + ',false,carttype)" class="checkoutCartMin">-</button><button class="btn btn-link px-2"<i class="fas fa-plus"></i></button></div><div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1"> <h5 class="mb-0">$' + totalProdPrice.toFixed(2) + '</h5> </div> <div class="col-md-1 col-lg-1 col-xl-1 text-end"> <a href="#!" class="text-danger"><i class="fas fa-trash fa-lg"></i></a> </div> </div> </div> </div>'
                    }
                    totalOrderPriceWrapper.innerHTML = '<div class="card" style="position: relative; bottom: 2.3vh; height: 50px"><h4 id="totaalOrderPrijsSpan" style="margin-top: 1%;margin-left: 1%">Totaal prijs: $' + parseFloat(totalOrderPrice).toFixed(2) + '</h4><h4 id="totaalOrderPrijsSpanNieuw" style="margin-top: 1%;margin-left: 1%; display: none"></h4></div>'
                    checkoutCartItemWrapper.innerHTML = html;
                }
            }
        }
    })
}

// checkout

function displayProfile(customerData = false) {
    document.getElementById('checkAccountDiv').style.display = 'none';
    document.getElementById('customerDataDiv').style.display = 'block';
    if (customerData) {
        document.getElementById('firstName').value = customerData['firstName'];
        document.getElementById('lastName').value = customerData['lastName'];
        document.getElementById('postalCode').value = customerData['postalCode'];
        document.getElementById('houseNumber').value = customerData['houseNumber'];
        document.getElementById('houseNumberExtra').value = customerData['houseNumberExtra'];
        document.getElementById('email').value = customerData['email'];
        document.getElementById('phone').value = customerData['phone'];
        document.getElementById('adress').value = customerData['adress'];
        document.getElementById('loggedIn').value = 1;
    }
}

function handleCustomerData() {
    let inputs = $("#form input");
    let filledFields = {};
    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].value) {
            filledFields[[inputs[i].id]] = inputs[i].value;
        }
    }
    if (filledFields.length !== 0) {
        if (document.getElementById('loggedIn').value === '1') {
            $.ajax({
                url: '../index.php?c=Checkout&m=handleCustomerData',
                method: 'POST',
                data: {formdata: filledFields},
                dataType: "JSON",
                success: function (response) {
                    if (response) {
                        window.location.href = '../index.php?c=Checkout&m=goToSuccess';
                    }
                },
                error: function (response) {
                    console.log('failed');
                    console.log(response)
                },
            });
        } else if (document.getElementById('loggedIn').value === '0') {
            if (typeof filledFields['password'] !== 'undefined' && typeof filledFields['passwordRepeat'] !== 'undefined' && filledFields['password'] === filledFields['passwordRepeat']) {
                $.ajax({
                    url: '../index.php?c=Checkout&m=handleCustomerData',
                    method: 'POST',
                    data: {formdata: filledFields},
                    dataType: "JSON",
                    success: function (response) {
                        if (response) {
                            localStorage.clear();
                            window.location.href = '../index.php?c=Checkout&m=goToSuccess';
                        }
                    },
                    error: function (response) {
                        console.log('failed');
                        console.log(response)
                    },
                });
            } else {
                alert('Wachtwoorden komen niet overheen en/of zijn leeg');
            }
        }
    }
}

function NoAccount() {
    displayProfile();
    document.getElementById('makeAccountDiv').style.display = 'block';
    let inputs = document.querySelectorAll('#makeAccountDiv input');
    for (const input in inputs) {
        input.required;
    }
}

function checkUserExists() { // checkt of user bestaat -> check wachtwoord -> haalt gegevens op
    let email = document.getElementById('userCheck');
    let psw = document.getElementById('passwordCheck');
    if (email.value && psw.value) {
        $.ajax({
            url: '../index.php?c=Checkout&m=checkUser',
            method: 'GET',
            data: {email: email.value, password: psw.value},
            dataType: "JSON",
            async: false,
            success: function (response) { // response krijgt false bij geen gevonden gebruiker
                if (response) {
                    displayProfile(response[0]);
                } else {
                    console.log(response)
                    alert('Geen bestaand account of verkeerde wachtwoord combinatie');
                }
            },
            error: function (response) {
                console.log('failed');
                console.log(response);
            },
        });
    } else {
        alert('email of wachtwoord leeg');
    }
}

function getUserData() { // checkt of gebruiker is ingelogd -> haalt gegevens op
    $.get('views/checkSession.php', function (data) { // checkt via checkSession.php of de gebruiker is ingelogd.
        if (data) {
            $.ajax({
                url: '../index.php?c=Checkout&m=getProfileData',
                method: 'GET',
                data: {id: data},
                dataType: "JSON",
                async: false,
                success: function (response) {
                    if (response) {
                        displayProfile(response[0]);
                    }
                },
                error: function (response) {
                    console.log('failed');
                    console.log(response)
                },
            });
        }
    });
}

function checkVoucher(value){
    let discountMessage = document.getElementById('discountMessage');
    let totaalPrijs = document.getElementById('totaalOrderPrijsSpan');
    let newPrice = document.getElementById('totaalOrderPrijsSpanNieuw');
    if (value.length !== 0){
        $.ajax({
            url: '../index.php?c=Checkout&m=checkVoucher',
            method: 'GET',
            data: {voucherCode: value},
            dataType: "JSON",
            async: false,
            success: function (response) {
                if (response){
                    discountMessage.innerHTML = response + '% korting';
                    discountMessage.style.color = 'green';
                    let discount = parseFloat(totaalPrijs.innerHTML.split('$').pop()) - (parseFloat('0.' + response) * parseFloat(totaalPrijs.innerHTML.split('$').pop()));
                    totaalPrijs.style.display = 'none';
                    newPrice.style.display = 'block';
                    newPrice.innerHTML = 'Totaal prijs: $'+discount.toFixed(2);
                } else {
                    discountMessage.innerHTML = 'kortingcode bestaat niet of is al gebruikt';
                    discountMessage.style.color = 'red';
                    newPrice.style.display = 'none';
                    totaalPrijs.style.display = 'block';
                }
            },
            error: function (response) {
                console.log('failed');
                console.log(response)
            },
        });
    } else {
        discountMessage.innerHTML = '';
        newPrice.style.display = 'none';
        totaalPrijs.style.display = 'block';
    }
}


