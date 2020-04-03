<?php


namespace App\Controller;


use App\Entity\SaleItem;
use App\Entity\SaleItemDarkMode;
use App\Entity\User;
use App\Entity\ShoppingCart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\Paginator;


class ApiAbstractController extends AbstractController
{

    private function getUserClient() {
        return $this->getDoctrine()->getRepository(User::class);
    }

    private function getSaleItem() {
        return $this->getDoctrine()->getRepository(SaleItem::class);
    }

    private function getShoppingCart() {
        return $this->getDoctrine()->getRepository(ShoppingCart::class);
    }

    protected function getUserClientByAll()
    {
        return $this->getUserClient()->findAll();
    }

    protected function getUserClientById(int $id)
    {
        return $this->getUserClient()->find($id);
    }

    protected function getUserClientByUsername($username)
    {
        return $this->getUserClient()->findOneBy(['email' => $username]);
    }

    protected function getSaleItemByAll()
    {
        return $this->getSaleItem()->findBy(['is_dark' => false]);
    }

    protected function getSaleItemDarkByAll()
    {
        return $this->getSaleItem()->findBy(['is_dark' => true]);
    }

    protected function getSaleItemById(int $id)
    {
        return $this->getSaleItem()->findOneBy(['is_dark' => false, 'id' => $id]);
    }

    protected function isInSaleItemByAll()
    {
        return $this->getSaleItem()->findAll();
    }

    protected function isInSaleItemById(int $id)
    {
        return $this->getSaleItem()->find($id);
    }

    protected function getSaleItemDarkById(int $id)
    {
        return $this->getSaleItem()->findOneBy(['is_dark' => true, 'id' => $id]);
    }

    protected function paginateSaleItem(int $page=1, int $nbByPage=50, $dark=false)
    {
        if (0 >= $page)
            $page = 1;
        if (0 >= $nbByPage)
            $nbByPage = 50;
        if (100 < $nbByPage)
            $nbByPage = 100;

        $paginator = new Paginator();
        if (false == $dark)
            $sales_item = $this->getSaleItemByAll();
        else
            $sales_item = $this->getSaleItemDarkByAll();
        $page  = $paginator->paginate(
            $sales_item,
            $page,
            $nbByPage
        );
        return $page;
    }

    protected function getShoppingCartByUsername($username)
    {
        return $this->getShoppingCart()->findOneBy(['user_own' => $username]);
    }

    protected function refreshEntityManager($object)
    {
        $this->getDoctrine()->getManager()->refresh($object);
    }

    protected function persistEntityManager($object)
    {
        $this->getDoctrine()->getManager()->persist($object);
    }

    protected function removeEntityManager($object)
    {
        $this->getDoctrine()->getManager()->remove($object);
    }

    protected function flushEntityManager()
    {
        $this->getDoctrine()->getManager()->flush();
    }

    protected function elmSaleItemGenerator($elm)
    {
        if (null == $elm)
            return [];
        $res = [
            'id' => $elm->getId(),
            'title' => $elm->getTitle(),
            'price' => $elm->getPrice(),
            'date' => $elm->getDate(),
            'description' => $elm->getDescription(),
            'image' => $elm->getImage(),
            'user_from' => $elm->getUserFrom()
        ];
        if (true == $elm->getIsDark())
            $res['is_dark'] = true;
        return $res;
    }

    protected function elmUserGenerator($elm)
    {
        if (null == $elm)
            return [];
        $res = [
            'id' => $elm->getId(),
            'username' => $elm->getUsername(),
            'email' => $elm->getEmail(),
            'name' => $elm->getName(),
            'apikey' => $elm->getApikey()
        ];
        if (true == $elm->getDarkModeAllowed())
            $res['dark_mode_allowed'] = true;
        return $res;
    }

    protected function tokenExist($apikey_json, $apikey, $refreshToken) {
        foreach ($apikey_json as $elm) {
            if (null != $apikey and $elm['apikey'] == $apikey)
                return True;
            if (null != $refreshToken and $elm['refreshToken'] == $refreshToken)
                return True;
        }
        return False;
    }

    protected function isTokenValid($request, $user, $username=null, $darkm=false)
    {
        if (null == $user and null != $username)
            $user = $this->getUserClientByUsername($username);

        return 200;
        $apikey = $request->headers->get('Authorization');

        if (null == $request or null == $user or null == $apikey)
            return 404;
        if (False == $this->tokenExist($user->getApikey(), $apikey, null)
            or true == $darkm and false == $user->getDarkModeAllowed())
            return 401;
        return 200;
    }
}