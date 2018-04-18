<?php

namespace App\Controller;

use App\Entity\Shop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Utils\Validator;

class ShopNearby
{
    private $requestStack;
    private $entityManager;
    private $validator;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, Validator $validator)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $em;
        $this->validator = $validator;
    }

    public function __invoke()
    {
        $location = $this->getLocationFromQuery();
        if(!$location)
            $location = ['latitude'=>0, 'longitude'=>0];

        $shops = $this->entityManager->getRepository('App\Entity\Shop')->findNearby($location);

        return $shops;
    }

    private function getLocationFromQuery() {
        $request = $this->requestStack->getCurrentRequest();
        $latitude = $request->query->get('latitude');
        $longitude = $request->query->get('longitude');

        if($this->validator->validateLatLong($latitude, $longitude))
            return ['latitude'=>$latitude, 'longitude'=>$longitude];

        return null;
    }
}
