<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
  /**
   * @var Generator
   */
  private Generator $faker;

  public function __construct()
  {
    $this->faker = Factory::create('fr_FR');
  }

  public function load(ObjectManager $manager): void
  {
    // Users
    $users = [];
    for ($i = 0; $i < 10; $i++) {
      $user = new User();
      $user->setFullname($this->faker->name())
        ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->userName() : null)
        ->setEmail($this->faker->email())
        ->setRoles(['ROLE_USER'])
        ->setPlainPassword('password');
      $users[] = $user;
      $manager->persist($user);
    }

    $manager->flush();
  }
}