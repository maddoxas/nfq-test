<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\User;
use AppBundle\Entity\Group;

class GroupRepository extends EntityRepository
{
    public function getGroups()
    {   
        return $this
          ->getEntityManager()
          ->createQueryBuilder()
          ->select('g')
          ->from('AppBundle:Group', 'g')
          ->where("g.deleted = 0")
          ->getQuery()
          ->getResult()
        ;
    }

    public function getGroupById(int $id)
    {   
        return $this
          ->getEntityManager()
          ->createQueryBuilder()
          ->select('g')
          ->from('AppBundle:Group', 'g')
          ->where("g.deleted = 0 AND g.id = :id")
          ->setParameter('id', $id)
          ->getQuery()
          ->getSingleResult()
        ;
    }
}