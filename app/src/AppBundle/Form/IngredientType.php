<?php
/**
 * Ingredient type.
 */
namespace AppBundle\Form;

use AppBundle\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class IngredientType.
 */
class IngredientType extends AbstractType
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
            'name',
            TextType::class,
            [
                'label' => 'label.name',
                'required' => true,
                'attr' => [
                    'max_length' => 128,
                ],
            ]
        );
//        $builder->add(
//            'quantity',
//            TextType::class,
//            [
//                'label' => 'label.quantity',
//                'required' => true,
//                'attr' => [
//                    'max_length' => 128,
//                ],
//            ]
//        );
//
//        $builder->add(
//            'unit',
//            ChoiceType::class,
//            [
//                'label' => 'label.unit',
//                'required' => true,
//                'choices'  => array(
//                    'x' => 'x',
//                    'y' => 'y',
//                    'z' => 'z',
//                ),
//                'attr' => [
//                    'class' => 'dropdown-content'
//                ],
//            ]
//        );


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
                'data_class' => Ingredient::class,
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
        return 'ingredient';
    }
}