<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use App\Entity\Caisse;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;



class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return parent::index();

    }

   

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MyCaisse')
             ;  
    }

    public function configureMenuItems(): iterable
    {    
        
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        //MenuItem::linkToLogout('Logout', 'fa fa-exit'),];
        //yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'homepage');
        //yield MenuItem::linkToCrud('Caisses', 'fas fa-map-marker-alt', Caisse::class);
    }
}
