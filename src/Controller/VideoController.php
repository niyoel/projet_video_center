<?php

namespace App\Controller;
use App\Entity\Video;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\VideoType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class VideoController extends AbstractController
{
     #[Route('/', name: 'app_home')]
     public function index(ManagerRegistry $doctrine, VideoRepository $repo): Response
     {
         return $this->render('video/index.html.twig', ['videos' => $repo->findAll()]);
     }
    #[Route('/video/{id<[0-9]+>}', name:'app_video_show', methods:'GET')]
    public function show(Video $video): Response
    {
        if (!$this->getUser() && $video->isIsPremium()){
            $this->addFlash('error','You have to be connected to see Premium videos');
            return $this->redirectToRoute('app_home');
        }
        return $this->render('video/show.html.twig', compact('video'));
    }    
    #[Route("/create", name:"app_video_create", methods: ["GET","POST"])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()){
            $this->addFlash('error','You have to be connected to create a video');
            return $this->redirectToRoute('app_home');
        }
        $video = new Video;
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $video->setUser($this->getUser());
            $em->persist($video);
            $em->flush();
            $this->addFlash('success', 'video successfully created !'); 

            return $this->redirectToRoute('app_home');
        }
        return $this->render('video/create.html.twig', ['monForm' => $form->createView()]);   
       }
       #[Route('/video/{id<[0-9]+>}/edit', name:'app_video_edit', methods: ['GET', 'POST'])] 

       public function edit(Request $request, Video $video, EntityManagerInterface $em, User $user): Response
       {
            if (!$this->getUser() || $video->getUser()->getId()!=$user->getId()){
                $this->addFlash('error','You have to be the owner of the video');
                return $this->redirectToRoute('app_home');
            }
           $form = $this->createForm(VideoType::class, $video);
           $form->handleRequest($request);
           
           if ($form->isSubmitted() && $form->isValid()) {
               $em->flush();
               $this->addFlash('success', 'video successfully updated !'); 
               return $this->redirectToRoute('app_home');
           }
   
           return $this->render('video/edit.html.twig', [
                       'video' => $video,
                       'monForm' => $form->createView()
               ]);
       }
       #[Route("/video/{id<[0-9]+>}/delete", name:"app_video_delete")]
       
      public function delete(Video $video, EntityManagerInterface $em): Response
       {
        if (!$this->getUser() || $video->getUser()->getId()!=$user->getId()){
            $this->addFlash('error','You have to be the owner of the video to delete');
            return $this->redirectToRoute('app_home');
        }
            $em->remove($video);
               $em->flush();
               $this->addFlash('info', 'video successfully deleted !'); 
               return $this->redirectToRoute('app_home');
}
}