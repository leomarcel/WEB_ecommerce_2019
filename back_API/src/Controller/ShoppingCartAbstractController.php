<?php


namespace App\Controller;

use App\Controller\ApiAbstractController;
use App\Entity\SaleItem;
use App\Entity\User;
use App\Entity\ShoppingCart;
use DateTime;


class ShoppingCartAbstractController extends ApiAbstractController
{
    protected function createShoppingCart($email, $data = [], $darkm = false)
    {
        $cart = new ShoppingCart();

        if (null == $email)
            return null;

        if (false == $darkm)
            $sales_item = $this->getSaleItemByAll();
        else
            $sales_item = $this->isInSaleItemByAll();

        $carts_item = [];
        foreach ($data as $id)
            if (is_numeric($id) and array_key_exists($id, $sales_item))
                if (array_key_exists($id, $carts_item))
                    $carts_item[$id['id']] = [
                        'id' => $id['id'],
                        'count' => $carts_item[$id['id']]['count'] + $id['count']];
                else
                    $cats_item[$id['id']] = [
                        'id' => $id['id'],
                        'count' => $id['count']];

        $cart->setUserOwn($email);
        $cart->setData($carts_item);
        $cart->setLastUpdateDate(new DateTime('now'));

        $this->persistEntityManager($cart);
        $this->flushEntityManager();
        $this->refreshEntityManager($cart);

        return $cart;
    }

    protected function updateShoppingCart($email, $data = [], $darkm = false)
    {

        $cart = $this->getShoppingCartByUsername($email);

        if (null == $email)
            return null;
        else if (null == $cart)
            return $this->createShoppingCart($email, $data);

        if (false == $darkm)
            $sales_item = $this->getSaleItemByAll();
        else
            $sales_item = $this->isInSaleItemByAll();

        $carts_item = [];
        foreach ($data as $id)
            if (is_numeric($id) and array_key_exists($id, $sales_item))
                if (array_key_exists($id, $carts_item))
                    $carts_item[$id['id']] = [
                        'id' => $id['id'],
                        'count' => $carts_item[$id['id']]['count'] + $id['count']];
                else
                    $carts_item[$id['id']] = [
                        'id' => $id['id'],
                        'count' => $id['count']];

        $cart->setData($carts_item);
        $cart->setLastUpdateDate(new DateTime('now'));

        $this->flushEntityManager();

        return $cart;
    }

    protected function addToShoppingCart($email, $data = [], $darkm = false)
    {

        $cart = $this->getShoppingCartByUsername($email);

        if (null == $email)
            return null;
        else if (null == $cart)
            return $this->createShoppingCart($email, $data);

        if (false == $darkm)
            $sales_item = $this->getSaleItemByAll();
        else
            $sales_item = $this->isInSaleItemByAll();

        $carts_item = $cart->getData();
        if (null == $carts_item)
            $carts_item = [];
        foreach ($data as $id)
            if (is_numeric($id['id']) and array_key_exists($id, $sales_item))
                $carts_item[$id['id']] = [
                    'id' => $id['id'],
                    'count' => $carts_item[$id['id']]['count'] + $id['count']];

        $cart->setData($carts_item);

        $this->flushEntityManager();

        return $cart;
    }

    protected function removeInShoppingCart($email, $data = [])
    {

        $cart = $this->getShoppingCartByUsername($email);

        if (null == $email or null == $cart)
            return null;

        $sales_item = $cart->getData();

        if (null == $sales_item)
            return $cart;
        foreach ($data as $id)
            if (is_numeric($id['id'])) {
                $count = $sales_item[$id['id']]['count'] - $id['count'];
                if ($count > 0)
                    $sales_item[$id['id']] = [
                        'id' => $id['id'],
                        'count' => $count];
            }

        $cart->setData($sales_item);

        $this->flushEntityManager();

        return $cart;
    }

    protected function formatShoppingCart($email = null, $cart = null)
    {

        if (null == $email and null == $cart)
            return null;
        else if (null != $email)
            $cart = $this->getShoppingCartByUsername($email);

        $res = ['count' => null, 'sales_item' => null];
        $res['count'] = 0;
        $res['total_price'] = ['value' => 0, 'tag' => 'EUR'];
        $res['sales_item'] = [];


        if (null == $cart or null == ($data = $cart->getData()))
            return $res;

        foreach ($data['data'] as $id)
            if (is_numeric($id)) {
                $sale_item = $this->getSaleItemById($id);
                $id['sale_item'] = $this->elmSaleItemGenerator($sale_item);
                $res['sales_item'][] = $id;
                $res['count'] += 1;
                if (null != $sale_item)
                    $res['total_price']['value'] += (float)$sale_item->getPrice()['value'];
            }

        return $res;
    }
}