<?php

namespace App\Controller\Admin;

use App\Entity\Activity;
use App\Entity\Edition;
use App\Entity\Job;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Resource;
use App\Entity\Room;
use App\Entity\School;
use App\Entity\Section;
use App\Entity\Sector;
use App\Entity\Skill;
use App\Entity\Speaker;
use App\Entity\Student;
use App\Entity\User;
use App\Entity\UserAnswer;
use App\Entity\Workshop;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(UserCrudController::class)->generateUrl();

        return $this->redirect($url);

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('App');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::section('Général'),
            MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Intervenants', 'fa fa-headphones', Speaker::class),
            MenuItem::linkToCrud('Edition', 'fa fa-calendar', Edition::class),

            MenuItem::section('Elèves'),
            MenuItem::linkToCrud('Lycéens', 'fa fa-graduation-cap', Student::class),
            MenuItem::linkToCrud('Ecoles', 'fa fa-school', School::class),
            MenuItem::linkToCrud('Classes', 'fa fa-glasses', Section::class),

            MenuItem::section('Ateliers'),
            MenuItem::linkToCrud('Ateliers', 'fa fa-chalkboard-user', Workshop::class),
            MenuItem::linkToCrud('Salles', 'fa fa-people-roof', Room::class),
            MenuItem::linkToCrud('Métiers', 'fa fa-user-doctor', Job::class),
            MenuItem::linkToCrud('Compétences', 'fa fa-book', Skill::class),
            MenuItem::linkToCrud('Activités', 'fa fa-chart-line', Activity::class),
            MenuItem::linkToCrud('Ressources', 'fa fa-shopify', Resource::class),
            MenuItem::linkToCrud('Secteurs', 'fa fa-location-crosshairs', Sector::class),

            MenuItem::section('Quizz'),
            MenuItem::linkToCrud('Quizz', 'fa fa-clipboard-question', Quiz::class),
            MenuItem::linkToCrud('Questions', 'fa fa-question', Question::class),
            MenuItem::linkToCrud('Réponses utilisateurs', 'fa fa-id-badge', UserAnswer::class),
        ];
    }
}
