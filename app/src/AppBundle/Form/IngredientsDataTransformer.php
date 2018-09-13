<?php
/**
 * Ingredients data transformer.
 */
namespace AppBundle\Form;

use AppBundle\Entity\Ingredient;
use AppBundle\Repository\IngredientsRepository;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class IngredientsDataTransformer.
 */
class IngredientsDataTransformer implements DataTransformerInterface
{
    /**
     * Ingredient repository.
     *
     * @var IngredientsRepository|null $ingredientsRepository
     */
    protected $ingredientsRepository = null;

    /**
     * IngredientsDataTransformer constructor.
     *
     * @param IngredientsRepository $ingredientsRepository Ingredients repository
     */
    public function __construct(IngredientsRepository $ingredientsRepository)
    {
        $this->ingredientsRepository = $ingredientsRepository;
    }

    /**
     * Transform array of ingredients to string of names.
     *
     * @param array $ingredients Ingredients entity array
     *
     * @return string Result
     */
    public function transform($ingredients)
    {
        if (null == $ingredients) {
            return '';
        }

        $ingredientNames = [];

        foreach ($ingredients as $ingredient) {
            $ingredientNames[] = $ingredient->getName();
        }

        return implode(',', $ingredientNames);
    }

    /**
     * Transform string of ingredient names into array of Ingredient entities.
     *
     * @param string $string String of Ingredient names
     *
     * @return array Result
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reverseTransform($string)
    {
        $ingredientNames = explode(',', $string);

        $ingredients = [];
        foreach ($ingredientNames as $ingredientName) {
            if (trim($ingredientName) !== '') {
                $ingredient = $this->ingredientsRepository->findOneByName($ingredientName);
                if (null == $ingredient) {
                    $ingredient = new Ingredient();
                    $ingredient->setName($ingredientName);
                    $this->ingredientsRepository->save($ingredient);
                }
                $ingredients[] = $ingredient;
            }
        }

        return $ingredients;
    }
}