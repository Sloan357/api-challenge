<?php

namespace App\Controller;

use App\Manager\ProductsManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    private ProductsManager $productManager;

    public function __construct(ProductsManager $productsManager)
    {
        $this->productManager = $productsManager;
    }

    #[Route('/products', name: 'app_products')]
    public function index(Request $request): JsonResponse
    {
        $category = null;
        $priceLessThan = null;

        if ($request->query->has('category')) {
            $category = $request->query->get('category');
        }

        if ($request->query->has('priceLessThan')) {
            $priceLessThan = $request->query->get('priceLessThan');
        }
        
        $productArray = $this->productManager->filterProduct($category, $priceLessThan);

        if (count($productArray) > 5) {
            $productArray = array_slice($productArray, 0 ,5);
        }

        $resultProducts = $this->productManager->listDiscountedProducts($productArray);

        return $this->json($resultProducts);
    }
}
