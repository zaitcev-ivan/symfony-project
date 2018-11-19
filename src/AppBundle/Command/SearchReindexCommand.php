<?php

namespace AppBundle\Command;

use AppBundle\Repository\ProductRepository;
use AppBundle\Service\SearchService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SearchReindexCommand extends Command
{
    private $searchService;
    /** @var ProductRepository */
    private $productRepository;

    /**
     * SearchCommand constructor.
     * @param SearchService $searchService
     * @param EntityManagerInterface $em
     * @param string|null $name
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    public function __construct
    (
        SearchService $searchService,
        EntityManagerInterface $em,
        string $name = null
    )
    {
        $this->productRepository = $em->getRepository('AppBundle:Product');
        $this->searchService = $searchService;
        parent::__construct($name);
    }
    
    protected function configure()
    {
        $this->setName('app:elastic:reindex')
            ->setDescription('Команды для работы с ElasticSearch')
            ->setHelp('Команды для работы с ElasticSearch')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Clear index');
        $this->searchService->clear();
        
        $products = $this->productRepository->findAll();
        
        foreach ($products as $product) {
            $this->searchService->index($product);
            $output->writeln('Add product #' . $product->getId());
        }
        
        
        $output->writeln('Done.');
    }
}