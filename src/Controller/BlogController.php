<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    // chaque méthode du controller est associé à une route bien spécifique 
    //Losque nous envoyons la route '/blog' dans l'url du navig ......

    /**
     * @Route("/blog", name="blog")
     */

    public function index(ArticleRepository $repo): Response
    {
        //$repo = $this->getDoctrine()->getRepository(Article::class);
        // dump($repo);
        
        $articles = $repo->findAll();
        dump($articles);

        return $this->render('blog/index.html.twig', 
        [
        'articles' => $articles
        ]);
    }
















    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', 
        [
            'title' => 'Bienvenue sur le blog Symfony',
            'age' => 25
        ]);
    }













    /**
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */

    public function  form(Article $article = null, Request $request, EntityManagerInterface $manager)
    {
        dump($request);

        // if($request->request->count() > 0)
        // {
        //     $article = new Article;
        //     $article->setTitle($request->request->get('title'))
        //             ->setContent($request->request->get('content'))
        //             ->setImage($request->request->get('image'))
        //             ->setCreatedAt(new \DateTime());
                    
        //     $manager->persist($article);
        //     $manager->flush();

        //     return $this->redirectToRoute('blog_show', [
        //         'id' => $article->getId()
        //     ]);
        // }
        if(!$article){
            $article = new Article;            
        }

        // $form = $this->createFormBuilder($article)
        //             ->add('title')
        //             ->add('content')
        //             ->add('image')
        //             ->getForm();
        
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        dump($request);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!$article->getId())
            {
                $article->setCreatedAt(new\DateTime());
            }

            $article->setCreatedAt(new \DateTime());

            $manager->persist($article);
            $manager->flush(); 

            return $this->redirectToRoute('blog_show' , [
                'id' => $article->getId()
            ]);
        }

        return $this->render('blog/create.html.twig' , [
            'formArticle'   => $form->createView(),
            'editMode'      => $article->getId()
        ]);
    }








    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article ): Response
    { 
        //$repo = $this->getDoctrine()->getRepository(Article::class);

       // $article = $repo->find($id);
        dump($article);

        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }

}
