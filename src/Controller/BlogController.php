<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', []);
    }

    #[Route('/blog/detail', name: 'blog_detail')]
    public function detail(): Response
    {
        return $this->render('blog/detail.html.twig', []);
    }
}
