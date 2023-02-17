<?php
session_start();
error_reporting(1);
?>

<!--<a href='index.php?c=user&m=loadLoginPage'>login</a>-->
<!--<a href='index.php?c=user&m=loadRegisterPage'>Register</a>-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Shop Homepage - Start Bootstrap Template</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico"/>
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet"/>
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../css/ownStyles.css" rel="stylesheet"/>
    <link href="../css/styles.css" rel="stylesheet"/>
    <script type="text/javascript" src="../js/ownJS.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="#!">Start Bootstrap</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span
                    class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="#!">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#!">About</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">All Products</a></li>
                        <li>
                            <hr class="dropdown-divider"/>
                        </li>
                        <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                        <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <section class="h-100" style="background-color: #eee;">
            <div class="container h-100 py-5">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-10">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="fw-normal mb-0 text-black">Shopping Cart</h3>
                            </div>
                            <div id="CheckoutItemWrapper">

                            </div>
                            <div id="totalprice">

                            </div>
                        </div>
                        <div class="card mb-4">
                            <h3 class="fw-normal mb-0 text-black" style="padding: 25px">Klantgegevens</h3>
                            <div style="padding-left: 20px;padding-right: 20px">
                                <div id="checkAccountDiv">
                                    <form>
                                        <h4>Heb je al een account?</h4>
                                        <div>
                                            <label>Email:</label>
                                            <input id="userCheck" style="margin-bottom: 20px" class="form-control"
                                                   type="text">
                                        </div>
                                        <div>
                                            <label>Wachtwoord:</label>
                                            <input id="passwordCheck" style="margin-bottom: 20px" class="form-control"
                                                   type="password">
                                        </div>
                                        <div style="margin-bottom: 20px">
                                            <div style="display: inline">
                                                <button onclick="checkUserExists()" type="button" id="userCheckSubmit">
                                                    Checken
                                                </button>
                                            </div>
                                            <div style="display: inline">
                                                <button id="noAccountSubmit" onclick="NoAccount()" type="button"
                                                        style="display: inline">Ik heb geen
                                                    account
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="customerDataDiv" style="display: none">
                                    <form id="form" method="post" onsubmit="handleCustomerData()">
                                        <!-- 2 column grid layout with text inputs for the first and last names -->
                                        <div class="row mb-4">
                                            <div class="col">
                                                <div class="form-outline">
                                                    <label class="form-label" for="voornaam">Voornaam:</label>
                                                    <input required type="text" name="firstName" id="firstName"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="form-outline">
                                                    <label class="form-label" for="achternaam">Achteraam:</label>
                                                    <input required type="text" name="lastName" id="lastName"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Text input -->
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-outline mb-4">
                                                    <label class="form-label" for="adress">Adress:</label>
                                                    <input required type="text" name="adress" id="adress"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-outline mb-4">
                                                    <label class="form-label" for="postcode">Postcode:</label>
                                                    <input required type="text" name="postalCode" id="postalCode"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-outline mb-4">
                                                    <label class="form-label" for="huisnr">Huisnummer:</label>
                                                    <input required type="number" name="houseNumber" id="houseNumber"
                                                           class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="form-outline mb-4">
                                                    <label class="form-label" for="huisnrtvg">Toevoeging:</label>
                                                    <input type="text" name="houseNumberExtra" id="houseNumberExtra"
                                                           class="form-control"/>
                                                </div>
                                            </div
                                        </div>
                                        <!-- Email input -->
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="email">Email:</label>
                                            <input required type="email" name="email" id="email" typeof="email"
                                                   class="form-control"/>
                                        </div>
                                        <div id="makeAccountDiv"
                                             style="height: 170px; display: none;">
                                            <div style="display: inline">
                                                <label>Wachtwoord</label>
                                                <input id="password" class="form-control" style="display: inline"
                                                       type="password" name="password">
                                            </div>
                                            <div style="display: inline">
                                                <label style="margin-top: 15px">Wachtwoord herhalen</label>
                                                <input id="passwordRepeat" class="form-control"
                                                       style="display: inline" type="password" name="passwordRepeat">
                                            </div>
                                        </div>
                                        <!-- Number input -->
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="tel">Telefoonnummer:</label>
                                            <input required type="number" name="phone" id="phone" class="form-control"/>
                                        </div>
                                        <div class="form-outline flex-fill">
                                            <label class="form-label" for="discount">Discount code</label><span style="margin-left: 5px" id="discountMessage"></span>
                                            <input type="text" id="discount" name="discount"
                                                   class="form-control form-control-lg"/>
                                        </div>
                                        <div class="form-outline flex-fill">
                                            <input value="0" type="hidden" id="loggedIn" name="loggedIn"
                                                   class="form-control form-control-lg"/>
                                        </div>
                                        <!-- Submit button -->
                                        <div style="padding-top: 15px">
                                            <button type="submit" class="btn btn-primary btn-block mb-4">Place order
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</section>
<script>
    displayCart('checkoutcart');

    getUserData();

    // timer voucher
    let input = document.getElementById('discount');

    let timeout = null;
    input.addEventListener('keyup', function (e) {

        clearTimeout(timeout);

        timeout = setTimeout(function () {
            checkVoucher(input.value)
        }, 1000);
    });

</script>
<!-- Footer-->
<footer class="py-5 bg-dark">
    <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2022</p></div>
</footer>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Core theme JS-->
<script src="js/scripts.js"></script>
</body>
</html>
