<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use App\Form\UserType;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
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
    public function adminFormArticle(EntityManagerInterface $manager, Article $article = null, Request $request): Response
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
     * @Route("/admin/{id}/delete-article", name="admin_delete_article")
     */
    public function deleteArticle(EntityManagerInterface $manager, Article $article)
    {
        $manager->remove($article);
        $manager->flush();

        $this->addFlash('success', "L'article a bien été supprimé");

        return $this->redirectToRoute('admin_articles');
    }









    /**
     * @Route("/admin/categories", name="admin_categories")
     */
    public function admincategories(EntityManagerInterface $manager, CategoryRepository $repo): Response
    {

        $colonnes = $manager->getClassMetadata(Category::class)->getFieldNames();
        dump($colonnes);

        $categories = $repo->findAll();
        dump($categories);

        return $this->render('admin/admin_categories.html.twig', [
            'colonnes' => $colonnes,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/categories/new", name="admin_new_categorie")
     * @Route("/admin/categories/{id}/edit", name="admin_edit_categorie")
     */
    public function adminFormCategories(EntityManagerInterface $manager, Category $category = null, Request $request): Response
    {
        if (!$category) {
            $category = new Category;
        }
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        dump($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$category->getId()) {
                $this->addFlash('success', "La categorie a bien été enregistré");
            }

            else{
                $this->addFlash('success', "La categorie a bien été modifié");
            }

            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/admin_create_category.html.twig', [
            'formCategory'  => $form->createView(),
            'editMode'      => $category->getId()
        ]);
    }

    /**
     * @Route("/admin/{id}/delete-categorie", name="admin_delete_categorie")
     */
    public function deleteCategory(EntityManagerInterface $manager, Category $category)
    {
        if($category->getArticles()->isEmpty())
        {
            $manager->remove($category);
            $manager->flush();

            $this->addFlash('success', "La categorie a bien été supprimé");
        }

        else
        {
            $this->addFlash('warning', "Impossible de supprimer une catégorie qui est associés à un ou plusieur article(s)");
        }
        return $this->redirectToRoute('admin_categories');
    }














    /**
     * @Route("/admin/membres", name="admin_membres")
     */
    public function adminMembres(EntityManagerInterface $manager, UserRepository $repo): Response
    {

        $colonnes = $manager->getClassMetadata(User::class)->getFieldNames();
        dump($colonnes);

        $membres = $repo->findAll();
        dump($membres);

        return $this->render('admin/admin_membres.html.twig', [
            'colonnes' => $colonnes,
            'membres' => $membres
        ]);
    }

        /**
     * @Route("/admin/membres/new", name="admin_new_membre")
     * @Route("/admin/membres/{id}/edit", name="admin_edit_membre")
     */
    public function adminFormMembres(EntityManagerInterface $manager, User $user, Request $request): Response
    {
        if (!$user) {
            $user = new user;
        }
        $form = $this->createForm(MembreType::class, $user);
        $form->handleRequest($request);

        dump($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user->getId()) {
                $this->addFlash('success', "La membre a bien été enregistré");
            }

            else{
                $this->addFlash('success', "La membre a bien été modifié");
            }

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('admin_membres');
        }

        return $this->render('admin/admin_create_membre.html.twig', [
            'formmembre'  => $form->createView(),
            'editMode'      => $user->getId()
        ]);
    }

    /**
     * @Route("/admin/{id}/delete-membre", name="admin_delete_membre")
     */
    public function deletemembre(EntityManagerInterface $manager, User $user)
    {
        $manager->remove($user);
        $manager->flush();

        $this->addFlash('success', "La membre a bien été supprimé");

        return $this->redirectToRoute('admin_membres');
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



}


