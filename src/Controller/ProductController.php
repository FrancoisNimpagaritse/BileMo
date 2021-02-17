<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductController extends AbstractController
{
    /**
     * Permet de récuper la liste des produits en BD et d'envoyer le résultat aux clients
     * @Route("/products", name="products")
     */
    public function listProducts(ProductRepository $repo, SerializerInterface $serializer): Response
    {
        $products = $repo->findAll();
        //$data = $serializer->serialize($products, "json");
        
        //$response = new Response($data);
        //$response->headers->set('Content-Type', 'application/json');

        return new JsonResponse($products);
    }

    /**
     * Permet de récuper les détailsd'un produit en BD et d'envoyer le résultat aux clients
     * @Route("/products/{id}", name="products_show")
     */
    public function showProduct(Product $product, SerializerInterface $serializer): Response
    {
        $data = $serializer->serialize($product, "json");
        
        $response = new Response($data);
        $response->headers->set("Content-Type", "application/json");

        return $response;
    }
}
