<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Contacts;
use App\Entity\StaticPages;
use App\Entity\StaticPagesType;
use App\Service\UpdateData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class BaseController extends AbstractController
{
    private UpdateData $client;

    public function __construct(UpdateData $updateData)
    {
        $this->client = $updateData;
    }

    /**
     * @Route ("/", name="homepage")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        if($request->query->get('parse')){
            $this->client->parseShop();
        }

        return $this->render('site/index.twig',[
        ]);
    }


}