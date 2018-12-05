<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Создание маппинга для сущности Products в ElasticSearch
 */
final class Version20181119170612 extends AbstractMigration implements ContainerAwareInterface
{
    /** @var Client */
    private $client;

    /**
     * Sets the container.
     * @param ContainerInterface|null $container
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    public function setContainer(ContainerInterface $container = null)
    {
        if ($container) {
            $settings = $container->get('app.component.elastic_search_settings');
            $this->client = ClientBuilder::create()->setHosts([$settings->getHost() . ':' . $settings->getPort()])->build();
        }
        else {
            throw new ServiceNotFoundException('AppBundle\Component\ElasticSearchSettings not found');
        }
    }

    public function up(Schema $schema) : void
    {

        $this->client->indices()->create([
            'index' => 'app',
            'body' => [
                'mappings' => [
                    'products' => [
                        '_source' => [
                            'enabled' => true,
                        ],
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                            ],
                            'price' => [
                                'type' => 'integer',
                            ],
                            'brand' => [
                                'type' => 'integer',
                            ],
                            'values' => [
                                'type' => 'nested',
                                'properties' => [
                                    'characteristic' => [
                                        'type' => 'integer'
                                    ],
                                    'value_string' => [
                                        'type' => 'keyword',
                                    ],
                                    'value_int' => [
                                        'type' => 'integer',
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        
        // Чтобы в консоль не вываливалась ошибка об отсутствии sql-команд в миграции
        $this->addSql('SELECT 1');
    }

    public function down(Schema $schema) : void
    {
        try {
            $this->client->indices()->delete([
                'index' => 'app'
            ]);
        } catch (Missing404Exception $e) {}

        $this->addSql('SELECT 1');
    }
}
