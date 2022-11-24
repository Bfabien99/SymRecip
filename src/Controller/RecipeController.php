<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    #[Route('/recipe', name: 'recipe.index')]
    public function index(RecipeRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $all_recipe = $repository->findAll();
        $recipes = $paginator->paginate(
            $all_recipe,
            $request->query->getInt('page', 1),10
        );
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recipe/nouveau', name: 'recipe.new', methods:['GET', 'POST'])]
    public function new(ManagerRegistry $doctrine,Request $request)
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $recipe = $form->getData();

            $doctrine->getManager()->persist($recipe);
            $doctrine->getManager()->flush();

            $this->addFlash(type:'success',message:'Nouvelle recette ajouté !');
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('recipe/new.html.twig',['form'=>$form->createView()]);
    }

    #[Route('/recipe/editer/{id}', name: 'recipe.edit')]
    public function edit(Recipe $recipe = null, ManagerRegistry $doctrine,Request $request)
    {
        if(!$recipe){
            $this->addFlash(type:'error',message:'Recette inconnu !');
            return $this->redirectToRoute('recipe.index');
        }

        $form = $this->createForm(RecipeType::class,$recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
 
             $doctrine->getManager()->persist($recipe);
             $doctrine->getManager()->flush();
 
             $this->addFlash(type:'success',message:'Modifications effectuées !');
             return $this->redirectToRoute('recipe.index');
         }

        return $this->render('recipe/edit.html.twig',['form'=>$form->createView()]);
    }

    #[Route('/recipe/delete/{id}', name: 'recipe.delete')]
    public function delete(Recipe $recipe = null, ManagerRegistry $doctrine)
    {
        if(!$recipe){
            $this->addFlash(type:'error',message:'Recette inconnu !');
            return $this->redirectToRoute('recipe.index');
        }else{
            $doctrine->getManager()->remove($recipe);
            $doctrine->getManager()->flush();
            $this->addFlash(type:'success',message:'Suppression effectuée !');
            return $this->redirectToRoute('recipe.index');
        }
    }

    #[Route('/recipe/{id}', name: 'recipe.show')]
    public function show(Recipe $recipe = null)
    {
        if(!$recipe){
            $this->addFlash(type:'error',message:'Recette inconnu !');
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('recipe/show.html.twig',['recipe'=>$recipe]);
    }
}
