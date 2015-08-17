<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTestdataCommand extends ContainerAwareCommand
{
    static private $fixtures = array(
        'Blackskyliner' => array(
            'class' => 'AppBundle\\Entity\\UserOne',
            'roles' => array('ROLE_USER'),
            'email' => 'blackskyliner@example.com',
            'password' => '12345',
        ),
        'Administrator' => array(
            'class' => 'AppBundle\\Entity\\UserTwo',
            'roles' => array('ROLE_ADMIN'),
            'email' => 'administrator@example.com',
            'password' => '12345',
        ),
    );

    protected function configure()
    {
        $this->setName('appbundle:testdata:create');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $discriminator = $this->getContainer()->get('pugx_user.manager.user_discriminator');
        $userManager = $this->getContainer()->get('pugx_user_manager');

        foreach (self::$fixtures as $userName => $userDetails) {
            $discriminator->setClass($userDetails['class']);

            /** @var User $userOne */
            $userOne = $userManager->createUser();

            $userOne->setEnabled(true);
            $userOne->setUsername($userName);
            $userOne->setEmail($userDetails['email']);
            $userOne->setRoles($userDetails['roles']);
            $userOne->setPlainPassword($userDetails['password']);

            try{
                $userManager->updateUser($userOne, true);
                $output->writeln(
                    sprintf(
                        'Created: %s:%s (%s)',
                        $userName,
                        $userDetails['password'],
                        implode(',', $userDetails['roles'])
                    )
                );
            } catch (DBALException $exception) {
                $output->writeln('Testdata already created.');
                return;
            }
        }
    }
}
