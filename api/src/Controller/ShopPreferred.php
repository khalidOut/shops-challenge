<?php

namespace App\Controller;

use App\Entity\Shop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ShopPreferred
{
    private $entityManager;
    private $tokenStorage;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $shops = $this->entityManager->getRepository('App\Entity\Shop')->findPreferred($user->getId());

        return $shops;
    }
}
