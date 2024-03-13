<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductsService
{
    // Declaration de la proprieter entityManager
    private EntityManagerInterface $entityManager;

    // Contructeur de la class TestService qui prend un parametre de type EntityManagerInterface et initialise une proprieter $entityManager
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function createProduct(Product $product)
    {
        $newProduct = new Product();
        $newProduct->setName($product->getName());
        $newProduct->setPrice($product->getPrice());
        $newProduct->setDescription($product->getDescription());

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $this->entityManager->persist($newProduct);

        // actually executes the queries (i.e. the INSERT query)
        $this->entityManager->flush();

        return $newProduct;
    }

    public function getAllProduct(): array
    {
        return $this->entityManager->getRepository(Product::class)->findAll();
    }

    public function getProductById(int $id): Product
    {
        return $this->entityManager->getRepository(Product::class)->find($id);
    }

    public function putProduct(int $id, Product $product): string
    {
        $existingProduct = $this->entityManager->getRepository(Product::class)->find($id);

        if ($existingProduct) {
            $existingProduct
                ->setName($product->getName())
                ->setPrice($product->getPrice())
                ->setDescription($product->getDescription());

            $this->entityManager->flush();

            return "Le produit avec l'ID {$id} a été mis à jour avec succès !";
        } else {
            return "Le produit avec l'ID {$id} n'existe pas.";
        }
    }

    public function patchProduct(int $id, Product $product): string
    {
        $existingProduct = $this->entityManager->getRepository(Product::class)->find($id);

        if ($existingProduct) {
            if ($product->getName() !== null) {
                $existingProduct->setName($product->getName());
            }
            if ($product->getPrice() !== null) {
                $existingProduct->setPrice($product->getPrice());
            }
            if ($product->getDescription() !== null) {
                $existingProduct->setDescription($product->getDescription());
            }

            $this->entityManager->flush();

            return "Le produit avec l'ID {$id} a été mis à jour avec succès !";
        } else {
            return "Le produit avec l'ID {$id} n'existe pas.";
        }
    }

    public function deleteProduct(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}
