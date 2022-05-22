<?php

namespace App\Controller\Forms;

use App\EntityHelper;
use App\Form\TonerType;
use App\RepositoryHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

#[IsGranted('ROLE_USER')]
class TonerFormController extends AbstractController
{
    private RepositoryHelper $repositoryHelper;

    private Security $security;

    private EntityHelper $entityHelper;

    public function __construct(RepositoryHelper $repositoryHelper, Security $security, EntityHelper $entityHelper)
    {
        $this->repositoryHelper = $repositoryHelper;
        $this->security = $security;
        $this->entityHelper = $entityHelper;
    }
    //hier werden parametriesiete URLs generiert
    #[Route('/toner', name: 'app_toner_list')]
    public function tonerList(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tonerRepository = $this->repositoryHelper->getTonerRepository();
        $toners          = $tonerRepository->findAll();

        return $this->render('forms/toner/list.html.twig', [
            'toners' => $toners,
            'searchParam' => '',
            'listUrl' =>  $this->generateUrl('app_toner_list',  [], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    //hier werden parametriesiete URLs generiert
    #[Route('/toner/search/{search}', name: 'app_toner_search')]
    public function tonerListSearch($search, Request $request, EntityManagerInterface $entityManager): Response
    {
        $tonerRepository = $this->repositoryHelper->getTonerRepository();
        $toners          = $tonerRepository->findByQuery($search);

        return $this->render('forms/toner/list.html.twig', [
            'toners' => $toners,
            'searchParam' => $search,
            'listUrl' =>  $this->generateUrl('app_toner_list',  [], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    //hier werden parametriesiete URLs generiert
    #[Route('/toner/show/{tonerId}', name: 'app_toner_show')]
    public function tonerShow($tonerId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $tonerRepository = $this->repositoryHelper->getTonerRepository();
        $toner           = $tonerRepository->find($tonerId);

        $form = $this->createForm(TonerType::class, $toner);

        return $this->render('forms/toner/edit.html.twig', [
            'tonerEditForm' => $form->createView(),
            'tonerId' => $tonerId,
            'title' => 'Detailansicht',
        ]);
    }

    //hier werden parametriesiete URLs generiert
    #[Route('/toner/edit/{tonerId}', name: 'app_toner_edit')]
    public function tonerEdit($tonerId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $tonerRepository = $this->repositoryHelper->getTonerRepository();
        $toner           = $tonerRepository->find($tonerId);

        $form = $this->createForm(TonerType::class, $toner);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($toner)) {
                $tonerRepository->save($toner);
            }
            return $this->redirectToRoute('app_toner_list');
        }

        return $this->render('forms/toner/edit.html.twig', [
            'tonerEditForm' => $form->createView(),
            'tonerId' => $tonerId,
            'title' => 'bearbeiten',
        ]);
    }

    //hier werden parametriesiete URLs generiert
    #[Route('/toner/create', name: 'app_toner_create')]
    public function tonerCreate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tonerRepository = $this->repositoryHelper->getTonerRepository();
        $toner           = $this->entityHelper->getTonerEntity();

        $form = $this->createForm(TonerType::class, $toner);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $tonerRepository->save($toner);
            return $this->redirectToRoute('app_toner_list');
        }

        return $this->render('forms/toner/edit.html.twig', [
            'tonerEditForm' => $form->createView(),
            'title' => 'erstellen',
        ]);
    }

    //hier werden parametriesiete URLs generiert
    #[Route('/toner/delete/{tonerId}', name: 'app_toner_delete')]
    public function tonerDelete($tonerId, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $tonerRepository = $this->repositoryHelper->getTonerRepository();
            $toner           = $tonerRepository->find($tonerId);

            $entityManager->remove($toner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_toner_list');
    }
}
