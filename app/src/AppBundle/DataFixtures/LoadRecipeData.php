<?php
/**
 * Data fixtures for recipes.
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadRecipeData.
 */
class LoadRecipeData extends Fixture
{
    /**
     * Load recipes.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            'framework',
            'Git',
            'IDE',
            'PHP',
            'Symfony',
            'templates',
            'tools',
            'tutorials',
            'Twig',
            'VCS',
        ];

        foreach ($data as $item) {
            $recipe = new Recipe();
            $recipe->setTitle($item);
            $manager->persist($recipe);
        }
        $manager->flush();
    }
}