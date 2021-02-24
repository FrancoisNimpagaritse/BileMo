<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClientController extends AbstractController
{
    /**
     * Permet de récuper la liste des clients
     * 
     * @Route("/api/clients", name="clients_index", methods={"GET"})
     */
    public function getClientsAction(ClientRepository $repo, SerializerInterface $serializer): Response
    {
        $clients = $repo->findAll();
        
        return new JsonResponse($clients);
    }

    /**
     * Permet d'ajouter une ressource de type clients
     * 
     * @Route("/api/clients", name="clients_add", methods={"POST"})
     */
    public function postClientsAction(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $client = $serializer->deserialize($request->getContent(), Client::class, 'json');
        
        $manager->persist($client);
        $manager->flush();

        return new JsonResponse(
            $serializer->serialize($client, 'json'),
            JsonResponse::HTTP_CREATED,
            [],
            true
        );
    }

    /**
     * Permet de consulter les détails d'un client
     * 
     * @Route("/api/clients/{id}", name="clients_show", methods={"GET"})
     */
    public function getClientAction($id, ClientRepository $repo, SerializerInterface $serializer): Response
    {
        $client = $repo->findOneById($id);
        
        $jsonResponse = new JsonResponse(
            $serializer->serialize($client, 'json'),
            JsonResponse::HTTP_OK,
            [],
            true
        );

        return $jsonResponse;
    }
    
    /**
     * Permet d'ajouter une ressource de type clients
     * 
     * @Route("/api/clients/{id}", name="clients_update", methods={"PUT"})
     */
    public function edit(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $client = $serializer->deserialize($request->getContent(), Client::class, 'json');
        
        //$manager->persist($client);
        $manager->flush();

        $jsonResponse = new JsonResponse(
            $serializer->serialize($client, 'json'),
            JsonResponse::HTTP_CREATED,
            [],
            true
        );

        return $jsonResponse;
    }
}
