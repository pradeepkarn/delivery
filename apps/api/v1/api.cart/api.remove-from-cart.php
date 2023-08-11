<?php
import("apps/api/v1/api.cart/fn.cart.php");
import("apps/api/v1/api.cart/fn.checkout.php");

if (isset($_POST['pid'])) {
    if (isset($_SESSION['cart'])) {
        remove_from_cart_api($_POST['pid']);
    }
    if (isset($_SESSION['cart'])) {
        echo json_encode($_SESSION['cart']);
    }
}