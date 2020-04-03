<?php


namespace App\Command;

use App\Entity\User;
use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ClearApiToken extends Command
{

    protected static $defaultName = 'app:clear-api-token';
    private $doctrine;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this
            ->setName('app:clear-api-token')
            ->setDescription('Clear expired API token from database.')
            ->setHelp('Clear expired API token from database.');
        $this->doctrine = $container->get('doctrine');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = $this->doctrine->getRepository(User::class)->findAll();
        $datetimenow = new DateTime("now");
        $dateTimestamp = strtotime($datetimenow->format('Y-m-d H:i:s'));

        $nb_remove = 0;
        $apikey_json = [];

        foreach ($users as $user) {
            $apikey_json = $user->getApikey();
            foreach ($apikey_json as $key => $token) {
                if (strtotime($token['expiresAt']['date']) < $dateTimestamp) {
                    unset($apikey_json[$key]);
                    $nb_remove += 1;
                }
            }
            $user->setApikey($apikey_json);
        }

        $this->doctrine->getManager()->flush();
        $output->write("{$nb_remove} API Key has been removed.");

        return 0;
    }
}