<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController {

    public function about() : Response
    {
        return new Response("page About !");
    }

    /**
     * @Route("", name="app_index")
     */
    public function index() : Response
    {
        return $this->render('pages/index.html.twig');
    }
}