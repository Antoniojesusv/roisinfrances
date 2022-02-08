<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/api/register", name="register", methods={"POST"})
     */
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, ManagerRegistry $doctrine)
    {
        $data = $request->toArray();
        $username = $data['username'];
        $password = $data['password'];

        $entityManager = $doctrine->getManager();

        $user = $entityManager->getRepository(User::class)
             ->findOneBy(['username' => $username]);

        if (!is_null($user)) {
            throw new \Exception("The user $username currently exists");
        }

        $user = new User();

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $password
        );

        $user->setUsername($username);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($hashedPassword);
        $entityManager->persist($user);
        $entityManager->flush();

        $data = [
            'status' => '200',
            'message' => "the user $username has benn successfully created",
        ];

        return $this->json($data, $status = 200, $headers = [], $context = []);
    }
}
