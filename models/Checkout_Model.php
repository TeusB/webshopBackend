<?php

namespace webshop;

class Checkout_Model extends Model
{

//    public function getShoppingCartData(int $shoppingCartID)
//    {
//        return $this->select('select ShoppingCartItem.idProduct,Product.name,Product.descr,Product.price,ShoppingCartItem.amount,round((ShoppingCartItem.amount * Product.price),2) as totalPrice from ShoppingCartItem inner join Product on ShoppingCartItem.idProduct = Product.idProduct where ShoppingCartItem.idShoppingCart = ?;',[$shoppingCartID],'i');
//    }

//    public function getShoppingCartID(int $idUser)
//    {
//        return $this->select("SELECT idShoppingCart FROM shoppingCart WHERE idUser = ?", [$idUser], "i");
//    }

    public function checkVoucherExists(string $voucherCode){
        return $this->select("select * from Voucher where code = ? AND used = ?", [$voucherCode,0], "si");
    }
    public function setVoucherUsed(string $voucherCode){
        return $this->update('update Voucher set used = ? where code = ?',[1,$voucherCode],"is");
    }
}