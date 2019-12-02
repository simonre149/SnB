<?php

namespace App\Controller;

use App\Entity\Good;
use App\Form\GoodType;
use App\Repository\GoodRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class GoodController extends AbstractController
{
    public function sell(Request $request, ObjectManager $manager)
    {
        $seller = $this->getUser();

        $good = new Good();

        $good->setCreatedAt(new \DateTime());
        $good->setSeller($seller);

        $form = $this->createForm(GoodType::class, $good);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($good);
            $manager->flush();

            return $this->redirectToRoute('home', [
                'good_id' => $good->getId(),
                ]
            );
        }

        return $this->render('pages/sell.html.twig', [
            'form' => $form->createView(),
            'current_menu' => 'sell',
        ]);
    }

    public function good($good_id, GoodRepository $goodRepository)
    {
        $good = $goodRepository->findOneById($good_id);

        return $this->render('pages/good.html.twig', [
            'good' => $good
        ]);
    }
}
