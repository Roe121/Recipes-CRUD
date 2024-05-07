<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Ingredient;
use App\Form\IngredientType;
use Doctrine\ORM\EntityManagerInterface;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        $ingredients = $paginator->paginate(
            $repository->findAll(), 
            $request->query->getInt('page', 1), 
            7
        );


        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients
        ]);
    }

    #[Route('/ingredient/new','app_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $entityManager->persist($ingredient);
            $entityManager->flush();

            $this->addFlash('success', 'Ingredient added successfully!');

            return $this->redirectToRoute('app_ingredient');
        }

        return $this->render('pages/ingredient/new.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    #[Route('/ingredient/edit/{id}', name: 'app_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit($id, IngredientRepository $repository, Request $request, EntityManagerInterface $entityManager): Response
{
    $ingredient = $repository->find($id);
    $form = $this->createForm(IngredientType::class, $ingredient);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $ingredient = $form->getData();

        $entityManager->persist($ingredient);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Ingredient edited successfully!'
        );

        return $this->redirectToRoute('app_ingredient');

    }

    return $this->render('pages/ingredient/edit.html.twig',
        [
            'form' => $form->createView()
        ]
    );
}
}
