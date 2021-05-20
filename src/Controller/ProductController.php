<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ProductController extends AbstractController
{
    /**
     * Permet de récuper la liste des produits en BD et d'envoyer le résultat aux clients
     * 
     * @Route("/api/products", name="products", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function getProductsAction(ProductRepository $repo, SerializerInterface $serializer, CacheInterface $cache): Response
    {
        $products = $cache->get('resultProducts', function(ItemInterface $item) use($repo){
            $item->expiresAfter(3600);

            return $repo->findAll();
        }); 

        $data = $serializer->serialize($products, 'json');
        
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
     * Permet de récuper les détails d'un produit en BD et d'envoyer le résultat aux clients
     * 
     * @Route("/api/products/{id}", name="products_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function getProductAction(Product $product, SerializerInterface $serializer): Response
    {
        if (!$product) {
            throw new NotFoundHttpException("Ce produit n'existe pas !");
        }
        $data = $serializer->serialize($product, 'json');
        return new Response($data, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
