<?php
/**
 * Data fixtures for categories.
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadCategoryData.
 */
class LoadCategoryData extends Fixture
{
    /**
     * Load categories.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            'breakfast',
            'dinner',
            'supper',
            'lunch',
            'bbq',
        ];

        foreach ($data as $item) {
            $category = new Category();
            $category->setName($item);
            $manager->persist($category);
        }
        $manager->flush();
    }
}
