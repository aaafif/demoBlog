<?php

namespace App\Controller;


use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/articles", name="admin_articles")
     */
    public function adminArticles(EntityManagerInterface $manager, ArticleRepository $repo): Response
    {
        $colonnes = $manager->getClassMetadata(Article::class)->getFieldNames();
        dump($colonnes);

        $articles = $repo->findAll();
        dump($articles);

        return $this->render('admin/admin_articles.html.twig', [
            'colonnes' => $colonnes,
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/admin/articles/new", name="admin_new_article")
     * @Route("/admin/articles/{id}/edit", name="admin_edit_article")
     */
    public function adminForm(EntityManagerInterface $manager, Article $article = null, Request $request): Response
    {
        if (!$article) {
            $article = new Article;
        }
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        dump($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreatedAt(new \DateTime);
                $this->addFlash('success', "L'article a bien été enregistré");
            }

            else{
                $this->addFlash('success', "L'article a bien été modifié");
            }

            $article->setCreatedAt(new \DateTime);
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('admin_articles');
        }

        return $this->render('admin/admin_create.html.twig', [
            'formArticle'   => $form->createView(),
            'editMode'      => $article->getId()
        ]);
    }





    /**
     * @Route("/admin/membres", name="admin_membres")
     */
    public function adminMembres(EntityManagerInterface $manager): Response
    {

        $colonnes = $manager->getClassMetadata(Atricle::class)->getFieldNames();

        dump($colonnes);

        return $this->render('admin/admin_membres.html.twig');
    }


    /**
     * @Route("/admin/commentaires", name="admin_commentaires")
     */
    public function adminCommentaires(EntityManagerInterface $manager): Response
    {

        $colonnes = $manager->getClassMetadata(Atricle::class)->getFieldNames();

        dump($colonnes);

        return $this->render('admin/admin_commentaires.html.twig');
    }


    /**
     * @Route("/admin/categories", name="admin_categories")
     */
    public function admincategories(EntityManagerInterface $manager): Response
    {

        $colonnes = $manager->getClassMetadata(Atricle::class)->getFieldNames();

        dump($colonnes);

        return $this->render('admin/admin_categories.html.twig');
    }
}
