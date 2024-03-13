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


    // methode if
    // public function patchProduct(int $id, Product $product): string
    // {
    //     $existingProduct = $this->entityManager->getRepository(Product::class)->find($id);

    //     if (!$existingProduct) {
    //         return "Le produit avec l'ID {$id} n'existe pas.";
    //     }
    // Récupérer les valeurs de tous les champs du produit envoyé
    // $updatedName = $product->getName();
    // $updatedPrice = $product->getPrice();
    // $updatedDescription = $product->getDescription();
    // Mettre à jour uniquement les champs qui sont définis dans la requête
    // if ($updatedName !== null) {
    //     $existingProduct->setName($updatedName);
    // }
    // if ($updatedPrice !== null) {
    // Vérifiez d'abord si $updatedPrice est une valeur numérique
    // if (is_numeric($updatedPrice)) {
    //     $existingProduct->setPrice((float)$updatedPrice);
    // } else {
    // Si ce n'est pas le cas, vous pouvez gérer l'erreur ou attribuer une valeur par défaut
    // Dans cet exemple, je vais simplement attribuer 0 comme prix par défaut
    //             $existingProduct->setPrice(0.0);
    //         }
    //     }
    //     if ($updatedDescription !== null) {
    //         $existingProduct->setDescription($updatedDescription);
    //     }

    //     $this->entityManager->flush();

    //     return "Le produit avec l'ID {$id} a été mis à jour avec succès !";
    // }



    // méthode foreach -->
    public function patchProduct(int $id, Product $product): string
    {
        $existingProduct = $this->entityManager->getRepository(Product::class)->find($id);

        if (!$existingProduct) {
            return "Le produit avec l'ID {$id} n'existe pas.";
        }

        // Récupérer les valeurs de tous les champs du produit envoyé
        $updatedFields = [
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'description' => $product->getDescription()
        ];

        // Mettre à jour uniquement les champs qui sont définis dans la requête
        foreach ($updatedFields as $field => $value) {
            // Vérifier si le champ est défini dans la requête et s'il n'est pas null
            if ($value !== null) {
                // Si le champ est price, vérifiez d'abord s'il est numérique
                if ($field === 'price' && !is_numeric($value)) {
                    // Si le prix n'est pas numérique, attribuez une valeur par défaut
                    $existingProduct->setPrice(0.0);
                } else {
                    // Sinon, mettez à jour le champ dans le produit existant
                    $setter = 'set' . ucfirst($field);
                    $existingProduct->$setter($value);
                }
            }
        }

        $this->entityManager->flush();

        return "Le produit avec l'ID {$id} a été mis à jour avec succès !";
    }


    public function deleteProduct(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}
