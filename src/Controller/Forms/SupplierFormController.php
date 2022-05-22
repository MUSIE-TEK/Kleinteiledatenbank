<?php

namespace App\Controller\Forms;

use App\Form\SupplierType;
use App\RepositoryHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[IsGranted('ROLE_USER')]
class SupplierFormController extends AbstractController
{
    /**
     * @var \App\RepositoryHelper
     */
    private RepositoryHelper $repositoryHelper;

    public function __construct(RepositoryHelper $repositoryHelper)
    {
        $this->repositoryHelper = $repositoryHelper;
    }

    //hier werden parametriesiete URLs generiert
    #[Route('/supplier', name: 'app_supplier_list')]
    public function supplierList(Request $request, EntityManagerInterface $entityManager): Response
    {
        $supplierRepository = $this->repositoryHelper->getSupplierRepository();
        $suppliers          = $supplierRepository->findAll();

        return $this->render('forms/supplier/list.html.twig', [
            'suppliers' => $suppliers,
            'searchParam' => '',
            'listUrl' =>  $this->generateUrl('app_supplier_list',  [], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    //hier werden parametriesiete URLs generiert
    #[Route('/supplier/search/{search}', name: 'app_supplier_search')]
    public function supplierListSearch($search, Request $request, EntityManagerInterface $entityManager): Response
    {
        $supplierRepository = $this->repositoryHelper->getSupplierRepository();
        $suppliers           = $supplierRepository->findByQuery($search);

        return $this->render('forms/supplier/list.html.twig', [
            'suppliers' => $suppliers,
            'searchParam' => $search,
            'listUrl' =>  $this->generateUrl('app_supplier_list',  [], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    //hier werden parametriesiete URLs generiert
    #[Route('/supplier/{supplierId}', name: 'app_supplier_edit')]
    public function supplierEdit($supplierId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $supplierRepository = $this->repositoryHelper->getSupplierRepository();
        $supplier           = $supplierRepository->find($supplierId);

        $form = $this->createForm(SupplierType::class, $supplier);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($supplier)) {
                $supplierRepository->save($supplier);
            }
            return $this->redirectToRoute('app_supplier_list');
        }

        return $this->render('forms/supplier/edit.html.twig', [
            'supplierEditForm' => $form->createView(),
            'supplierId' => $supplierId,
            'title' => 'erstellen',
        ]);
    }

    //hier werden parametriesiete URLs generiert
    #[Route('/supplier/create', name: 'app_supplier_create')]
    public function supplierCreate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $supplierRepository = $this->repositoryHelper->getSupplierRepository();
        $supplier           = $this->entityHelper->getSupplierEntity();

        $form = $this->createForm(SupplierType::class, $supplier);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $supplierRepository->save($supplier);
            return $this->redirectToRoute('app_supplier_list');
        }

        return $this->render('forms/supplier/edit.html.twig', [
            'supplierEditForm' => $form->createView(),
            'title' => 'erstellen',
        ]);
    }
}
