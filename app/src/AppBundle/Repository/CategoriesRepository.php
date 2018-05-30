<?php
/**
 * Categories repository.
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use AppBundle\Entity\Category;


/**
 * Class CategoriesRepository.
 *
 * @package AppBundle\Repository
 */
class CategoriesRepository extends EntityRepository
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
        return isset($this->categories[$id]) && count($this->categories)
            ? $this->categories[$id] : null;
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
        $paginator->setMaxPerPage(Category::NUM_ITEMS);
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
        return $this->createQueryBuilder('category');
    }

    /**
     * Save entity.
     *
     * @param \AppBundle\Entity\Category $category Category entity
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Category $category)
    {
        $this->_em->persist($category);
        $this->_em->flush($category);
    }

    /**
     * Delete entity.
     *
     * @param \AppBundle\Entity\Category $category Category entity
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Category $category)
    {
        $this->_em->remove($category);
        $this->_em->flush();
    }
}