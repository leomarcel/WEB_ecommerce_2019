<?php


namespace App\Controller;

use App\Controller\ShoppingCartAbstractController;
use App\Entity\SaleItem;
use App\Entity\User;
use App\Entity\ShoppingCart;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;

class ShoppingCartController extends ShoppingCartAbstractController
{
    /**
     * @Route("/api/{username}/cart", methods={"GET"})
     * @param Request $request
     * @param $username
     * @return JsonResponse
     */
    public function getUserCart(Request $request, $username)
    {
        $res['status'] = $this->isTokenValid($request, null, $username);

        if (200 != $res['status'])
            return new JsonResponse($res, $res['status']);

        $cart = $this->formatShoppingCart($username);

        $res['cart'] = [
            'username' => $username,
            'count' => $cart['count'],
            'sales_item' => $cart['sales_item'],
            'total_price' => $cart['total_price']
        ];

        return new JsonResponse($res, $res['status']);
    }

    /**
     * @Route("/api/{username}/cart", methods={"PUT"})
     * @param Request $request
     * @param $username
     * @return JsonResponse
     */
    public function putUserCart(Request $request, $username)
    {
        $user = $this->getUserClientByUsername($username);

        $res['status'] = $this->isTokenValid($request, $user);

        if (200 != $res['status'])
            return new JsonResponse($res, $res['status']);

        $data = $request->query->get('data');

        $cart = $this->updateShoppingCart($username, $data, $user->getDarkModeAllowed());
        $cart = $this->formatShoppingCart(null, $cart);

        $res['cart'] = [
            'username' => $username,
            'count' => $cart['count'],
            'sales_item' => $cart['sales_item']
        ];

        return new JsonResponse($res, $res['status']);
    }

    /**
     * @Route("/api/{username}/cart/add", methods={"PATCH"})
     * @param Request $request
     * @param $username
     * @return JsonResponse
     */
    public function patchUserCart(Request $request, $username)
    {
        $user = $this->getUserClientByUsername($username);

        $res['status'] = $this->isTokenValid($request, $user);

        if (200 != $res['status'])
            return new JsonResponse($res, $res['status']);

        $data = $request->query->get('data');

        $cart = $this->addToShoppingCart($username, $data, $user->getDarkModeAllowed());
        $cart = $this->formatShoppingCart(null, $cart);

        $res['cart'] = [
            'username' => $username,
            'count' => $cart['count'],
            'sales_item' => $cart['sales_item']
        ];

        return new JsonResponse($res, $res['status']);
    }

    /**
     * @Route("/api/{username}/cart/remove", methods={"PATCH"})
     * @param Request $request
     * @param $username
     * @return JsonResponse
     */
    public function deleteUserCart(Request $request, $username)
    {
        $res['status'] = $this->isTokenValid($request, null, $username);

        if (200 != $res['status'])
            return new JsonResponse($res, $res['status']);

        $data = $request->query->get('data');

        $cart = $this->removeInShoppingCart($username, $data);
        $cart = $this->formatShoppingCart(null, $cart);

        $res['cart'] = [
            'username' => $username,
            'count' => $cart['count'],
            'sales_item' => $cart['sales_item']
        ];

        return new JsonResponse($res, $res['status']);
    }

}