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
     * 
     * @Route("/api/products", name="products", methods={"GET"})
     */
    public function getProductsAction(ProductRepository $repo): Response
    {
        $products = $repo->findAll();
        
        return new JsonResponse($products);
    }

    /**
     * Permet de récuper les détails d'un produit en BD et d'envoyer le résultat aux clients
     * 
     * @Route("/api/products/{id}", name="products_show", methods={"GET"})
     */
    public function getProductAction(Product $product, SerializerInterface $serializer): Response
    {
        return new JsonResponse($product);
    }
}
