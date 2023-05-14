<?php
include ("init.php");
use Models\Cart;   

$carts = new Cart('', '', '','');
$carts->setConnection($connection);
$all_carts = $carts->getCart(1);

//This is your test secret API key.
\Stripe\Stripe::setApiKey('sk_test_51LgIL6JDVdkZQkB8LlIgHQbRhd0nkWRJZmUlg32mJIleQ6DyHGmdMg2JXKk3wXWenDOaQ8fMGgmBBSt0wmJeY0HY00FjrBokRO');
header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost/sia_pentegrate';

if (count($all_carts)>1){
  $corsair_id = $all_carts[0]['product_id'];
  $corsair_quantity = $all_carts[0]['cart_item_quantity'];

  $logitech_id = $all_carts[1]['product_id'];
  $logitech_quantity = $all_carts[1]['cart_item_quantity'];


  $checkout_session = \Stripe\Checkout\Session::create([
    'line_items' => [[
      'price' => 'price_1N6YXwJDVdkZQkB8JaGOTYKP',
      'quantity' => $corsair_quantity
    ],[
      'price' => 'price_1N6YhOJDVdkZQkB8fU8Hheuh',
      'quantity' => $logitech_quantity
    ]],
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/order.php',
    'cancel_url' => $YOUR_DOMAIN . '/index.php',
  ]);



} else {
  $product_id = $all_carts[0]['product_id'];
  $cart_item_quantity = $all_carts[0]['cart_item_quantity'];

  if($product_id===1){
    $price_id = 'price_1N6YXwJDVdkZQkB8JaGOTYKP';
  } else{
    $price_id = 'price_1N6YhOJDVdkZQkB8fU8Hheuh';
  }
  $checkout_session = \Stripe\Checkout\Session::create([
    'line_items' => [[
      'price' => $price_id,
      'quantity' => $cart_item_quantity
    ]],
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/order.php',
    'cancel_url' => $YOUR_DOMAIN . '/index.php',
  ]);
}
header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);