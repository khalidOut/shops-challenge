<?php

namespace App\Controller;

use App\Entity\Shop;
use App\Utils\Validator;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShopNearby
{
    private $requestStack;
    private $entityManager;
    private $tokenStorage;
    private $validator;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em,
            TokenStorageInterface $tokenStorage, Validator $validator)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $em;
        $this->tokenStorage = $tokenStorage;
        $this->validator = $validator;
    }

    public function __invoke()
    {
        $request = $this->requestStack->getCurrentRequest();

        $location = $this->getLocationFromQuery($request);
        if(!$location)
            $location = ['latitude'=>0, 'longitude'=>0];

        $page = $request->query->get('page') ? $request->query->get('page') : 1;
        if(!$page)
            $page = 1;

        $user = $this->tokenStorage->getToken()->getUser();
        $shops = $this->entityManager->getRepository('App\Entity\Shop')->findNearby($user->getId(), $location, $page);

        return new Paginator($shops);
    }

    private function getLocationFromQuery($request) {
        $latitude = $request->query->get('latitude');
        $longitude = $request->query->get('longitude');

        if($this->validator->validateLatLong($latitude, $longitude))
            return ['latitude'=>$latitude, 'longitude'=>$longitude];

        return null;
    }
}
