# About

A simple PHP API wrapper for Shopify using Guzzle [Shopify API](https://help.shopify.com/api/getting-started).

## Installation

Install via [Composer](https://getcomposer.org/) by running `composer require arslanramay/php-shopify-api` in your project directory.

## Usage

In order to use this wrapper library you will need to provide credentials to access Shopify's API.

You will either need an access token for the shop you are trying to access (if using a [public application](https://help.shopify.com/api/getting-started/authentication#public-applications)) or an API Key and Secret for a [private application](https://help.shopify.com/api/getting-started/authentication#private-applications).

## Code Examples

#### Make an API call
```php
use arslanramay\ShopifyPHP\Shopify;

// Initialize the client
$shopify = new Shopify('exampleshop.myshopify.com', 'mysupersecrettoken');

// Get all products
$result = $shopify->call('GET', 'admin/products.json');

// Get the products with ids of '9326553104669' and '9339160002845' with only the 'id', 'images', and 'title' fields
$result = $shopify->call('GET', 'admin/products.json', [
    'ids' => '9326553104669,9339160002845',
    'fields' => 'id,images,title',
]);

// Create a new product with title "Kerastase Shampoo 150ml"
$result = $shopify->call('POST', 'admin/products.json', [
    'product' => [
        "title"        => "Kerastase Shampoo 150ml",
        "body_html"    => "<strong>Good shampoo for hair!</strong>",
        "vendor"       => "Kerastase",
        "product_type" => "Shampoo",
        "tags"         => 'Shampoo, Kerastase, "Hair Care"',
    ],
]);
```

#### Use Private Application API Credentials to authenticate API requests
```php
use arslanramay\ShopifyPHP\Shopify;

$shopify = new Shopify($data['shop'], [
    'api_key' => '...',
    'secret'  => '...',
]);
```

#### Use an access token to authenticate API requests
```php
use arslanramay\ShopifyPHP\Shopify;

$storedToken = ''; // Retrieve the stored token for the shop in question
$shopify = new Shopify('exampleshop.myshopify.com', $storedToken);
```

#### Request an access_token for a shop
```php
use arslanramay\ShopifyPHP\Shopify;

function make_authorization_attempt($shop, $scopes)
{
    $shopify = new Shopify($shop, [
        'api_key' => '...',
        'secret'  => '...',
    ]);

    $nonce = bin2hex(random_bytes(10));

    // Store a record of the shop attempting to authenticate and the nonce provided
    $storedAttempts = file_get_contents('authattempts.json');
    $storedAttempts = $storedAttempts ? json_decode($storedAttempts) : [];
    $storedAttempts[] = ['shop' => $shop, 'nonce' => $nonce, 'scopes' => $scopes];
    file_put_contents('authattempts.json', json_encode($storedAttempts));

    return $shopify->getAuthorizeUrl($scopes, 'https://example.com/handle/shopify/callback', $nonce);
}

header('Location: ' . make_authorization_attempt('exampleshop.myshopify.com', ['read_product']));
die();
```

#### Handle Shopify's response to the authorization request
```php
use arslanramay\ShopifyPHP\Shopify;

function check_authorization_attempt()
{
    $data = $_GET;

    $shopify = new Shopify($data['shop'], [
        'api_key' => '...',
        'secret'  => '...',
    ]);

    $storedAttempt = null;
    $attempts = json_decode(file_get_contents('authattempts.json'));
    foreach ($attempts as $attempt) {
        if ($attempt->shop === $data['shop']) {
            $storedAttempt = $attempt;
            break;
        }
    }

    return $shopify->authorizeApplication($storedAttempt->nonce, $data);
}

$response = check_authorization_attempt();
if ($response) {
    // Store the access token for later use
    $response->access_token;
}
```
