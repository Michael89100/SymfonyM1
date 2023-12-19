<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Job;
use App\Entity\Resource;
use App\Entity\Room;
use App\Entity\Skill;
use App\Entity\User;
use App\Entity\Workshop;
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
        ->setName($this->faker->words(3, true));
      $activities[] = $activity;
      $manager->persist($activity);
    }

    // Skills
    $skills = [];
    for ($i = 0; $i < 100; $i++) {
      $skill = new Skill();
      $skill
        ->setName($this->faker->words(5, true));
      $skills[] = $skill;
      $manager->persist($skill);
    }

    // Job
    $jobs = [];
    for ($i = 0; $i < 100; $i++) {
      $job = new Job();
      $job
        ->setName($this->faker->words(3, true));
      for ($j = 0; $j < mt_rand(1, 5); $j++) {
        $job->addActivity($this->faker->randomElement($activities));
      }
      for ($j = 0; $j < mt_rand(1, 10); $j++) {
        $job->addSkill($this->faker->randomElement($skills));
      }
      $jobs[] = $job;
      $manager->persist($job);
    }

    // Rooms
    $rooms = [];
    for ($i = 0; $i < 10; $i++) {
      $room = new Room();
      $room
        ->setName($this->faker->words(2, true))
        ->setCapacityMaximum(mt_rand(10, 100));
      $rooms[] = $room;
      $manager->persist($room);
    }

    // Resources
    // $resources = [];
    // for ($i = 0; $i < 100; $i++) {
    //   $resource = new Resource();
    //   $resource
    //     ->setName($this->faker->sentence(1))
    //     ->setDescription($this->faker->sentence(mt_rand(5, 10)))
    //     ->setUrl($this->faker->url());
    //   $resources[] = $resource;
    //   $manager->persist($resource);
    // }

    $manager->flush();
  }
}
