<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Validator;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $entityManager;
    private $passwordEncoder;
    private $validator;
    private $users;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, Validator $validator, UserRepository $users)
    {
        $this->entityManager = $em;
        $this->passwordEncoder = $encoder;
        $this->validator = $validator;
        $this->users = $users;
    }

    public function create($email, $plainPassword)
    {
        // make sure to validate the user data is correct
        $this->validateUserData($email, $plainPassword);

        // create the user and encode its password
        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER']);

        $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function validateUserData($email, $plainPassword)
    {
        // validate password and email if is not this input means interactive.
        $this->validator->validateEmail($email);
        $this->validator->validatePassword($plainPassword);

        // check if a user with the same email already exists.
        $existingEmail = $this->users->findOneBy(['email' => $email]);

        if (null !== $existingEmail)
        {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" email.', $email));
        }
    }

    public function addPreferredShop($user, $shop)
    {
        if(!$user->getPreferredShops()->contains($shop))
        {
            $user->addPreferredShop($shop);
            $this->entityManager->flush();
        }
    }
}
