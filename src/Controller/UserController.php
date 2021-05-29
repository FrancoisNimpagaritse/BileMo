<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class UserController extends AbstractController
{
    /**
     * Permet de consulter la liste des utilisateurs inscrits liés à un client donné
     * 
     * @Route("/api/clients/{id}/users", name="clients_users", methods={"GET"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Permet de récuper les utilisateurs liés à un client",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class))
     *     )
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function getUsersAction($id, UserRepository $repoUser, ClientRepository $repoClient,
    SerializerInterface $serializer, PaginatorInterface $paginator, Request $request): Response
    {
        $client = $repoClient->findOneById($id);

        if(!$client) {
            throw new NotFoundHttpException("Ce client n'existe pas !");
        }

        $users = $repoUser->findBy(['client' => $client->getId()]);
        $usersList = $paginator->paginate($users, $request->query->getInt('page', 1), 5);

        $data = $serializer->serialize($usersList, 'json');

        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * Permet de consulter les détails d'un utilisateur lié à un client
     * 
     * @Route("/api/clients/{client_id}/users/{id}", name="users_show", methods={"GET"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Permet de récuper les détails d'un utilisateur liés à un client",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class))
     *     )
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
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
     * 
     * @OA\Response(
     *     response=201,
     *     description="Permet de créer un utilisateur lié à un client",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class))
     *     )
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
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
     * 
     * @OA\Response(
     *     response=200,
     *     description="Permet de modifier un utilisateur lié à un client",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class))
     *     )
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
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
     * 
     * @OA\Response(
     *     response=200,
     *     description="Permet de supprimer un utilisateur lié à un client",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=User::class))
     *     )
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function deleteUsersAction($id, $client_id, ClientRepository $clientRepo, UserRepository $userRepo, 
     EntityManagerInterface $manager): Response
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
