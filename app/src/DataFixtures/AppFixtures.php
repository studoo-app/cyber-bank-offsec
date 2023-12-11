<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Operation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $this->loadUsers($manager,$faker);
    }

    public function loadUsers(ObjectManager $manager, Generator $faker): void
    {
        //ADMIN
        $admin = new User();
        $admin->setEmail("admin@hsbank.dev");
        $admin->setName("Staff Account");
        $admin->setPassword($this->hasher->hashPassword($admin,"hsbank-pwd"));
        $admin->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);

        //CLIENTS
        for($i=1; $i<=10;$i++){

            $prenom = strtolower(
                $faker->numberBetween(0,1) ?
                    $this->clean($faker->firstNameMale) :
                    $this->clean($faker->firstNameFemale)
            );
            $nom = str_replace(" ", "",strtolower($this->clean($faker->lastName)));
            $email = "$prenom.$nom@mail.dev";

            $client = new User();
            $client->setEmail($email);
            $client->setName("$prenom $nom");
            $client->setPassword($this->hasher->hashPassword($client,"password"));
            $client->setRoles(["ROLE_CLIENT"]);

            $client->setAccount($this->makeAccount($faker));

            $manager->persist($client);

        }

        //John Doe Shadow Account
        $shadow = new User();
        $shadow->setEmail("john.doe@mail.dev");
        $shadow->setName("John doe");
        $shadow->setPassword($this->hasher->hashPassword($client,"password"));
        $shadow->setRoles(["ROLE_CLIENT"]);
        $shadow->setAccount($this->makeAccount($faker,false));
        $manager->persist($shadow);

        $manager->flush();
    }


    private function makeAccount(Generator $faker, bool $generateOperations=true): Account
    {
        $account = new Account();
        $account->setNumber($faker->numberBetween(1000,9999));
        $account->setBalance(0);
        if($generateOperations){
            for($i=0;$i < $faker->numberBetween(5,15); $i++){
                $account->addOperation($this->makeOperation($faker));
            }
            $account->calculateBalance();
        }

        return $account;
    }

    private function makeOperation(Generator $faker): Operation
    {
        $operation = new Operation();
        $operation->setLabel(lcfirst($faker->sentence(2)));
        $operation->setAmount($faker->numberBetween(-2000,4000));

        return $operation;
    }

    private function clean(String $text): ?String
    {
        $utf8 = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u' => ' ', // Literally a single quote
            '/[“”«»„]/u' => ' ', // Double quote
            '/ /' => ' ', // nonbreaking space (equiv. to 0x160)
            '/[.]/' => '',
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }


}
