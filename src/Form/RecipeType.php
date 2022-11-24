<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',options:[
                'label'=>'Titre de la recette'
            ])
            ->add('time',options:[
                'label'=>'Temps requis (en minutes)'
            ])
            ->add('nbPeople',options:[
                'label'=>'Nombre de personne'
            ])
            ->add('difficulty',options:[
                'label'=>'Difficulté (1 à 5)'
            ])
            ->add('description')
            ->add('price',MoneyType::class,[
                'label'=>'Prix'
            ])
            ->add('isFavorite',options:[
                'label'=>'Marquer comme favoris'
            ])
            ->add('ingredients',EntityType::class,[
                'expanded' => false,
                'multiple' => true,
                'attr' =>[
                    'class' => 'js-example-basic-single'
                ],
                'class'=>Ingredient::class,
                'label'=>'Ingredient(s) requi(s)'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter recette',
                'attr' =>[
                    'class'=>'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
