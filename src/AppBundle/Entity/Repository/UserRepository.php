<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

use AppBundle\Entity\User;
use AppBundle\Entity\Group;

class UserRepository extends EntityRepository
{
	public function getUsersWhoAreNotAddedToGroup(Group $group)
	{
		  return $this
		      ->getEntityManager()
          ->createQueryBuilder()
          ->select('u')
          ->from('AppBundle:User', 'u')
           ->where("u.deleted = 0 AND :Group NOT MEMBER OF u.groups")
           ->setParameter("Group", $group)
           ->getQuery()
           ->getResult()
      ;
	}

	public function getUserById(int $id)
    {   
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('u')
            ->from('AppBundle:User', 'u')
             ->where("u.deleted = 0 AND u.id = :id")
             ->setParameter('id', $id)
   		       ->getQuery()
   		       ->getSingleResult()
        ;
    }
}