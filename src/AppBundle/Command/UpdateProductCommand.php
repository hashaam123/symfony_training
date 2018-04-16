<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use AppBundle\Service\ProductService;

/**
 * Class UpdateProductCommand
 * @package AppBundle\Command
 */
class UpdateProductCommand extends Command
{
    /**
     * @var ProductService
     * @package AppBundle\Service
     */
    private $productService;

    /**
     * UpdateProductCommand constructor.
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            // name of the command
            ->setName("app:update-product")
            // description of command while command is running
            ->setDescription("updates product by id")
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to update product.....")
            // configuring arguments
            ->addArgument('productid', InputArgument::REQUIRED, 'Id of Product.')
            ->addArgument('productname', InputArgument::REQUIRED, 'Name of Product.')
            // ...
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $productId = $input->getArgument("productid");
        $productName = $input->getArgument("productname");
        $status = $this->productService->updateProductById($productId, $productName);
        if ($status == true) {
            $output->writeln("Product updated successfully");
        } else {
            $output->writeln("Product does not exist");
        }
    }
}
