<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends AbstractController
{
    public function index()
    {
        return $this->render('pages/messages.html.twig', [
            'current_menu' => 'messages'
        ]);
    }

    public function usermessages($user_id, Request $request, ObjectManager $manager, UserRepository $userRepository, MessageRepository $messageRepository)
    {
        $form = $this->createFormBuilder()->getForm();
        $user1 = $this->getUser();
        $user2 = $userRepository->findOneById($user_id);

        $message = new \App\Entity\Message();
        $message->setSentAt(new \DateTime());
        $message->setUserOne($user1);
        $message->setUserTwo($user2);

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($message);
            $manager->flush();

            return $this->redirectToRoute('home');
        }

        $allMessages = $messageRepository->findAll();

        return $this->render('pages/usermessages.html.twig',[
            'current_menu' => 'messages',
            'form' => $form->createView(),
            'allMessages' => $allMessages
        ]);
    }
}
