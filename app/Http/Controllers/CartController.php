<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $tax = 18;
    public function add() {
        $product = ["id"=>1, "name"=>"Product 1", "qty"=> 2, "price"=>55];
        $cart = session()->get('cart', []);
        $cart[$product['id']] = $product;
        session()->put("cart", $cart);
        Cart::create($product);
        return response(["cart"=>$cart]);
    }

    public function addMultiple() {
        $products = [
            ["id"=>2, "name"=>"Product 1", "qty"=> 1, "price"=>155],
            ["id"=>3, "name"=>"Product 3", "qty"=> 1, "price"=>20],
            ["id"=>4, "name"=>"Product 3", "qty"=> 4, "price"=>5],
        ];
        $cart = session()->get('cart', []);
        foreach ($products as $product) {
          $cart[$product['id']] = $product;
          Cart::create($product);
        }

        session()->put("cart", $cart);

        return response(["cart"=>$cart]);
    }

    public function content() {
        $cart = session()->get('cart', []);
        return response(["cart"=>$cart]);
    }

    public function get($id) {
        $cart = session()->get('cart', []);
        return response(["cart"=>$cart[$id]]);
    }

    public function delete($id) {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            unset($cart[$id]);
            Cart::destroy($id);
        } else {
            return response(["message"=>"Key is not exist!!!"]);
        }
        session()->put("cart", $cart);
        return response(["cart"=>$cart]);
    }

    public function clear() {
        session()->forget("cart");
        $cart = session()->get('cart', []);
        Cart::query()->delete();
        return response(["cart"=>$cart]);
    }

    public function subtotal() {
        $total = 0;
        $cart = session()->get('cart', []);
        foreach ($cart as $item) {
            $total+= $item['qty'] * $item['price'];
        }
        return response(["total"=>$total]);
    }

    public function total() {
        $total = 0;
        $cart = session()->get('cart', []);
        foreach ($cart as $item) {
            $total+= $item['qty'] * $item['price'];
        }

        $subtotal = $total + ($this->tax * $total) / 100;
        return response(["subtotal" => $subtotal]);
    }

    public function count() {
        $cart = session()->get('cart', []);
        return response(["subtotal" => count($cart)]);
    }

    public function update($id, $qty)
    {
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {
            $cart[$id]['qty'] = (int)$qty;
            session()->put("cart", $cart);
            Cart::where("id", $id)->update(['qty'=>$qty]);
            return response(["cart" => $cart]);
        }

        return response(["Message" => "Key is not exist!!!"]);
    }

    public function getTax()
    {
        return response(["tax" => $this->tax]);
    }

    public function setTax($tax)
    {
        $this->tax  = $tax;
        return response(["tax" => $this->tax]);
    }
}
