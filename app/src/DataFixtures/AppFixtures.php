<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Job;
use App\Entity\Resource;
use App\Entity\Room;
use App\Entity\Sector;
use App\Entity\Edition;
use App\Entity\School;
use App\Entity\Section;
use App\Entity\Skill;
use App\Entity\Student;
use App\Entity\Speaker;
use App\Entity\Quiz;
use App\Entity\Question;
use App\Entity\UserAnswer;
use App\Entity\User;
use App\Entity\Workshop;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use DateTimeImmutable;

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
    for ($i = 0; $i < 30; $i++) {
      $activity = new Activity();
      $activity
        ->setName($this->faker->words(3, true));
      $activities[] = $activity;
      $manager->persist($activity);
    }

    // Skills
    $skills = [];
    for ($i = 0; $i < 30; $i++) {
      $skill = new Skill();
      $skill
        ->setName($this->faker->words(5, true));
      $skills[] = $skill;
      $manager->persist($skill);
    }

    // Job
    $jobs = [];
    for ($i = 0; $i < 30; $i++) {
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

    // Sector
    $sectors = [];
    for ($i = 0; $i < 30; $i++) {
      $sector = new Sector();
      $sector
        ->setName($this->faker->words(3, true))
        ->setDescription($this->faker->words(3, true));
      $sectors[] = $sector;
      $manager->persist($sector);
    }

    // Edition
    $editions = [];
    for ($i = 0; $i < 5; $i++) {
      $edition = new Edition();
      $edition
        ->setYear($this->faker->year())
        ->setStartAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-1 year', '+1 year')))
        ->setEndAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-1 year', '+1 year')))
        ->setAdress($this->faker->address());
      $editions[] = $edition;
      $manager->persist($edition);
    }

    // Schools
    $schools = [];
    for ($i = 0; $i < 50; $i++) {
      $school = new School();
      $school
        ->setName($this->faker->words(3, true))
        ->setAdress($this->faker->address())
        ->setCity($this->faker->city())
        ->setPostalCode(intval($this->faker->postcode()))
        ->setCountry($this->faker->country());
      $schools[] = $school;
      $manager->persist($school);
    }

    // Section
    $sections = [];
    for ($i = 0; $i < 15; $i++) {
      $section = new Section();
      $section
        ->setName($this->faker->words(3, true));
      $sections[] = $section;
      $manager->persist($section);
    }

    // Students
    // $students = [];
    // for ($i = 0; $i < 100; $i++) {
    //   $student = new Student();
    //   $student
    //     ->setSchoolEmail($this->faker->email())
    //     ->setRegistrationAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-1 year', '+1 year')))
    //     ->setSchool($this->faker->randomElement($schools))
    //     ->setSection($this->faker->randomElement($sections))
    //     ->setUser($this->faker->randomElement($users))
    //     ->setEdition($this->faker->randomElement($editions));
    //   $students[] = $student;
    //   $manager->persist($student);
    // }

    // Speakers
    // $speakers = [];
    // for ($i = 0; $i < 100; $i++) {
    //   $speaker = new Speaker();
    //   $speaker
    //     ->setSocialEmail($this->faker->email())
    //     ->setResgistrationAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-1 year', '+1 year')))
    //     ->setUser($this->faker->randomElement($users))
    //     ->setEdition($this->faker->randomElement($editions));
    //   $speakers[] = $speaker;
    //   $manager->persist($speaker);
    // }

    // Quiz
    $quizzes = [];
    for ($i = 0; $i < 10; $i++) {
      $quiz = new Quiz();
      $quiz
        ->setName($this->faker->words(2, true))
        ->setEdition($this->faker->randomElement($editions));
      $quizzes[] = $quiz;
      $manager->persist($quiz);
    }

    // Question
    $questions = [];
    for ($i = 0; $i < 50; $i++) {
      $question = new Question();
      $question
        ->setName($this->faker->words(7, true))
        ->setQuiz($this->faker->randomElement($quizzes))
        ->setType($this->faker->randomElement(['text', 'radio', 'checkbox']));
      $questions[] = $question;
      $manager->persist($question);
    }

    // UserAnswer
    $userAnswers = [];
    for ($i = 0; $i < 100; $i++) {
      $userAnswer = new UserAnswer();
      $userAnswer
        ->setResponse($this->faker->words(3, true))
        ->setQuestion($this->faker->randomElement($questions))
        ->setUser($this->faker->randomElement($users));
      $userAnswers[] = $userAnswer;
      $manager->persist($userAnswer);
    }

    // Workshops
    $workshops = [];
    for ($i = 0; $i < 20; $i++) {
      $workshop = new Workshop();
      $workshop
        ->setName($this->faker->words(3, true))
        ->setDescription($this->faker->words(10, true))
        ->setStartAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-1 year', '+1 year')))
        ->setEndAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-1 year', '+1 year')))
        ->setRoom($this->faker->randomElement($rooms))
        ->setSector($this->faker->randomElement($sectors))
        ->setEdition($this->faker->randomElement($editions));
      // for ($w = 0; $w < mt_rand(1, 5); $w++) {
      //   $workshop->addStudent($this->faker->randomElement($students));
      // }
      for ($j = 0; $j < mt_rand(1, 5); $j++) {
        $workshop->addJob($this->faker->randomElement($jobs));
      }
      $workshops[] = $workshop;
      $manager->persist($workshop);
    }

    // Resources
    $resources = [];
    for ($i = 0; $i < 100; $i++) {
      $resource = new Resource();
      $resource
        ->setName($this->faker->sentence(1))
        ->setDescription($this->faker->sentence(mt_rand(5, 10)))
        ->setUrl($this->faker->url())
        ->setWorkshop($this->faker->randomElement($workshops));
      $resources[] = $resource;
      $manager->persist($resource);
    }

    $manager->flush();
  }
}
