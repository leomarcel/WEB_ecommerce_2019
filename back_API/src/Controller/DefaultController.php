<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class DefaultController extends AbstractController
{
    /**
     * Load the site definition and redirect to the default page.
     *
     * @Route("/")
     */
    public function indexAction()
    {
        $res = "https://documenter.getpostman.com/view/10218237/SzS7Rmj6";

		return $this->redirect($res);
    }
}