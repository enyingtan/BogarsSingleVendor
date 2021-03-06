<?php

namespace App\Http\Controllers\Publics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Cart;

/*
 * Used most of all for ajax requests
 */

class CartController extends Controller
{

    private $cart;

    public function __construct()
    {
        $this->cart = new Cart();
    }

    public function addProduct(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $post = $request->all();
        $quantity = (int) $post['quantity'];
        
        if ($quantity == 0) {
            $quantity = 1;
        }
        
        if (!isset($post['product_size']) || empty($post['product_size'])) {
            $product_size = 'XS';
        } else {
            $product_size = $post['product_size'];
        }

        $this->cart->addProduct($post['id'], $quantity, $product_size);
    }

    public function changeSize(Request $request) {
        if (!$request->ajax()) {
            abort(404);
        }
        $post = $request->all();
        $size = $post['size'];
        $this->cart->changeSize($post['id'], $size);
    }

    public function renderCartProductsWithHtml(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        echo json_encode(array(
            'html' => $this->cart->getCartHtmlWithProducts(),
            'num_price_products' => '('.$this->cart->countProducts.') '.$this->cart->totalPrice.'€'
        ));
    }

    public function removeProductQuantity(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $post = $request->all();
        $this->cart->removeProductQuantity($post['id']);
    }

    public function getProductsForCheckoutPage(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        echo $this->cart->getCartHtmlWithProductsForCheckoutPage();
    }

    public function removeProduct(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $post = $request->all();
        $this->cart->removeProduct($post['id']);
    }

}
