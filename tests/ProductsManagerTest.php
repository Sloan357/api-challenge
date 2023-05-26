<?php

namespace App\Tests;

use App\Manager\ProductsManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProductsManagerTest extends KernelTestCase
{
    /**
     * @covers \App\Manager\ProductsManager::applyDiscount
     */
    public function testApplyDiscount()
    {
        $productsManager = new ProductsManager();

        $discounted = $productsManager->applyDiscount(100, 25);

        $this->assertEquals($discounted, 0.75*100);
    }

    /**
     * @covers \App\Manager\ProductsManager::applyDiscount
     */
    public function testApplyNoDiscount()
    {
        $productsManager = new ProductsManager();

        $discounted = $productsManager->applyDiscount(100, 0);

        $this->assertEquals($discounted, 100);
    }

    /**
     * @covers \App\Manager\ProductsManager::filterProduct
     */
    public function testFilterProductsNoFilters()
    {
        $productsManager = new ProductsManager();

        $productArray = $productsManager->filterProduct(null, null, $this->mockList());

        $this->assertIsArray($productArray);
    }

    /**
     * @covers \App\Manager\ProductsManager::filterProduct
     */
    public function testFilterProductsCategoryFilter()
    {
        $productsManager = new ProductsManager();

        $productArray = $productsManager->filterProduct('boots', null, $this->mockList());

        $this->assertIsArray($productArray);
    }

    /**
     * @covers \App\Manager\ProductsManager::filterProduct
     */
    public function testFilterProductsPriceLessThanFilter()
    {
        $productsManager = new ProductsManager();

        $productArray = $productsManager->filterProduct(null, 900, $this->mockList());

        $this->assertIsArray($productArray);
    }

    /**
     * @covers \App\Manager\ProductsManager::filterProduct
     */
    public function testFilterProductsAllFilter()
    {
        $productsManager = new ProductsManager();

        $productArray = $productsManager->filterProduct('boots', 900, $this->mockList());

        $this->assertIsArray($productArray);
    }

    /**
     * @covers \App\Manager\ProductsManager::listDiscountedProducts
     */
    public function testListDiscountedProducts()
    {
        $productsManager = new ProductsManager();

        $filteredArray = $productsManager->filterProduct('sandals', null, $this->mockList());

        $discountedArray = $productsManager->listDiscountedProducts($filteredArray);

        $this->assertNotNUll($discountedArray[0]['price']['originalPrice'], 'The price attribute does not seem to have been converted into the expected list');
    }

    private function mockList()
    {
        return '{
            "products": [
                {
                    "sku": "000001",
                    "name": "BV Lean leather ankle boots",
                    "category": "boots",
                    "price": 89000
                },
                {
                    "sku": "000002",
                    "name": "BV Lean leather ankle boots",
                    "category": "boots",
                    "price": 99000
                },
                {
                    "sku": "000003",
                    "name": "Ashlington leather ankle boots",
                    "category": "boots",
                    "price": 71000
                },
                {
                    "sku": "000004",
                    "name": "Naima embellished suede sandals",
                    "category": "sandals",
                    "price": 79500
                },
                {
                    "sku": "000005",
                    "name": "Nathane leather sneakers",
                    "category": "sneakers",
                    "price": 59000
                }
            ]
        }';
    }
}