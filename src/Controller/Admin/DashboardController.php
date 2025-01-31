<?php

namespace App\Controller\Admin;

use App\Entity\Log;
use App\Entity\Room;
use App\Entity\Secret;
use App\Entity\Theme;
use App\Entity\User;
use App\Entity\Vote;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect(
            $adminUrlGenerator->setController(UserCrudController::class)->generateUrl()
        );
    }

    public function configureMenuItems(): iterable
    {
        yield menuItem::linkToDashboard('Tableau de bord', 'fa fa-home');

        yield MenuItem::linkToCrud('Logs', 'fa fa-history', Log::class);
        yield menuItem::linkToCrud('Themes', 'fas fa-tags', Theme::class);
        yield menuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield menuItem::linkToCrud('Rooms', 'fas fa-door-closed', Room::class);
        yield menuItem::linkToCrud('Secrets', 'fas fa-key', Secret::class);
        yield menuItem::linkToCrud('Votes', 'fas fa-thumbs-up', Vote::class);
    }
}
