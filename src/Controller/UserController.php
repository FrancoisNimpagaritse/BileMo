<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * Permet de consulter la liste des utilisateurs inscrits liés à un client donné
     * 
     * @Route("/api/clients/{id}/users", name="clients_users_show", methods={"GET"})
     */
    public function getUsersAction($id, UserRepository $repoUser, ClientRepository $repoClient, SerializerInterface $serializer): Response
    {
        $client = $repoClient->findOneById($id);
        $users = $repoUser->findBy(['client' => $client->getId()]);
        
        return new Response(
            $serializer->serialize($users, 'json', SerializationContext::create()->setGroups(array('list'))),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * Permet de consulter les détails d'un utilisateur lié à un client
     * 
     * @Route("/api/clients/{client_id}/users/{id}", name="users_show", methods={"GET"})
     */
    public function getUserAction(UserRepository $repoUser, ClientRepository $repoClient, $client_id, $id, SerializerInterface $serializer): Response
    {
        $client = $repoClient->findOneBy(['id' => $client_id]);
        
        $user = $repoUser->findOneBy(['client' => $client, 'id' => $id]);
                
        $response = new Response(
            $serializer->serialize($user, 'json', SerializationContext::create()->setGroups(array('list'))),
            Response::HTTP_OK,
            [],
            true
        );

        return $response;
    }

    /**
     * Permet d'ajouter une ressource utilisateur lié à un client
     * 
     * @Route("/api/clients/{id}/users", name="clients_users_add", methods={"POST"})
     */
    public function postUsersAction(Request $request, Client $client, SerializerInterface $serializer, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager): Response
    {     
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $hash= $encoder->encodePassword($user, $user->getPassword());

        $user->setClient($client);
        $user->setPassword($hash);

        $manager->persist($user);
        $manager->flush();

        return new Response(
            $serializer->serialize($user, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    /**
     * Permet d'éditer une ressource utilisateur lié à un client
     * 
     * @Route("/api/clients/{client_id}/users/{id}", name="clients_users_edit", methods={"PUT"})
     */
    public function putUsersAction(Request $request, UserRepository $repoUser,ClientRepository $repoClient, $client_id, $id, SerializerInterface $serializer, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager): Response
    {   
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        
        $hash= $encoder->encodePassword($user, $user->getPassword());
        
        $user->setPassword($hash);

        $manager->persist($user);
        $manager->flush();

        return new Response(
            $serializer->serialize($user, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    /**
     * Permet de supprimer un utilisateur ajouté par un client
     * 
     * @Route("/api/clients/{client_id}/users/{id}", name="clients_users_delete", methods={"DELETE"})
     */
    public function deleteUsersAction(Request $request, Client $client, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {        
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        
        $manager->remove($user);
        $manager->flush();

        return new Response(
            "",
            204,
            []        
        );
    }

}
