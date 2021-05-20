<?php

namespace App\Controller;

use Exception;
use App\Entity\Client;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ClientController extends AbstractController
{
    /**
     * Permet de récuper la liste des clients
     * 
     * @Route("/api/clients", name="clients_index", methods={"GET"})
     */
    public function getClientsAction(ClientRepository $repo, SerializerInterface $serializer, CacheInterface $cache): Response
    {
        $clients = $cache->get('resultClients', function(ItemInterface $item)  use($repo){
            $item->expiresAfter(3600);

            return $repo->findAll();
        });

        $data = $serializer->serialize($clients, 'json');

        return  new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
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

        $data = $serializer->serialize($client, 'json');

        return new Response($data, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    /**
     * Permet de consulter les détails d'un client
     * 
     * @Route("/api/clients/{id}", name="clients_show", methods={"GET"})
     */
    public function getClientAction($id, ClientRepository $repo, SerializerInterface $serializer): Response
    {
        $client = $repo->findOneBy(['id' => $id]);

        if (!$client) {
            throw new NotFoundHttpException("Ce client n'existe pas !");
        }

        $data = $serializer->serialize($client, 'json');

        return  new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
    
    /**
     * Permet d'ajouter une ressource de type clients
     * 
     * @Route("/api/clients/{id}", name="clients_update", methods={"PUT"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function putClientAction($id, ClientRepository $repo, Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $client = $repo->findOneBy(['id' => $id]);
        
        if (!$client) {
            throw new NotFoundHttpException("Ce client n'existe pas !");
        }

        if ($client != $this->getUser()->getClient()) {
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

        $data = $serializer->serialize($client, 'json');

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * Permet de supprimer une ressource de type clients
     * 
     * @Route("/api/clients/{id}", name="clients_delete", methods={"DELETE"})
     */
    public function deleteAction($id, ClientRepository $repo, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $client = $repo->findOneBy(['id' => $id]);

        if (!$client) {
            throw new NotFoundHttpException("Ce client n'existe pas !");
        }
       
       if (count($client->getUsers()) > 0) {
           throw new AccessDeniedException("Le client ne peut pas être supprimé car il a des utilisateurs !");
       }
        $manager->remove($client);
        $manager->flush();

        return new Response( null, Response::HTTP_OK, []);
    }
}
