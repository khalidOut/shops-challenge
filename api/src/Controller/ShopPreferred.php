<?php

namespace App\Controller;

use App\Entity\Shop;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShopPreferred
{
    private $requestStack;
    private $entityManager;
    private $tokenStorage;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $request = $this->requestStack->getCurrentRequest();
        $page = $request->query->get('page') ? $request->query->get('page') : 1;
        if(!$page)
            $page = 1;

        $shops = $this->entityManager->getRepository('App\Entity\Shop')->findPreferred($user->getId(), $page);

        return new Paginator($shops);
    }
}
