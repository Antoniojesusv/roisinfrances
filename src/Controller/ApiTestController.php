<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiTestController extends AbstractController
{
    /**
     * @Route("/api/test", name="api_test", methods={"GET"})
     */
    public function index(Request $request, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
        $decodedJwtToken = $jwtManager->decode($tokenStorageInterface->getToken());

        $data = [
            'status' => '200',
            'message' => 'the user has been successfully logged in with token',
        ];

        return $this->json($data, $status = 200, $headers = [], $context = []);
    }
}
