<?php

namespace App\Repository;

use App\Entity\Shop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Shop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shop[]    findAll()
 * @method Shop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Shop::class);
    }

   /**
    * @param int $userId Id of the user
    * @param array $location Latitude, Longitude
    * @return Shop[] Returns an array of Shop objects ordered by distance
    */
    public function findNearby($userId, $location)
    {
        $parameters['userId'] = $userId;
        $parameters['radian']=pi()/180;
        $parameters['latToRadian']=deg2rad($location['latitude']);
        $parameters['lonToRadian']=deg2rad($location['longitude']);
        $parameters['R']=6371;
        $distance="((ACOS(SIN(:latToRadian) * SIN(s.latitude * :radian) + " .
            "COS(:latToRadian) * COS(s.latitude * :radian) * " .
            "COS((s.longitude * :radian) - :lonToRadian)))  * :R) AS distance";

        return $this->createQueryBuilder('s')
            ->select('s AS shop')
            ->addSelect($distance)
            ->leftJoin('s.preferrerUsers u', 'WITH u.id=:userId')
            ->where('u.id IS NULL')
            ->setParameters($parameters)
            ->orderBy('distance', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

   /**
    * @param int $userId Id of the user
    * @return Shop[] Returns an array of user's preferred Shop
    */
    public function findPreferred($userId)
    {
        return $this->createQueryBuilder('s')
            ->join('s.preferrerUsers u', 'WITH u.id=:userId')
            ->setParameter('userId', $userId)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}
