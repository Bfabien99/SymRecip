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

    /**
     * Create new ingredient
     *
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @return Response
     */
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

            $this->addFlash(type:'success',message:'Nouvel ingrédient ajouté !');
            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('ingredient/new.html.twig',['form'=>$form->createView()]);
    }

    
    #[Route('/ingredient/editer/{id}', name: 'ingredient.edit', methods:['GET', 'POST'])]    
    /**
     * Edit an ingredient
     *
     * @param  Ingredient $ingredient
     * @param  ManagerRegistry $doctrine
     * @param  Request $request
     * @return Response
     */
    public function edit(Ingredient $ingredient=null,ManagerRegistry $doctrine,Request $request): Response
    {
        
        if(!$ingredient){
            $this->addFlash(type:'error',message:'Ingrédient inconnu !');
            return $this->redirectToRoute('ingredient.index');
        }
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $ingredient = $form->getData();

            $doctrine->getManager()->persist($ingredient);
            $doctrine->getManager()->flush();

            $this->addFlash(type:'success',message:'Modifications effectuées !');
            return $this->redirectToRoute('ingredient.index');
        }

        return $this->render('ingredient/edit.html.twig',['form'=>$form->createView()]);
    }

    #[Route('/ingredient/delete/{id}', name: 'ingredient.delete', methods:['GET'])]    
    /**
     * Delete an ingredient
     *
     * @param  Ingredient $ingredient
     * @param  ManagerRegistry $doctrine
     * @return Response
     */
    public function delete(Ingredient $ingredient=null,ManagerRegistry $doctrine ): Response
    {
        if(!$ingredient){
            $this->addFlash(type:'error',message:'Ingrédient inconnu !');
            return $this->redirectToRoute('ingredient.index');
        }else{
            $doctrine->getManager()->remove($ingredient);
            $doctrine->getManager()->flush();
            $this->addFlash(type:'success',message:'Suppression effectuée !');
            return $this->redirectToRoute('ingredient.index');
        }

    }
}
