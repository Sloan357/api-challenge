<?php 

namespace App\Command;

use App\Manager\ProductsManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:list-products',
    description: 'This command accepts two parameters: (string) category and price less than, and accordingly lists the valid products up to a maximum of 5',
)]
class ProductListCommand extends Command
{
    protected ProductsManager $productsManager;
    protected $fileSystem;
    
    public function __construct(ProductsManager $productsManager, Filesystem $fileSystem)
    {
        parent::__construct();

        $this->productsManager = $productsManager;
        $this->fileSystem = $fileSystem;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('category', InputArgument::OPTIONAL, 'Product Category')
            ->addArgument('priceLessThan', InputArgument::OPTIONAL, 'Upper Price Limit');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $category = $input->getArgument('category');
        $priceLessThan = $input->getArgument('priceLessThan');

        $list = file_get_contents('././public/productList.json');

        $productArray = [];

        if ($category && $category != 'null') {
            if ($priceLessThan) {
                $productArray = $this->productsManager->filterProduct($category, $priceLessThan, $list);
            } else {
                $productArray = $this->productsManager->filterProduct($category, null, $list);
            }
            $io->note(sprintf('You passed an argument: %s', $category));
        } elseif ($category === 'null') {
            $productArray = $this->productsManager->filterProduct(null, $priceLessThan, $list);
        } else {
            $productArray = $this->productsManager->filterProduct(null, null, $list);
        }

        $resultArray = $this->productsManager->listDiscountedProducts($productArray);

        var_dump($resultArray);

        return Command::SUCCESS;
    }
}
