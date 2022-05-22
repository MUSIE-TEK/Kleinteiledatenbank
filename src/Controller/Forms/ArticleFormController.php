<?php
//Namespace zeigt den Platz in der Verzeichnisstruktur
namespace App\Controller\Forms;

use App\EntityHelper;
use App\Form\ArticleType;
use App\RepositoryHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

//Attributes: Nur berechtigte mit mindestens ROLE USER dürfen die Routen öffnen
#[IsGranted('ROLE_USER')]
class ArticleFormController extends AbstractController
{
    private RepositoryHelper $repositoryHelper;

    private Security $security;

    private EntityHelper $entityHelper;

    //Constructor: private Klassenvariablen als Service übergeben
    public function __construct(RepositoryHelper $repositoryHelper, Security $security, EntityHelper $entityHelper)
    {
        $this->repositoryHelper = $repositoryHelper;
        $this->security = $security;
        $this->entityHelper = $entityHelper;
    }


    #[Route('/article', name: 'app_article_list')]
    //
    public function articleList(Request $request, EntityManagerInterface $entityManager): Response
    {
        $articleRepository = $this->repositoryHelper->getArticleRepository();
        $articles          = $articleRepository->findAll();

        //gibt eine Responce zurück, nimmt eine twig-Datei an und nutzt übergebene Parameter in twig/html
        // the `render()` method returns a `Response` object with the
        // contents created by the template
        return $this->render('forms/article/list.html.twig', [
            'articles' => $articles,
            'searchParam' => '',
            //dynamische Generierung der absoluten URL
            'listUrl' =>  $this->generateUrl('app_article_list',  [], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    //hier werden parametriesiete URLs generiert
    #[Route('/article/search/{search}', name: 'app_article_search')]
    public function articleListSearch($search, Request $request, EntityManagerInterface $entityManager): Response
    {
        $articleRepository = $this->repositoryHelper->getArticleRepository();
        $articles          = $articleRepository->findByQuery($search);

        return $this->render('forms/article/list.html.twig', [
            'articles' => $articles,
            'searchParam' => $search,
            'listUrl' =>  $this->generateUrl('app_article_list',  [], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }


    #[Route('/article/show/{articleId}', name: 'app_article_show')]
    public function articleShow($articleId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $articleRepository = $this->repositoryHelper->getArticleRepository();
        $article           = $articleRepository->find($articleId);

        $form = $this->createForm(ArticleType::class, $article);

        return $this->render('forms/article/edit.html.twig', [
            'articleEditForm' => $form->createView(),
            'articleId' => $articleId,
            'title' => 'Detailansicht',
        ]);
    }

    #[Route('/article/edit/{articleId}', name: 'app_article_edit')]
    public function articleEdit($articleId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $articleRepository = $this->repositoryHelper->getArticleRepository();
        $article           = $articleRepository->find($articleId);

        $form = $this->createForm(ArticleType::class, $article);

        //prüft, ob form ausgefüllt wurde
        $form->handleRequest($request);
        //prüft, ob ausgefüllte form, bestätigt, geprüft und ausgeführt wurde
        if ($form->isSubmitted() && $form->isValid()) {
            //isset, prüft ob $article vorhanden ist
            if (isset($article)) {
                //speichert vorhanden Artikel
                $articleRepository->save($article);
            }
            //falls Form bestätigt, weiterleiten zu Artikelliste
            return $this->redirectToRoute('app_article_list');
        }

        return $this->render('forms/article/edit.html.twig', [
            //erstellt aus Form eine View
            'articleEditForm' => $form->createView(),
            'articleId' => $articleId,
            'title' => 'bearbeiten',
        ]);
    }
    //hier werden parametriesiete URLs generiert
    #[Route('/article/create', name: 'app_article_create')]
    public function articleCreate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $articleRepository = $this->repositoryHelper->getArticleRepository();
        $article           = $this->entityHelper->getArticleEntity();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $articleRepository->save($article);
            return $this->redirectToRoute('app_article_list');
        }

        return $this->render('forms/article/edit.html.twig', [
            'articleEditForm' => $form->createView(),
            'title' => 'erstellen',
        ]);
    }

    //hier werden parametriesierte URLs generiert
    #[Route('/article/delete/{articleId}', name: 'app_article_delete')]
    public function articleDelete($articleId, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $articleRepository = $this->repositoryHelper->getArticleRepository();
            $article           = $articleRepository->find($articleId);

            //lösche diese Zeile im entityManager
            $entityManager->remove($article);
            //übernimmt die Änderung vom entityManager in die DB
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_list');
    }
}
