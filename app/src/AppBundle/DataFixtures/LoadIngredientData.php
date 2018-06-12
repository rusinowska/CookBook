<?php
/**
 * Data fixtures for ingredients.
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadIngredientData.
 */
class LoadIngredientData extends Fixture
{
    /**
     * Load recipes.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            'water',
            'apple',
            'pear',
            'milk',
            'sugar',
            'egg',
        ];

        foreach ($data as $item) {
            $ingredient = new Ingredient();
            $ingredient->setName($item);
            $manager->persist($ingredient);
        }
        $manager->flush();
    }
}