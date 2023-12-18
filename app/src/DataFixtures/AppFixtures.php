<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Job;
use App\Entity\Skill;
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
    for ($i = 0; $i < 30; $i++) {
      $user = new User();
      $user
        ->setFirstName($this->faker->firstName())
        ->setLastName($this->faker->lastName())
        ->setEmail($this->faker->email())
        ->setRoles(['ROLE_USER'])
        ->setPlainPassword('password');
      $users[] = $user;
      $manager->persist($user);
    }

    // Activity
    $activities = [];
    for ($i = 0; $i < 100; $i++) {
      $activity = new Activity();
      $activity
        ->setName($this->faker->sentence(3));
      $activities[] = $activity;
      $manager->persist($activity);
    }

    // Skills
    $skills = [];
    for ($i = 0; $i < 100; $i++) {
      $skill = new Skill();
      $skill
        ->setName($this->faker->sentence(5));
      $skills[] = $skill;
      $manager->persist($skill);
    }

    // Job
    $jobs = [];
    for ($i = 0; $i < 100; $i++) {
      $job = new Job();
      $job
        ->setName($this->faker->sentence(3));
      for ($j = 0; $j < mt_rand(1, 5); $j++) {
        $job->addActivity($this->faker->randomElement($activities));
      }
      for ($j = 0; $j < mt_rand(1, 10); $j++) {
        $job->addSkill($this->faker->randomElement($skills));
      }
      $jobs[] = $job;
      $manager->persist($job);
    }

    $manager->flush();
  }
}
