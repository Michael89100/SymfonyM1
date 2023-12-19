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
            MenuItem::section('General'),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class),
            MenuItem::linkToCrud('Speakers', 'fa fa-headphones', Speaker::class),
            MenuItem::linkToCrud('Edition', 'fa fa-calendar', Edition::class),

            MenuItem::section('Student'),
            MenuItem::linkToCrud('Schools', 'fa fa-school', School::class),
            MenuItem::linkToCrud('Sections', 'fa fa-graduation-cap', Section::class),

            MenuItem::section('Workshop'),
            MenuItem::linkToCrud('Workshops', 'fa fa-cahlkboard-user', Workshop::class),
            MenuItem::linkToCrud('Rooms', 'fa fa-people-roof', Room::class),
            MenuItem::linkToCrud('Jobs', 'fa fa-doctor', Job::class),
            MenuItem::linkToCrud('Skills', 'fa fa-book', Skill::class),
            MenuItem::linkToCrud('Activities', 'fa fa-chart-line', Activity::class),
            MenuItem::linkToCrud('Resources', 'fa fa-shopify', Resource::class),
            MenuItem::linkToCrud('Sectors', 'fa fa-location-crosshairs', Sector::class),

            MenuItem::section('Quiz'),
            MenuItem::linkToCrud('Quizzes', 'fa fa-clipboard-question', Quiz::class),
            MenuItem::linkToCrud('Questions', 'fa fa-question', Question::class),
            MenuItem::linkToCrud('Users answers', 'fa fa-id-badge', UserAnswer::class),
        ];
    }
}
