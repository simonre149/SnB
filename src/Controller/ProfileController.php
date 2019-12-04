<?php

namespace App\Controller;

use App\Repository\GoodRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    public function index(GoodRepository $goodRepository)
    {
        $allUserGoods = $goodRepository->findAllByUserId($this->getUser()->getId());

        return $this->render('pages/profile.html.twig', [
            'allUserGoods' => $allUserGoods
        ]);
    }
}
