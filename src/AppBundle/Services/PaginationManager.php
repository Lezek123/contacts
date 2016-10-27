<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18.10.16
 * Time: 12:49
 */

namespace AppBundle\Services;

use Doctrine\ORM\QueryBuilder;

/**
 * Class PaginationManager
 */
class PaginationManager
{
    protected $er;
    protected $limit;
    protected $page;

    /**
     * PaginationManager constructor.
     * @param QueryBuilder $qb
     * @param int          $page
     * @param int          $limit
     */
    public function __construct(QueryBuilder $qb, $page = 1, $limit = 10)
    {
        $this->qb = $qb;
        $this->limit = $limit;
        if ($page < 1) {
            $page = 1;
        }
        if ($page > $this->getMaxPages()) {
            $page = $this->getMaxPages();
        }
        $this->page = $page;
    }

    /**
     * @return array
     */
    public function getRecords()
    {
        return $this->getQb()->setFirstResult($this->limit * ($this->page -1))->setMaxResults($this->limit)->getQuery()->getResult();
    }

    /**
     * @return int
     */
    public function getMaxPages()
    {
        $allResults = count($this->getQb()->getQuery()->getResult());

        return ceil($allResults/$this->limit);
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return QueryBuilder
     */
    public function getQb()
    {
        return clone $this->qb;
    }
}
