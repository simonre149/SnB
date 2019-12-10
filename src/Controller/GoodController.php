<?php

namespace App\Controller;

use App\Entity\Good;
use App\Form\EditType;
use App\Form\GoodType;
use App\Repository\GoodRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class GoodController extends AbstractController
{
    public function sell(Request $request, EntityManagerInterface $entityManager)
    {
        $seller = $this->getUser();

        $good = new Good();

        $good->setCreatedAt(new \DateTime());
        $good->setSeller($seller);

        $form = $this->createForm(GoodType::class, $good);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($good);
            $entityManager->flush();

            return $this->redirectToRoute('home');
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

    public function edit($good_id, GoodRepository $goodRepository, Request $request, EntityManagerInterface $entityManager)
    {
        $good = $goodRepository->findOneById($good_id);

        if ($this->getUser()->getId() != $good->getSeller()->getId()) return $this->redirectToRoute('home');

        $form = $this->createForm(EditType::class, $good);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('pages/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function delete($good_id, GoodRepository $goodRepository, EntityManagerInterface $entityManager, Filesystem $filesystem)
    {
        $good = $goodRepository->findOneById($good_id);

        if ($this->getUser()->getId() != $good->getSeller()->getId()) return $this->redirectToRoute('home');

        $filesystem->remove($good->getFilename());

        $entityManager->remove($good);
        $entityManager->flush();
        return $this->redirectToRoute('profile');
    }
}