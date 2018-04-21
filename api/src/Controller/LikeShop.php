<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LikeShop
{
    private $requestStack;
    private $entityManager;
    private $userManager;
    private $tokenStorage;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, UserManager $userManager, TokenStorageInterface $tokenStorage)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $em;
        $this->userManager = $userManager;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke()
    {
        $request = $this->requestStack->getCurrentRequest();
        $shopId = $request->attributes->get('shopId');
        $shop = $this->entityManager->getRepository('App\Entity\Shop')->find($shopId);
        if(!$shop)
            return new JsonResponse(['message' => 'Shop not found'], 404);

        $user = $this->tokenStorage->getToken()->getUser();
        $this->userManager->addPreferredShop($user, $shop);

        return new JsonResponse(['message' => 'Shop liked']);
    }
}
