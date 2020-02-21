<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{

    private $articleRepository;

    /**
     * @Route("/articles", methods={"GET"}, name="articles_index")
     */
    public function index(ArticleRepository $articleRepository) : Response
    {

        $articles = $articleRepository->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/articles/{article}", name="articles_show", requirements={"article"="\d+"})
     */
    public function show(Article $article)
    {

        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/articles/new", name="articles_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {

        $article = new Article;
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article ' . $article->getTitle() . ' a bien été ajouté.');
            $this->addFlash('danger', 'L\'article a été supprimé.');
            $this->addFlash('warning', 'L\'article a été modifié.');

            return $this->redirectToRoute('articles');
        }

        return $this->render('articles/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/articles/search", name="articles_search", methods={"GET"})
     */
    public function search(Request $request, ArticleRepository $articleRepository)
    {
        $searchTerm = $request->query->get('search');

        if ($searchTerm == '') {
            $articles = $articleRepository->findAll();
        }
        else {
            $articles = $articleRepository->findByAllFields($searchTerm);
        }



        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}