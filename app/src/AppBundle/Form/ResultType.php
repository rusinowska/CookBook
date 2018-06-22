<?php
/**
 * Result type.
 */

namespace AppBundle\Form;

use AppBundle\Entity\Recipe;
use AppBundle\Entity\Ingredient;
use AppBundle\Repository\RecipesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ResultType.
 */
class ResultType extends AbstractType
{

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder From builder
     * @param array                                        $options Options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'ingredients',
            EntityType::class,
            [
                'class' => Ingredient::class,
                'choice_label' => function ($ingredient) {
                    return $ingredient->getName();
                },
                'label' => 'label.ingredient',
                'required' => false,
                'expanded' => true,
                'multiple' => true
            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver Option resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Recipe::class,
                'validation_groups' => 'recipe-search',

            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return null|string Form name
     */
    public function getBlockPrefix()
    {
        return 'recipe';
    }
}
