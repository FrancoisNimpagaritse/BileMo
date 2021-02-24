<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * Permet de consulter la liste des utilisateurs liés à un client donné
     * 
     * @Route("/api/clients/{id}/users", name="clients_users_show", methods={"GET"})
     */
    public function getUsersAction($id, UserRepository $repoUser, ClientRepository $repoClient, SerializerInterface $serializer): Response
    {
        $client = $repoClient->findOneById($id);
        $users = $repoUser->findBy(['client' => $client->getId()]);
        
        $jsonResponse = new JsonResponse(
            $serializer->serialize($users, 'json'),
            JsonResponse::HTTP_OK,
            [],
            true
        );

        return $jsonResponse;
    }
}
