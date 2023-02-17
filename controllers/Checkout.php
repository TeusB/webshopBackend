<?php

namespace webshop;

class Checkout extends AbstractView
{
    private $c_model;
    private $u_model;
    private $cart_model;


    public function __construct()
    {
        session_start();
        $this->cart_model = new Cart_Model();
        $this->c_model    = new Checkout_Model();
        $this->u_model    = new User_Model();
    }

   public function goToCheckout()
    {
        $this->showView('checkout', ['nvt' => '']);
    }

    public function goToSuccess()
    {
        if (isset($_SESSION['idUser'])){
            $this->showView('success', ['loggedIn' => true]);
        } else {
            $this->showView('success', ['loggedIn' => false]);
        }
    }


    public function getProfileData()
    {
        if (isset($_GET['id'])) {
            echo json_encode($this->u_model->getUserProfileData($_GET['id']));
        }
    }

    public function handleCustomerData()
    {
        $data = $_POST['formdata'];
        if ( ! isset($_SESSION['idUser'])) { // nieuwe gebruiker
            $insertID = $this->u_model->dynamicInsert($data); // returnt ID die wordt gebruikt voor aanmaken shoppingCart;
            echo json_encode($this->cart_model->createShoppingCart($insertID)); // maakt shopping cart aan
        } else { // update bestaande gebruiker
            $this->cart_model->clearCart($_SESSION['idUser']);
            echo json_encode($this->u_model->dynamicUpdate($data, $_SESSION['idUser'])); // update gebruiker
        }
    }

    public function checkUser()
    {
        $email   = $_GET['email'];
        $psw     = $_GET['password'];
        $checkID = $this->u_model->getId($email);
        if ($checkID !== 0) { // $checkID geeft 0 terug als geen gebruiker is gevonden
            if (password_verify($psw, $this->u_model->getPassword($email))) {
                $profileData        = $this->u_model->getUserProfileData($checkID);
                $_SESSION['idUser'] = $checkID;
                echo json_encode($profileData);
            } else {
                echo json_encode(false);
            }
        } else {
            echo json_encode(false);
        }
    }

    function checkVoucher(){
        $check = $this->c_model->checkVoucherExists($_GET['voucherCode']);
        if ($check){
            echo json_encode($check[0]['worth']);
        } else {
            echo json_encode(false);
        }
    }

}