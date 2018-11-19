<?php

namespace AppBundle\Service;

use AppBundle\Component\ElasticSearchSettings;
use Elasticsearch\ClientBuilder;
use AppBundle\Entity\Product;
use AppBundle\Entity\Value;

class SearchService
{
    private $client;

    /**
     * SearchService constructor.
     * @param ElasticSearchSettings $settings
     */
    public function __construct(ElasticSearchSettings $settings)
    {
        $this->client = ClientBuilder::create()->setHosts([$settings->getHost() . ':' . $settings->getPort()])->build();
    }

    /**
     * Добавление товара в индекс
     * @param Product $product
     */
    public function index(Product $product): void
    {
        $this->client->index([
            'index' => 'app',
            'type' => 'products',
            'id' => $product->getId(),
            'body' => [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'brand' => $product->getBrand()->getId(),
                'values' => array_map(function (Value $value) {
                    return [
                        'characteristic' => $value->getCharacteristic()->getId(),
                        'value_string' => (string)$value->getValue(),
                        'value_int' => (int)$value->getValue(),
                    ];
                }, $product->getValues()->toArray()),
            ],
        ]);
    }

    /**
     * Удаление из индекса товара
     * @param Product $product
     */
    public function remove(Product $product): void
    {
        $this->client->delete([
            'index' => 'app',
            'type' => 'products',
            'id' => $product->getId(),
        ]);
    }

    /**
     * Удаление из индекса всех товаров
     */
    public function clear(): void
    {
        $this->client->deleteByQuery([
            'index' => 'app',
            'type' => 'products',
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ]);
    }
}