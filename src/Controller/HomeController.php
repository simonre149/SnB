<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\GoodRepository;
use App\Repository\SubcategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    public function index(GoodRepository $goodRepository, Request $request)
    {
        $allGoods = $goodRepository->findAllDescSentAt();

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $content = $form->get('search')->getData();
            $min_price = $form->get('min_price')->getData();
            $max_price = $form->get('max_price')->getData();
            $subcategory = $form->get('subcategory')->getData()->getId();

            if ($min_price == null) $min_price = 0;
            if ($max_price == null) $max_price = 999999999;

            return $this->redirectToRoute('search', [
                'content' => $content,
                'min_price' => $min_price,
                'max_price' => $max_price,
                'subcategory' => $subcategory
            ]);
        }

        return $this->render('pages/home.html.twig', [
            'current_menu' => 'home',
            'allGoods' => $allGoods,
            'form' => $form->createView()
        ]);
    }

    public function search($content, $min_price, $max_price, $subcategory, GoodRepository $goodRepository, SubcategoryRepository $subcategoryRepository, Request $request)
    {
        $allGoods = $goodRepository->search($content, $min_price, $max_price, $subcategory);
        $subcategory_name = $subcategoryRepository->findOneBy(['id' => $subcategory]);

        return $this->render('pages/home.html.twig', [
            'current_menu' => 'search',
            'allGoods' => $allGoods,
            'search_content' => $content,
            'subcategory_name' => $subcategory_name
        ]);
    }
}
