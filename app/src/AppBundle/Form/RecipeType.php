<?php
/**
 * Recipe type.
 */
namespace AppBundle\Form;

use AppBundle\Entity\Recipe;
use AppBundle\Entity\Ingredient;
use AppBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RecipeType.
 */
class RecipeType extends AbstractType
{

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder Form builder
     * @param array                                        $options Options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => 'label.title',
                'required' => true,
                'attr' => [
                    'max_length' => 128,
                    'class' => 'input-field'
                ],
            ]
        );
        $builder->add(
            'description',
            TextType::class,
            [
                'label' => 'label.description',
                'required' => true,
                'attr' => [
                    'max_length' => 500,
                    'class' => 'input-field'
                ],
            ]
        );
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
        $builder->add(
            'categories',
            EntityType::class,
            [
                'class' => Category::class,
                'choice_label' => function ($category) {
                    return $category->getName();
                },
                'label' => 'label.category',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver Resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Recipe::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return null|string Result
     */
    public function getBlockPrefix()
    {
        return 'recipe';
    }
}