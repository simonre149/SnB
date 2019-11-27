<?php

namespace App\Controller;

use App\Repository\GoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index(GoodRepository $repository)
    {
        $allGoods = $repository->findAll();

        return $this->render('pages/home.html.twig', [
            'current_menu' => 'home',
            'allGoods' => $allGoods
        ]);
    }
}
