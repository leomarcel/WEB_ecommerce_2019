<?php


namespace App\Controller;

use App\Controller\ShoppingCartAbstractController;
use App\Entity\SaleItem;
use App\Entity\User;
use App\Entity\ShoppingCart;
use Stripe\Customer;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;


class PayementController extends ShoppingCartAbstractController
{
    /**
     * @Route("/api/{username}/cart/payement", methods={"POST"})
     * @param Request $request
     * @param $username
     * @return JsonResponse
     */
    public function payementCart(Request $request, $username)
    {
        $res['status'] = $this->isTokenValid($request, null, $username);

        if (200 != $res['status'])
            return new JsonResponse($res, $res['status']);

        $cart = $this->formatShoppingCart($username);
        $this->updateShoppingCart($username, []);

        $res['redirect_link'] = "https://paypal.me/cocoponey";
        $res['cart'] = [
            'username' => $username,
            'count' => $cart['count'],
            'sales_item' => $cart['sales_item'],
            'total_price' => $cart['total_price']
        ];

        return new JsonResponse($res, $res['status']);
    }

}