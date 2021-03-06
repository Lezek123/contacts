<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ContactRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContactRepository extends EntityRepository
{
    /**
     * Save
     *
     * @param \AppBundle\Entity\Contact $entity
     * @return bool
     */
    public function save(Contact $entity)
    {
        try {
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
        } catch (\PDOException $e) {
            return false;
        }

        return true;
    }

    /**
     * Delete
     *
     * @param \AppBundle\Entity\Contact $entity
     * @param bool                      $hard
     * @return bool
     */
    public function delete(Contact $entity, $hard = false)
    {
        if ($hard) {
            try {
                $this->getEntityManager()->remove($entity);
                $this->getEntityManager()->flush();
            } catch (\Exception $e) {
                return false;
            }
        } else {
            try {
                $entity->setIsDeleted(true);
                $this->save($entity);
            } catch (\PDOException $e) {
                return false;
            }
        }

        return true;
    }

    /**
     * Pagination qb
     * @param bool $debug
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getPaginationQB($debug = false)
    {
        if ($debug) {
            return $this->createQueryBuilder('c')->orderBy('c.id');
        } else {
            return $this->createQueryBuilder('c')->where('c.isDeleted = false')->orderBy('c.id');
        }
    }
}
