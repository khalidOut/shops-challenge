<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShopNearby
{
    private $requestStack;
    private $entityManager;
    private $tokenStorage;
    private $validator;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, TokenStorageInterface $tokenStorage, Validator $validator)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $em;
        $this->tokenStorage = $tokenStorage;
        $this->validator = $validator;
    }

    public function __invoke()
    {
        $location = $this->getLocationFromQuery();
        if(!$location)
            $location = ['latitude'=>0, 'longitude'=>0];

        $user = $this->tokenStorage->getToken()->getUser();
        $shops = $this->entityManager->getRepository('App\Entity\Shop')->findNearby($user->getId(), $location);

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
