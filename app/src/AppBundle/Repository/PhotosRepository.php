<?php
/**
 * Photos repository.
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use AppBundle\Entity\Photo;

/**
 * Class PhotosRepository.
 *
 * @package AppBundle\Repository
 */
class PhotosRepository extends EntityRepository
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
        return isset($this->photos[$id]) && count($this->photos)
            ? $this->photos[$id] : null;
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
        $paginator->setMaxPerPage(Photo::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }



    /**
     * Save entity.
     *
     * @param \AppBundle\Entity\Photo $photo Photo entity
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save($photo)
    {
        $this->_em->persist($photo);
        $this->_em->flush($photo);
    }

    /**
     * Delete entity
     *
     * @param \AppBundle\Entity\Photo $photo Photo entity
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Photo $photo)
    {
        $this->_em->remove($photo);
        $this->_em->flush();
    }

    /**
     * Query all entities.
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function queryAll()
    {
        return $this->createQueryBuilder('photo');
    }
}
