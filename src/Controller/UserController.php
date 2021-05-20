<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Exception\ResourceValidationException;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends AbstractController
{
    /**
     * Permet de consulter la liste des utilisateurs inscrits liés à un client donné
     * 
     * @Route("/api/clients/{id}/users", name="clients_users", methods={"GET"})
     */
    public function getUsersAction($id, UserRepository $repoUser, ClientRepository $repoClient, SerializerInterface $serializer): Response
    {
        $client = $repoClient->findOneById($id);

        if(!$client) {
            throw new NotFoundHttpException("Ce client n'existe pas !");
        }

        $users = $repoUser->findBy(['client' => $client->getId()]);
        $data = $serializer->serialize($users, 'json');

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * Permet de consulter les détails d'un utilisateur lié à un client
     * 
     * @Route("/api/clients/{client_id}/users/{id}", name="users_show", methods={"GET"})
     */
    public function getUserAction(UserRepository $repoUser, ClientRepository $repoClient, $client_id, $id, SerializerInterface $serializer): Response
    {
        $client = $repoClient->findOneBy(['id' => $client_id]);
        
        if(!$client) {
            throw new NotFoundHttpException("Ce client n'existe pas !");
        }

        $user = $repoUser->findOneBy(['client' => $client, 'id' => $id]);
        
        if(!$user) {
            throw new NotFoundHttpException("Cet utilisateur n'existe pas !");
        }

        $data = $serializer->serialize($user, 'json');

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
}

    /**
     * Permet d'ajouter une ressource utilisateur lié à un client
     * 
     * @Route("/api/clients/{id}/users", name="clients_users_add", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function postUsersAction(Request $request, Client $client, SerializerInterface $serializer, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager, ValidatorInterface $validator): Response
    {     
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        $hash= $encoder->encodePassword($user, $user->getPassword());

        if($client != $this->getUser()->getClient()){
           throw new AccessDeniedException("Vous n'avez pas le droit de créer cette ressource !");
        }
        
        $user->setClient($client);
        $user->setPassword($hash);
        
        $errors = $validator->validate($user);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new Response($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }
        
        $manager->persist($user);
        $manager->flush();

        $data = $serializer->serialize($user, 'json');

        return new Response($data, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    /**
     * Permet d'éditer une ressource utilisateur lié à un client
     * 
     * @Route("/api/clients/{client_id}/users/{id}", name="clients_users_edit", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     * 
     */
    public function putUsersAction(Request $request, UserRepository $repoUser,ClientRepository $repoClient,
     $client_id, $id, SerializerInterface $serializer, UserPasswordEncoderInterface $encoder, 
     EntityManagerInterface $manager, ValidatorInterface $validator): Response
    {   
        $user = $repoUser->find($id);
        
        if(!$user) {
            throw new NotFoundHttpException("Cet utilisateur n'existe pas !");
        }
        
        $client = $repoClient->find($client_id);
        
        if($client != $this->getUser()->getClient()){
            throw new AccessDeniedException("Vous n'avez pas le droit à cette ressource !");
         }
        
        if(!$client) {
            throw new NotFoundHttpException("Ce client n'existe pas !");
        }
        
        if($user != $this->getUser() || $this->getUser()->getRole()== "ROLE_ADMIN"){
            throw new AccessDeniedException("Vous n'avez pas le droit de faire cette action !");
        }

        $payload= json_decode($request->getContent(), true);
        
        foreach($payload as $key => $value){
            $setter = 'set'. ucfirst($key);
            if(method_exists($user, $setter)) {
                $user->{$setter}($value);
            }
        }
       

        $hash= $encoder->encodePassword($user, $user->getPassword());
        
        $user->setPassword($hash);

        $errors = $validator->validate($user);
        if (count($errors)) {
            $errorsJson = $serializer->serialize($errors, 'json');
            return new Response($errorsJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $manager->persist($user);
        $manager->flush();

        $data = $serializer->serialize($user, 'json');

        return new Response($data, Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
}

    /**
     * Permet de supprimer un utilisateur ajouté par un client
     * 
     * @Route("/api/clients/{client_id}/users/{id}", name="clients_users_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteUsersAction($id, $client_id, ClientRepository $clientRepo, UserRepository $userRepo, 
    SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        $user = $userRepo->findOneBy(['id' => $id]);

        if (!$user) {
            throw new NotFoundHttpException("Cet utilisateur n'existe pas !");
        }

        $client = $clientRepo->findOneBy(['id' => $client_id]);

        if (!$client) {
            throw new NotFoundHttpException("Ce client n'existe pas !");
        }        

        if ($client != $this->getUser()->getClient()) {
            throw new AccessDeniedException("Vous n'avez pas le droit de faire cette action !");
         }

        $manager->remove($user);
        $manager->flush();

        return new Response('', Response::HTTP_OK, []);
    }

}
