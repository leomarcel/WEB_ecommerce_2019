<?php


namespace App\Command;

use App\Entity\SaleItem;
use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class SaleItemProvisioningCommand extends Command
{

    protected static $defaultName = 'app:sale-item-provisioning';
    private $doctrine;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this
            ->setName('app:sale-item-provisioning')
            ->setDescription('Sale item provisioning cli for database.')
            ->setHelp('add-sale-item, drop-sale-item')
            ->addArgument('action', InputArgument::REQUIRED, 'add-sale-item or drop-sale-item')
            ->addArgument('title', InputArgument::OPTIONAL, 'The title of the new sale item')
            ->addArgument('description', InputArgument::OPTIONAL, 'The description of the new sale item')
            ->addArgument('price', InputArgument::OPTIONAL, 'The price of the new sale item')
            ->addArgument('image', InputArgument::OPTIONAL, 'The image link of the new sale item');
        $this->doctrine = $container->get('doctrine');
    }

    private function add_sale_item(InputInterface $input, OutputInterface $output)
    {
        $sale_item = new SaleItem();

        $title = $input->getArgument('title');
        $description = $input->getArgument('description');
        $price = $input->getArgument('price');
        $image = $input->getArgument('image');

        if (null == $title) {
            $output->write("Empty title or price.");
            return 84;
        }

        if (false == is_numeric($price)) {
            $output->write("Wrong or empty price.");
            return 84;
        }

        $sale_item->setTitle($title);
        $sale_item->setDescription($description);
        $sale_item->setPrice(['value' => (float)$price, 'tag' => 'EUR']);
        $sale_item->setDate(new DateTime('now'));
        $sale_item->setUserFrom("CLI Tool on server");
        $sale_item->setImage(['default' => '/image/dark-default.jpg', 'image' => $image]);
        $sale_item->setIsDark(false);

        $manager = $this->doctrine->getManager();
        $manager->persist($sale_item);
        $manager->flush();

        return 0;
    }

    private function drop_sale_item(InputInterface $input, OutputInterface $output)
    {
        $title = $input->getArgument('title');

        if ($title == null) {
            $output->write("No title.");
            return 84;
        }

        $sale_item = $this->doctrine->getRepository(SaleItem::class)->findOneByTitle($title);

        if (null == $sale_item) {
            $output->write("No sale item with title '{$title}'.");
            return 84;
        }
        $manager = $this->doctrine->getManager();
        $manager->remove($sale_item);
        $manager->flush();

        return 0;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $action = $input->getArgument('action');

        if ($action == 'add-sale-item')
            return $this->add_sale_item($input, $output);
        else if ($action == 'drop-sale-item')
            return $this->drop_sale_item($input, $output);
        else
            $output->write("{$action} is not know action. Action know: add-sale-item or drop-sale-item");

        return 0;
    }
}