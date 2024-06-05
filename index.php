<?php

require 'vendor/autoload.php';

use arslanramay\ShopifyPHP\Shopify;

// Load the.env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Define shop domain, API key, and secret
$shop_domain       = $_ENV['SHOP_DOMAIN'];
$shop_access_token = $_ENV['SHOP_ACCESS_TOKEN'];
$shop_api_version  = $_ENV['SHOP_API_VERSION'];

echo "<pre>";
// var_dump($_ENV);
// var_dump($shop_access_token);

echo "Shop Domain:  " . $shop_domain . "\n";
echo "Shop Access Token:  " . $shop_access_token;



// Initialize the Shopify client
$shopify = new Shopify($shop_domain, $shop_access_token);

// =====================================
//          CODE EXAMPLES
// =====================================

// Example 1: Fetch all Products
$result = $shopify->call('GET', 'admin/products.json');

// echo "<pre>";
// echo var_dump($result->products);
// echo print_r($result->products);
// echo "</pre>";



// Example 2: Fetch products with ids of '9326553104669' and '9339160002845' with only the 'id', 'images', and 'title' fields
$products = $shopify->call('GET', 'admin/products.json', [
    'ids'    => '9326553104669,9339160002845',
    'fields' => 'id,images,title,created_at,status',
]);

echo "<pre>";
// echo var_dump($result->products);
echo print_r($products);
// echo "Products: " . json_encode($products, JSON_PRETTY_PRINT) . "\n";
echo "</pre>";



// Create a new "Burton Custom Freestyle 151" product
// $result123 = $shopify->call('POST', 'admin/products.json', [
//     'product' => [
//         "title"        => "Burton Custom Freestyle 151",
//         "body_html"    => "<strong>Good snowboard!</strong>",
//         "vendor"       => "Burton",
//         "product_type" => "Snowboard",
//         "tags"         => 'Barnes Noble, Johns Fav, "Big Air"',
//     ],
// ]);

// print_r($result123);

// Example 3: Create a new Product

// Product data
$productData = [
    'product' => [
        'title'        => 'Kerastase Shampoo 150ml',
        'body_html'    => '<strong>Kerastase Shampoo</strong> for healthy hair.',
        'vendor'       => 'Kerastase',
        'product_type' => 'Shampoo',
        'tags'         => 'Shampoo, Kerastas, Hair Care',
        'variants'     => [
            [
                'option1' => 'Default Title',
                'price'   => '29.99',
                'sku'     => 'KERASTASE-150ML',
            ]
        ]
    ]
];

// Create product
try {
    $product = $shopify->call('POST', 'admin/api/2024-01/products.json', $product);
    print_r($product);
} catch (Exception $e) {
    echo 'Error creating product: ' . $e->getMessage();
}


// Create a new product with title "Kerastase Shampoo 150ml"
// $result = $shopify->call('POST', 'admin/products.json', [
//     'product' => [
//         "title"        => "Kerastase Shampoo 150ml",
//         "body_html"    => "<strong>Good shampoo for hair!</strong>",
//         "vendor"       => "Kerastase",
//         "product_type" => "Snowboard",
//         "tags"         => 'Shampoo, Kerastas, "Hair Care"',
//     ],
// ]);


// Fetch orders
// try {
//     $orders = $shopify->call('GET', 'admin/api/2024-04/orders.json');
//     echo "<pre>";
//     echo "Orders: " . json_encode($orders, JSON_PRETTY_PRINT) . "\n";
//     echo "</pre>";
// } catch (Exception $e) {
//     echo "Error fetching orders: " . $e->getMessage() . "\n";
// }