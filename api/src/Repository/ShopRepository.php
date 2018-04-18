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
    * @return Shop[] Returns an array of Shop objects ordered by distance
    */
    public function findNearby($location)
    {
        $radian=pi()/180;
        $latToRadian=deg2rad($location['latitude']);
        $lonToRadian=deg2rad($location['longitude']);
        $R=6371;
        $distance="((ACOS( ". "SIN(".$latToRadian.") * SIN(s.latitude * ".$radian.") + " .
            "COS(".$latToRadian.") * COS(s.latitude* ".$radian.") * " .
            "COS(( s.longitude *".$radian.") -".$lonToRadian.")))  * ".$R.") AS distance";

        return $this->createQueryBuilder('s')
            ->select('s AS shop')
            ->addSelect($distance)
            ->orderBy('distance', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}
