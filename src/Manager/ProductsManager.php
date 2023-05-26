<?php

namespace App\Manager;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProductsManager
{
    public function listDiscountedProducts(array $productArray)
    {
        $resultProducts = [];

        foreach ($productArray as $prod) {
            $discount = null;

            if ($prod['category'] === 'boots') {
                $discount = 30;
            }

            if ($prod['sku'] === '000003') {
                if ($discount && $discount < 15 || !$discount) {
                    $discount = 15;
                }
            }

            $result = [
                'sku' => $prod['sku'],
                'name' => $prod['name'],
                'category' => $prod['category'],
                'price' => [
                    'originalPrice' => $prod['price'],
                    'finalPrice' => $this->applyDiscount($prod['price'], $discount),
                    'discountPercentage' => $discount ? $discount . '%' : null,
                    'currency' => 'EUR'
                ]
            ];

            array_push($resultProducts, $result);
        }

        return $resultProducts;
    }

    public function filterProduct(string $category = null, string $priceLessThan = null, $list = null) 
    {
        if (!$list) {
            if (file_get_contents('productList.json')) {
                $productList = json_decode(file_get_contents('productList.json'))->products;
            } else {
                return null;
            }
            
        } else {
            $productList = json_decode($list)->products;
        }
        

        $serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $filteredResults = [];

        $priceLessThan = $priceLessThan * 100;

        if ($category && $priceLessThan) {

            foreach ($productList as $prod) {
                if ($prod->category === $category && $prod->price <= $priceLessThan) {
                    array_push($filteredResults, $prod);
                }
            }
            
            return $serializer->normalize($filteredResults, null);
        } elseif ($category && !$priceLessThan) {
            foreach ($productList as $prod) {
                if ($prod->category === $category) {
                    array_push($filteredResults, $prod);
                }
            }

            return $serializer->normalize($filteredResults, null);
        } elseif (!$category && $priceLessThan) {
            foreach ($productList as $prod) {
                if ($prod->price <= $priceLessThan) {
                    array_push($filteredResults, $prod);
                }
            }

            return $serializer->normalize($filteredResults, null);
        } else {
            return $serializer->normalize($productList, null);
        }
    }

    public function applyDiscount(int $price, int $percentile = null) 
    {
        return $percentile ? (100 - $percentile)*$price/100 : $price;
    }
}