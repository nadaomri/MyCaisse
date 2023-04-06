<?php

namespace App\Controller;

use App\Entity\Caisse;
use App\Form\CaisseType;
use App\Repository\CaisseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\PdfService;
use App\Service\UploaderService;
use App\Event\ListAllCaissesEvent;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;


#[Route('/caisse')]
class CaisseController extends AbstractController
{  

    #[Route('/', name: 'app_caisse_index', methods: ['GET'])]
    public function index(Request $request, CaisseRepository $caisseRepository, PaginatorInterface $paginator ): Response
    {     
        $caisse = $caisseRepository->findAll();
          
        $caisse = $paginator->paginate(
            $caisse, /* query NOT result */
            $request->query->getInt('page', 1), 10);

        return $this->render('caisse/index.html.twig', [
            'caisses' => $caisse,
        ]);
    }

    #[Route('/list', name: 'app_caisse_list', methods: ['GET'])]
    public function list(Request $request, CaisseRepository $caisseRepository, PaginatorInterface $paginator ): Response
    {     
        $caisse = $caisseRepository->findAll();
          
        $caisse = $paginator->paginate(
            $caisse, /* query NOT result */
            $request->query->getInt('page', 1), 10);

        return $this->render('caisse/list.html.twig', [
            'caisses' => $caisse,
        ]);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('solde')
            ->add(DatetimeFilter::new('createdAt'))
        ;
    }
    

     #[Route('/pdf/{id}', name: 'details.pdf')]
    public function generatePdfCash(Caisse $caisse = null, PdfService $pdf) {
        $html = $this->render('caisse/detail.html.twig', ['caisses' => $caisse]);
        $pdf->showPdfFile($html);
    }                              

    #[Route('/new', name: 'app_caisse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CaisseRepository $caisseRepository, ManagerRegistry $doctrine): Response
    {
        $caisse = new Caisse();
        $form = $this->createForm(CaisseType::class, $caisse);
        $form->handleRequest($request);
        $em = $doctrine->getManager();

            $nvBalance = $caisse->getBalance();

        if ($form->isSubmitted() && $form->isValid()){
            $caisseRepository->add($caisse, true);
            
            //depense
            //echo ("nvSolde: ".$nvSolde.",     m= ".$caisse->getMontant()); //die();

                  if($nvBalance >= $caisse->getMontant()){
                  $nvBalance = $nvBalance - $caisse->getMontant();
                  $caisse->setBalance($nvBalance);
                  // tell Doctrine i want to (eventually) save the Caisse (no queries yet)
                  $em->persist($caisse);
                  // actually executes the queries (i.e. the INSERT query)
                  $em->flush();
                  return $this->render('caisse/show.html.twig', [
                    'caisse' => $caisse,
                        ]);
             
                }   
                else {
                    return $this->renderForm('caisse/new.html.twig', [
                                'caisse' => $caisse,
                                'form' => $form,
                            ]);
                } 

            //return $this->redirectToRoute('app_caisse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('caisse/new.html.twig', [
            'caisse' => $caisse,
            'form' => $form,
        ]);
    }

    #[Route('/alim', name: 'app_caisse_alim', methods: ['GET', 'POST'])]
    public function alim(Request $request, CaisseRepository $caisseRepository, ManagerRegistry $doctrine): Response
    {
        $caisse = new Caisse();
        $form = $this->createForm(CaisseType::class, $caisse);
        $form->handleRequest($request);
        $em = $doctrine->getManager();

            $nvBalance = $caisse->getBalance();

        if ($form->isSubmitted() && $form->isValid()){
            $caisseRepository->add($caisse, true);

                  $nvBalance = $nvBalance + $caisse->getMontant();
                  $caisse->setBalance($nvBalance);
                  // tell Doctrine i want to (eventually) save the Caisse (no queries yet)
                  $em->persist($caisse);
                  // actually executes the queries (i.e. the INSERT query)
                  $em->flush();
                  return $this->render('caisse/show.html.twig', [
                    'caisse' => $caisse,
                        ]);

            //return $this->redirectToRoute('app_caisse_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('caisse/new.html.twig', [
            'caisse' => $caisse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_caisse_show', methods: ['GET'])]
    #[IsGranted("ROLE_USER")]
    public function show(Caisse $caisse, Request $request): Response
    {   
         //$donnée = $this->getDoctrine()->getRepository(Caisse::class)->findOneBy(['name' => $name]);

        return $this->render('caisse/show.html.twig', ['caisse' => $caisse,]);
        
    }    

    #[Route('/{id}/edit', name: 'app_caisse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Caisse $caisse, CaisseRepository $caisseRepository): Response
    {   
         
        $form = $this->createForm(CaisseType::class, $caisse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $caisseRepository->add($caisse, true);

            return $this->redirectToRoute('app_caisse_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('caisse/edit.html.twig', [
            'caisse' => $caisse,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_caisse_delete', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function delete(Request $request, Caisse $caisse, CaisseRepository $caisseRepository): Response
    {   

        if ($this->isCsrfTokenValid('delete'.$caisse->getId(), $request->request->get('_token'))) {

            $caisseRepository->remove($caisse, true);
        }

        return $this->redirectToRoute('app_caisse_index', [], Response::HTTP_SEE_OTHER);
    }
}