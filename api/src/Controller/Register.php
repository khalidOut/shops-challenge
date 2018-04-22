<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserManager;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface as JWTManager;

use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class Register
{
    private $userManager;
    private $eventDispatcher;
    private $jwtManager;

    public function __construct(UserManager $userManager, EventDispatcherInterface $eventDispatcher, JWTManager $jwtManager)
    {
        $this->userManager = $userManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->jwtManager = $jwtManager;
    }

    public function __invoke(User $data)
    {
        try{
            $user = $this->userManager->create($data->getEmail(), $data->getPassword());
        }
        catch(RuntimeException $e) {
            return new JsonResponse(['message' => $e->getMessage()], 422);
        }

        $jwt = $this->jwtManager->create($user);
        $response = new JsonResponse();
        $event = new AuthenticationSuccessEvent(array('token' => $jwt), $user, $response);
        $this->eventDispatcher->dispatch(Events::AUTHENTICATION_SUCCESS, $event);
        $response->setData($event->getData());

        return $response;
    }
}
