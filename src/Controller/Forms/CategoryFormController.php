<?php

namespace App\Controller\Forms;

use App\EntityHelper;
use App\Form\CategoryType;
use App\RepositoryHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[IsGranted('ROLE_USER')]
class CategoryFormController extends AbstractController
{
    private RepositoryHelper $repositoryHelper;

    private EntityHelper $entityHelper;

    public function __construct(RepositoryHelper $repositoryHelper, EntityHelper $entityHelper)
    {
        $this->repositoryHelper = $repositoryHelper;
        $this->entityHelper = $entityHelper;
    }
    //hier werden parametriesiete URLs generiert
    #[Route('/category', name: 'app_category_list')]
    public function categoryList(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoryRepository = $this->repositoryHelper->getCategoryRepository();
        $categories         = $categoryRepository->findAll();
        // the `render()` method returns a `Response` object with the
        // contents created by the template
        return $this->render('forms/category/list.html.twig', [
            'categories' => $categories,
            'searchParam' => '',
            'listUrl' =>  $this->generateUrl('app_category_list',  [], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }
    //hier werden parametriesiete URLs generiert
    #[Route('/category/search/{search}', name: 'app_category_search')]
    public function categoryListSearch($search, Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoryRepository = $this->repositoryHelper->getCategoryRepository();
        $categories         = $categoryRepository->findByQuery($search);

        return $this->render('forms/category/list.html.twig', [
            'categories' => $categories,
            'searchParam' => $search,
            'listUrl' =>  $this->generateUrl('app_category_list',  [], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }
    //hier werden parametriesiete URLs generiert
    #[Route('/category/create', name: 'app_category_create')]
    public function categoryCreate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoryRepository = $this->repositoryHelper->getCategoryRepository();
        $category           = $this->entityHelper->getCategoryEntity();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category);
            return $this->redirectToRoute('app_category_list');
        }

        return $this->render('forms/category/edit.html.twig', [
            'categoryEditForm' => $form->createView(),
            'title' => 'erstellen',
        ]);
    }

    //hier werden parametriesiete URLs generiert
    #[Route('/category/{categoryId}', name: 'app_category_edit')]
    public function categoryEdit($categoryId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $categoryRepository = $this->repositoryHelper->getCategoryRepository();
        $category           = $categoryRepository->find($categoryId);

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($category)) {
                $categoryRepository->save($category);
            }

            return $this->redirectToRoute('app_category_list');
        }

        return $this->render('forms/category/edit.html.twig', [
            'categoryEditForm' => $form->createView(),
            'categoryId' => $categoryId,
            'title' => 'bearbeiten',
        ]);
    }
}
