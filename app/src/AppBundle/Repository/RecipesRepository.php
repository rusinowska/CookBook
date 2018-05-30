<?php
/**
 * Recipes repository.
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use AppBundle\Entity\Recipe;


/**
 * Class RecipesRepository.
 *
 * @package AppBundle\Repository
 */
class RecipesRepository extends EntityRepository
{

    /**
     * Find single record by its id.
     *
     * @param integer $id Single record index
     *
     * @return array|null Result
     */
    public function findOneById($id)
    {
        return isset($this->recipes[$id]) && count($this->recipes)
            ? $this->recipes[$id] : null;
    }

    /**
     * Gets all records paginated.
     *
     * @param int $page Page number
     *
     * @return \Pagerfanta\Pagerfanta Result
     */
    public function findAllPaginated($page = 1)
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($this->queryAll(), false));
        $paginator->setMaxPerPage(Recipe::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * Query all entities.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function queryAll()
    {
        return $this->createQueryBuilder('recipe');
    }

    /**
     * Save entity.
     *
     * @param \AppBundle\Entity\Recipe $recipe Recipe entity
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Recipe $recipe)
    {
        $this->_em->persist($recipe);
        $this->_em->flush($recipe);
    }

    /**
     * Delete entity.
     *
     * @param \AppBundle\Entity\Recipe $recipe Recipe entity
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Recipe $recipe)
    {
        $this->_em->remove($recipe);
        $this->_em->flush();
    }
}