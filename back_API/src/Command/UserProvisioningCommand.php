<?php


namespace App\Command;

use App\Entity\User;
use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class UserProvisioningCommand extends Command
{

    protected static $defaultName = 'app:user-provisioning';
    private $doctrine;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this
            ->setName('app:user-provisioning')
            ->setDescription('User provisioning cli for database.')
            ->setHelp('add-user, update-user, drop-user')
            ->addArgument('action', InputArgument::REQUIRED, 'add-user or drop-user')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the new user')
            ->addArgument('password', InputArgument::OPTIONAL, 'The plain password of the new user')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'The username of the new user', null)
            ->addOption('darkm', null, InputOption::VALUE_OPTIONAL, 'Authorize dark mode of the new user', null)
            ->addOption('new_password', null, InputOption::VALUE_OPTIONAL, 'For change the password of the user', null);;

        $this->doctrine = $container->get('doctrine');
    }

    private function add_user(InputInterface $input, OutputInterface $output)
    {
        $user = new User();

        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $name = $input->getOption('name');
        $darkm = $input->getOption('darkm');

        if ($email == null or $password == null) {
            $output->write("No email or password.");
            return 84;
        }

        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $user->setRoles([1]);
        $user->setName($name);
        $user->setDarkModeAllowed(filter_var((string)$darkm, FILTER_VALIDATE_BOOLEAN));

        $manager = $this->doctrine->getManager();
        $manager->persist($user);
        $manager->flush();

        return 0;
    }

    private function drop_user(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        if ($email == null or $password == null) {
            $output->write("No email or password.");
            return 84;
        }

        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $email]);

        if (null == $user) {
            $output->write("No user with email '{$email}'.");
            return 84;
        }

        if (password_verify($password, $user->getPassword())) {
            $manager = $this->doctrine->getManager();
            $manager->remove($user);
            $manager->flush();
        } else {
            $output->write("Wrong password.");
            return 84;
        }

        return 0;
    }

    private function update_user(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $name = $input->getOption('name');
        $new_password = $input->getOption('new_password');
        $darkm = $input->getOption('darkm');

        if ($email == null or $password == null) {
            $output->write("No email or password.");
            return 84;
        }

        $user = $this->doctrine->getRepository(User::class)->findOneBy(['email' => $email]);

        if (null == $user) {
            $output->write("No user with email '{$email}'.");
            return 84;
        }

        if (password_verify($password, $user->getPassword())) {
            $manager = $this->doctrine->getManager();
            if (null != $new_password and $new_password != $password)
                $user->setPassword(password_hash($new_password, PASSWORD_DEFAULT));
            if (null != $darkm)
                $user->setDarkModeAllowed(filter_var((string)$darkm, FILTER_VALIDATE_BOOLEAN));
            if (null != $name)
                $user->setName($name);
            $manager->flush();
        } else {
            $output->write("Wrong password.");
            return 84;
        }

        return 0;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $action = $input->getArgument('action');

        if ($action == 'add-user')
            return $this->add_user($input, $output);
        else if ($action == 'drop-user')
            return $this->drop_user($input, $output);
        else if ($action == 'update-user')
            return $this->update_user($input, $output);
        else
            $output->write("{$action} is not know action. Action know: add-user, update-user or drop-user");

        return 0;
    }
}