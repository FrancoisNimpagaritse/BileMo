<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
        $data = $serializer->serialize($clients, 'json');
        return new Response($data,
            Response::HTTP_OK
        );
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

        return new Response(
            $serializer->serialize($client, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    /**
     * Permet de consulter les détails d'un client
     * 
     * @Route("/api/clients/{id}", name="clients_show", methods={"GET"})
     */
    public function getClientAction(Client $client, SerializerInterface $serializer): Response
    {
        return new Response(
            $serializer->serialize($client, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }
    
    /**
     * Permet d'ajouter une ressource de type clients
     * 
     * @Route("/api/clients/{id}", name="clients_update", methods={"PUT"})
     */
    public function putClientAction(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $client = $serializer->deserialize($request->getContent(), Client::class, 'json');
        
        $manager->flush();

        $jsonResponse = new Response(
            $serializer->serialize($client, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );

        return $jsonResponse;
    }

    /**
     * Permet de supprimer une ressource de type clients
     * 
     * @Route("/api/clients/{id}", name="clients_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $client = $serializer->deserialize($request->getContent(), Client::class, 'json');
        //vérifier ko ata users afise avant
        $manager->remove($client);
        $manager->flush();

        $jsonResponse = new Response(
            null,
            Response::HTTP_OK,
            [],
            true
        );

        return $jsonResponse;
    }
}
