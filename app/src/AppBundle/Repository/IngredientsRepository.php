<?php
/**
 * Ingredients repository.
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use AppBundle\Entity\Ingredient;


/**
 * Class IngredientsRepository.
 *
 * @package AppBundle\Repository
 */
class IngredientsRepository extends EntityRepository
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
        return isset($this->ingredients[$id]) && count($this->ingredients)
            ? $this->ingredients[$id] : null;
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
        $paginator->setMaxPerPage(Ingredient::NUM_ITEMS);
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
        return $this->createQueryBuilder('ingredient');
    }

    /**
     * Save entity.
     *
     * @param \AppBundle\Entity\Ingredient $ingredient Ingredient entity
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Ingredient $ingredient)
    {
        $this->_em->persist($ingredient);
        $this->_em->flush($ingredient);
    }

    /**
     * Delete entity.
     *
     * @param \AppBundle\Entity\Ingredient $ingredient Ingredient entity
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Ingredient $ingredient)
    {
        $this->_em->remove($ingredient);
        $this->_em->flush();
    }
}