<?php
/**
 * Recipe type.
 */
namespace AppBundle\Form;

use AppBundle\Entity\Recipe;
use AppBundle\Entity\Ingredient;
use AppBundle\Entity\Category;
use AppBundle\Form\PhotoType;
use AppBundle\Repository\IngredientsRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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
                    'class' => 'input-field',
                ],
            ]
        );
        $builder->add(
            'description',
            TextareaType::class,
            [
                'label' => 'label.description',
                'required' => true,
                'attr' => [
                    'max_length' => 10000,
                    'class' => 'materialize-textarea',
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
                'multiple' => true,
                'attr' => [
                    'class' => 'mdc-chip-set', ],
                'choice_attr' => [
                    'class' => 'mdc-chip', ],
            ]
        );

        $builder->add(
            'category',
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

        $builder->add(
            'photos',
            CollectionType::class,
            array(
                'entry_type' => PhotoType::class,
                'entry_options' => array('label' => false),
                'allow_add' => true,
            )
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
                'validation_groups' => 'recipe-default',
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
