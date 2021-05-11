<?php

namespace App\Controller;

use Exception;
use App\Entity\Client;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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

        $data = $serializer->serialize($clients, 'json', SerializationContext::create()->setGroups(array('listFull')));
        
        return new Response($data,
            Response::HTTP_OK
        );
    }

    /**
     * Permet d'ajouter une ressource de type clients
     * 
     * @Route("/api/clients", name="clients_add", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function postClientsAction(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager, ValidatorInterface $validator): Response
    {
        $client = $serializer->deserialize($request->getContent(), Client::class, 'json');
        
        $errors = $validator->validate($client);

        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new Response(
                $errorsJson,
                Response::HTTP_BAD_REQUEST,
                [],
                true
            );
        }

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
        if(!$client) {
            throw new NotFoundHttpException("Ce client n'existe pas !");
        }

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
     * @IsGranted("ROLE_ADMIN")
     */
    public function putClientAction( Client $client, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        if(!$client) {
            throw new NotFoundHttpException("Ce client n'existe pas !");
        }

        if($client != $this->getUser()->getClient()){
            throw new AccessDeniedException("Vous n'avez pas le droit à cette ressource !");
         }

        $payload= json_decode($request->getContent(), true);
        
        foreach($payload as $key => $value){
            $setter = 'set'. ucfirst($key);
            if(method_exists($client, $setter)) {
                $client->{$setter}($value);
            }
        }

        $manager->persist($client);
        $manager->flush();

        $jsonResponse = new Response(
            $serializer->serialize($client, 'json'),
            Response::HTTP_OK,
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
        //vérifier ko ata users afise avant if(count($client->getUsers()) > 0)
        $manager->remove($client);
        $manager->flush();

        $jsonResponse = new Response(
            null,
            Response::HTTP_OK,
            []
        );

        return $jsonResponse;
    }
}
