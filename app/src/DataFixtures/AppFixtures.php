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
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use DateTimeImmutable;

use function PHPUnit\Framework\isEmpty;

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

    // Chargement du fichier workshops.json
    $workshopsJson = file_get_contents(__DIR__ . '/workshops.json');
    $workshopsArray = json_decode($workshopsJson, true);

    // Users
    $users = [];
    for ($i = 0; $i < 50; $i++) {
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
    $usersTemp = $users;

    // Activity
    $activities = [];
    for ($i = 0; $i < 20; $i++) {
      $activity = new Activity();
      $activity
        ->setName($this->faker->words(3, true));
      $activities[] = $activity;
      $manager->persist($activity);
    }

    // Skills
    $skills = [];
    for ($i = 0; $i < 50; $i++) {
      $skill = new Skill();
      $skill
        ->setName($this->faker->words(5, true));
      $skills[] = $skill;
      $manager->persist($skill);
    }

    // Job
    $jobs = [];
    for ($i = 0; $i < 20; $i++) {
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
    for ($i = 0; $i < 15; $i++) {
      $room = new Room();
      $room
        ->setName($this->faker->words(2, true))
        ->setCapacityMaximum(mt_rand(10, 100));
      $rooms[] = $room;
      $manager->persist($room);
    }

    // Sector
    $sectors = [];
    for ($i = 0; $i < 10; $i++) {
      $sector = new Sector();
      $sector
        ->setName($this->faker->words(3, true))
        ->setDescription($this->faker->words(3, true));
      $sectors[] = $sector;
      $manager->persist($sector);
    }

    // Edition
    $editions = [];
    for ($i = 0; $i < 2; $i++) {
      $edition = new Edition();
      // 2 mai de l'année en cours à 8h
      $dateStart = new DateTime(2023 + $i . '-05-02 08:00:00');
      $dateEnd = new DateTime(2023 + $i . '-05-02 18:00:00');
      $edition
        ->setYear(2023 + $i)
        ->setStartAt(DateTimeImmutable::createFromMutable($dateStart))
        ->setEndAt(DateTimeImmutable::createFromMutable($dateEnd))
        ->setAdress($this->faker->address());
      $editions[] = $edition;
      $manager->persist($edition);
    }

    // Schools
    $schools = [];
    for ($i = 0; $i < 10; $i++) {
      $school = new School();
      $school
        ->setName($this->faker->words(3, true))
        ->setAdress($this->faker->address())
        ->setCity($this->faker->city())
        ->setPostalCode(intval($this->faker->postcode()))
        ->setCountry("France");
      $schools[] = $school;
      $manager->persist($school);
    }

    // Section
    $sections = [];
    for ($i = 0; $i < 3; $i++) {
      $section = new Section();
      $sectionsString = ['seconde', 'première', 'terminale'];
      $section
        ->setName($sectionsString[$i]);
      $sections[] = $section;
      $manager->persist($section);
    }

    $students = [];
    for ($j = 0; $j < count($editions); $j++) {
      $usersTemp = $users;
      // récupération de la date de début et de fin de l'édition au format DateTime
      $dateStart = $editions[$j]->getStartAt()->modify('-6 month')->format('Y-m-d H:i:s');
      $dateEnd = $editions[$j]->getEndAt()->format('Y-m-d H:i:s');
      for ($i = 0; $i < mt_rand(20, 40); $i++) {
        $randomIndex = array_rand($usersTemp);
        $student = new Student();
        $student
          ->setSchoolEmail($this->faker->email())
          ->setRegistrationAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween($dateStart, $dateEnd)))
          ->setSchool($this->faker->randomElement($schools))
          ->setSection($this->faker->randomElement($sections))
          ->setEdition($editions[$j])
          ->setUser($usersTemp[$randomIndex]);
        $manager->persist($student);
        $students[] = $student;
        unset($usersTemp[$randomIndex]);
      }
    }

    // Speakers
    $speakers = [];
    for ($i = 0; $i < 10; $i++) {
      $randomIndex = array_rand($usersTemp);
      $speaker = new Speaker();
      $speaker
        ->setSocialEmail($this->faker->email())
        ->setResgistrationAt(DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-1 year', '+1 year')))
        ->setUser($usersTemp[$randomIndex]);
      $speakers[] = $speaker;
      unset($usersTemp[$randomIndex]);
      $manager->persist($speaker);
    }

    // Quiz
    $quizzes = [];
    for ($i = 0; $i < 5; $i++) {
      $quiz = new Quiz();
      $quiz
        ->setName($this->faker->words(2, true));
      $quizzes[] = $quiz;
      $manager->persist($quiz);
    }
    $quizzesTemp = $quizzes;

    // Question
    $questions = [];
    for ($i = 0; $i < 30; $i++) {
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
      $edition = $this->faker->randomElement($editions);
      $randomHours = rand(1, 6);
      $startAt = $edition->getStartAt()->modify($randomHours . " hour");
      $workshop
        ->setName($this->faker->randomElement($workshopsArray)['name'])
        ->setDescription($this->faker->randomElement($workshopsArray)['description'])
        ->setRoom($this->faker->randomElement($rooms))
        ->setSector($this->faker->randomElement($sectors))
        ->setEdition($edition)
        ->setStartAt($startAt)
        ->setEndAt($startAt->modify('+1 hour'));

      if ($this->faker->boolean()) {
        if (!isEmpty($quizzesTemp)) {
          $randomIndex = array_rand($quizzesTemp);
          $workshop->setQuiz($quizzesTemp[$randomIndex]);
          unset($quizzesTemp[$randomIndex]);
        }
      }

      $capacityMaximum = $workshop->getRoom()->getCapacityMaximum();
      // On filtre les étudiants par édition
      $studentsTemp = array_filter($students, function ($student) use ($edition) {
        return $student->getEdition() === $edition;
      });
      for ($w = 0; $w < mt_rand(1, $capacityMaximum); $w++) {
        if (empty($studentsTemp)) break;
        $randomIndex = array_rand($studentsTemp);
        $workshop->addStudent($studentsTemp[$randomIndex]);
        unset($studentsTemp[$randomIndex]);
      }

      $speakersTemp = $speakers;
      for ($w = 0; $w < mt_rand(1, 5); $w++) {
        if (empty($speakersTemp)) break;
        $randomIndex = array_rand($speakersTemp);
        $workshop->addSpeaker($speakersTemp[$randomIndex]);
        unset($speakersTemp[$randomIndex]);
      }

      for ($j = 0; $j < mt_rand(1, 5); $j++) {
        $workshop->addJob($this->faker->randomElement($jobs));
      }

      $workshops[] = $workshop;
      $manager->persist($workshop);
    }

    // Resources
    $resources = [];
    for ($i = 0; $i < 20; $i++) {
      $resource = new Resource();
      $resource
        ->setName($this->faker->words(5, true) . '.pdf')
        ->setDescription($this->faker->sentence(mt_rand(5, 10)))
        ->setUrl($this->faker->url() . '/' . $resource->getName())
        ->setWorkshop($this->faker->randomElement($workshops));
      $resources[] = $resource;
      $manager->persist($resource);
    }

    $manager->flush();
  }
}
