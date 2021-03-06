<?php


namespace App\Controller;

use App\Controller\ApiAbstractController;
use App\Entity\SaleItem;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use TypeError;

class DarkSaleItemApiController extends ApiAbstractController
{

    private function createDarkSaleItem($title, $price, $description='', $user_from='', $image='') {
        $sale_item = new SaleItem();

        if (null == $title or null == $price or false == is_numeric($price))
            return null;

        $sale_item->setTitle($title);
        $sale_item->setPrice(['value' => (float)$price, 'tag' => '€']);
        $sale_item->setDescription($description);
        $sale_item->setUserFrom($user_from);
        $sale_item->setDate(new DateTime('now'));
        $sale_item->setIsDark(true);

        $sale_item->setImage(['default' => '/image/dark-default.jpg', 'image' => $image]);

        $this->persistEntityManager($sale_item);
        $this->flushEntityManager();
        $this->refreshEntityManager($sale_item);

        return $sale_item;
    }

    /**
     * @Route("/dark-api/{username}/sale", methods={"GET"})
     * @param Request $request
     * @param $username
     * @return JsonResponse
     */
    public function sale_items(Request $request, $username)
    {
        $res['status'] = $this->isTokenValid($request, null, $username, true);

        if (200 != $res['status'])
            return new JsonResponse($res, $res['status']);

        $page = $request->query->get('page', 1);
        $nbByPage = $request->query->get('nbByPage', 50);

        $sale = $this->paginateSaleItem($page, $nbByPage, true);

        $res = array();

        $res['status'] = 200;
        $res['page'] = $sale->getCurrentPageNumber();
        $res['nbByPage'] = $sale->getItemNumberPerPage();
        $res['count_item'] = $sale->getTotalItemCount();
        $res['count_page'] = (int)($res['count_item'] / $res['nbByPage'] + 1);
        $res['sales_item'] = [];

        foreach ($sale as $elm)
            $res['sales_item'][] = $this->elmSaleItemGenerator($elm);

        return new JsonResponse($res, $res['status']);
    }

    /**
     * @Route("/dark-api/{username}/sale/{id}/info", methods={"GET"})
     * @param Request $request
     * @param $username
     * @param $id
     * @return JsonResponse
     */
    public function sale_item_info(Request $request, $username, $id)
    {
        $res['status'] = $this->isTokenValid($request, null, $username, true);

        if (200 != $res['status'])
            return new JsonResponse($res, $res['status']);


        $sale_item = $this->getSaleItemDarkById($id);

        if ($sale_item) {
            $res['status'] = 200;
            $res['sale_item'] = [$this->elmSaleItemGenerator($sale_item)];
        } else
            $res['status'] = 404;

        return new JsonResponse($res, $res['status']);
    }

    /**
     * @Route("/dark-api/{username}/create-sale-item", methods={"POST"})
     * @param Request $request
     * @param $username
     * @return JsonResponse
     */
    public function create_sale_item(Request $request, $username)
    {
        $user = $this->getUserClientByUsername($username);

        $res['status'] = $this->isTokenValid($request, $user, null, true);

        if (200 != $res['status'])
            return new JsonResponse($res, $res['status']);

        $title = $request->query->get('title');
        $price = $request->query->get('price');
        $description = $request->query->get('description');
        $image = $request->query->get('image');

        $sale_item = $this->createDarkSaleItem($title, $price, $description, $user->getEmail(), $image);

        if (null == $sale_item)
            return new JsonResponse(['status' => 422], 422);

        $res['sale_item'] = $this->elmSaleItemGenerator($sale_item);

        return new JsonResponse($res, $res['status']);
    }

    /**
     * @Route("/dark-api/{username}/sale/{id}/update", methods={"PATCH"})
     * @param Request $request
     * @param $username
     * @param $id
     * @return JsonResponse
     */
    public function update_sale_item(Request $request, $username, $id)
    {
        $user = $this->getUserClientByUsername($username);

        $res['status'] = $this->isTokenValid($request, $user, null, true);

        if (200 != $res['status'])
            return new JsonResponse($res, $res['status']);

        $price = $request->query->get('price');
        $description = $request->query->get('description');
        $image = $request->query->get('image');

        $sale_item = $this->getSaleItemDarkById($id);

        if ($username != $sale_item->getUserFrom())
            return new JsonResponse(['status' => 404], 404);

        if (null != $price)
            $sale_item->setPrice($price);
        if (null != $description)
            $sale_item->setDescription($description);
        if (null != $image)
            $sale_item->setImage($image);


        $res['sale_item'] = $this->elmSaleItemGenerator($sale_item);

        return new JsonResponse($res, $res['status']);
    }
}