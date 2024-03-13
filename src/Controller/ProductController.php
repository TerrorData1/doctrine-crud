<?php
// src/Controller/ProductController.php
namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    private SerializerInterface $serializer;
    private ProductsService $productsService;

    public function __construct(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $this->productsService = new ProductsService($entityManager);
        $this->serializer = $serializer;
    }

    #[Route('/api/products', methods: ['POST'])]
    public function createProduct(#[MapRequestPayload] Product $product): Response
    {
        return new Response($this->serializer->serialize($this->productsService->createProduct($product), 'json'));
    }

    #[Route('/api/products', methods: ['GET'])]
    public function getProducts(): Response
    {
        return new Response($this->serializer->serialize($this->productsService->getAllProduct(), 'json'));
    }

    #[Route('/api/products/{id}', methods: ['GET'])]
    public function getProduct(int $id): Response
    {
        return new Response($this->serializer->serialize($this->productsService->getProductById($id), 'json'));
    }

    #[Route('/api/products/{id}', methods: ['PUT'])]
    public function putProduct(int $id, #[MapRequestPayload] Product $product): Response
    {
        $message = $this->productsService->putProduct($id, $product);
        return new Response($message);
    }

    #[Route('/api/products/{id}', methods: ['PATCH'])]
    public function patchProduct(int $id, #[MapRequestPayload] Product $product): Response
    {
        $message = $this->productsService->putProduct($id, $product);
        return new Response($message);
    }

    #[Route('/api/products/{id}', methods: ['DELETE'])]
    public function deleteProduct(int $id): Response
    {
        $this->productsService->deleteProduct($this->productsService->getProductById($id));
        return new Response();
    }
}
