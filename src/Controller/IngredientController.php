<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
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
    #[Route('/ingredient', name: 'ingredient.index')]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {   $all_ingredients = $repository->findAll();
        $ingredients = $paginator->paginate(
            $all_ingredients,
            $request->query->getInt('page', 1),10
        );
        return $this->render('ingredient/index.html.twig', ['ingredients' => $ingredients]);
    }
}
