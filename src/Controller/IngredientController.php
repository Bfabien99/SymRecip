<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    /**
     * Get all ingredient with pagination
     *
     * @param IngredientRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'ingredient.index', methods:['GET'])]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {   $all_ingredients = $repository->findAll();
        $ingredients = $paginator->paginate(
            $all_ingredients,
            $request->query->getInt('page', 1),10
        );
        return $this->render('ingredient/index.html.twig', ['ingredients' => $ingredients]);
    }

    #[Route('/ingredient/nouveau', name: 'ingredient.new', methods:['GET', 'POST'])]
    public function new(ManagerRegistry $doctrine,Request $request): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $ingredient = $form->getData();
            $doctrine->getManager()->persist($ingredient);
            $doctrine->getManager()->flush();

        }

        return $this->render('ingredient/new.html.twig',['form'=>$form->createView()]);
    }
}
