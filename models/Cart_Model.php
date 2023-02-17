<?php
namespace webshop;
//require_once '../classes/Model.php';


class Cart_Model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function selectListItemsByCart(int $idUser)
    {
        return $this->select("SELECT name, price FROM cart WHERE idUser = ?", $idUser, "i");
    }

    public function createShoppingCart(int $idUser){
        return $this->insert('insert into ShoppingCart (idUser) values (?)',[$idUser],'i');
    }

     public function getShoppingCartData(int $shoppingCartID)
     {
         return $this->select('select ShoppingCartItem.idProduct,Product.name,Product.descr,Product.price,ShoppingCartItem.amount,round((ShoppingCartItem.amount * Product.price),2) as totalPrice from ShoppingCartItem inner join Product on ShoppingCartItem.idProduct = Product.idProduct where ShoppingCartItem.idShoppingCart = ?;',[$shoppingCartID],'i');
     }

    public function getShoppingCartID(int $idUser)
    {
        return $this->select("SELECT idShoppingCart FROM shoppingCart WHERE idUser = ?", [$idUser], "i");
    }

    public function addItemToCart(int $idItem, int $idShoppingCart, int $amount)
    {
        $values = [$idItem, $idShoppingCart, $amount];
        return $this->insert(
            "INSERT INTO shoppingCartItem (idProduct, idShoppingCart, amount) VALUES (?, ?, ?);",
            $values,
            "iii"
        );
    }

    public function checkItemExistInCart($idShoppingCart, $idItem)
    {
        $values = [$idShoppingCart, $idItem];
        return $this->selectBool("SELECT idProduct FROM shoppingCartItem WHERE idShoppingCart = ? AND idProduct = ?", $values, "ii");
    }

    public function returnAmount($idShoppingCart, $idItem)
    {
        $values = [$idShoppingCart, $idItem];
        return $this->select("SELECT amount FROM shoppingCartItem WHERE idShoppingCart = ? AND idProduct = ?", $values, "ii");
    }


    public function addAmountToCart(int $idShoppingCart,int $idItem,int $amount)
    {
        $values = [$amount, $idShoppingCart, $idItem];
        return $this->update('update ShoppingCartItem set amount = ? where idShoppingCart = ? and idProduct = ?',$values,"iii");
    }

    // public function addItem(array $arr)
    // {
    //     if (isset($arr['idproduct'], $arr['idShoppingCart'], $arr['amount'])) {
    //         return $this->insert(
    //             "INSERT INTO shoppingCartItem (idProduct, idShoppingCart, amount) VALUES ({$arr['idproduct']}, {$arr['idShoppingCart']}, '{$arr['amount']}');"
    //         );
    //     }
    //     throw new \Exception('Niet gelukt om het product toe te voegen aan het boodschappenwagentje.');
    // }

    public function clearCart($idUser){
        return $this->delete('delete from shoppingCartItem where idShoppingCart = (select idShoppingCart from ShoppingCart where idUser = ?)',[$idUser],'i');
    }

     public function delItem(int $productID,int $idShoppingCart)
     {
        return $this->delete('delete from ShoppingCartItem where idProduct = ? and idShoppingCart = ?',[$productID,$idShoppingCart],'ii');
     }
}
