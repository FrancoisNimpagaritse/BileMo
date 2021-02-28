<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializerInterface as SerializerSerializerInterface;
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
    public function getProductsAction(ProductRepository $repo, SerializerInterface $serializer): Response
    {
        $products = $repo->findAll();
        $data = $serializer->serialize($products, 'json');

        return new Response(
            $data,
            Response::HTTP_OK
        );
    }

    /**
     * Permet de récuper les détails d'un produit en BD et d'envoyer le résultat aux clients
     * 
     * @Route("/api/products/{id}", name="products_show", methods={"GET"})
     */
    public function getProductAction(Product $product, SerializerInterface $serializer): Response
    {
        $data = $serializer->serialize($product, 'json');
        return new Response($data,
            Response::HTTP_OK
        );
    }
}
