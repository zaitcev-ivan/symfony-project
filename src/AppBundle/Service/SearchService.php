<?php

namespace AppBundle\Service;

use AppBundle\Component\ElasticSearchSettings;
use AppBundle\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Elasticsearch\ClientBuilder;
use AppBundle\Entity\Product;
use AppBundle\Entity\Value;

class SearchService
{
    private $client;
    private $productRepository;

    /**
     * SearchService constructor.
     * @param ElasticSearchSettings $settings
     */
    public function __construct
    (
        ElasticSearchSettings $settings,
        EntityManagerInterface $em
    )
    {
        $this->client = ClientBuilder::create()->setHosts([$settings->getHost() . ':' . $settings->getPort()])->build();
        $this->productRepository = $em->getRepository('AppBundle:Product');
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

    /**
     * @param $query
     * @return Product[]|null
     */
    public function search($query): array
    {
        $response = $this->client->search([
            'index' => 'app',
            'type' => 'products',
            'body' => [
                '_source' => ['id'],
                'query' => [
                    'bool' => [
                        'must' => [
                            'multi_match' => [
                                'query' => $query['query'],
                                'fields' => [ 'name', 'code' ],
                            ]
                        ]
                    ],
                ],
            ],
        ]);
        
        
        $ids = array_column($response['hits']['hits'], '_id'); 
        
        return $this->productRepository->findBy(['id' => $ids]);
    }
}