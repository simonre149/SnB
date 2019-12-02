<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends AbstractController
{
    public function index(MessageRepository $messageRepository)
    {
        $allMessages = $messageRepository->findAllByCurrentUserId($this->getUser()->getId());
        $allContactedUsers = [];
        $finalContactedUsers = [];

        for($i = 0; $i < count($allMessages); $i++)
        {
            array_push($allContactedUsers, $allMessages[$i]->getUserOne()->getId());
            array_push($allContactedUsers, $allMessages[$i]->getUserTwo()->getId());
        }

        return $this->render('pages/messages.html.twig', [
            'current_menu' => 'messages',
            'allContactedUsers' => $allContactedUsers
        ]);
    }

    public function usermessages($user_id, Request $request, ObjectManager $manager, UserRepository $userRepository, MessageRepository $messageRepository)
    {
        $user1 = $this->getUser();
        $user2 = $userRepository->findOneById($user_id);

        if ($user1->getId() == $user_id) return $this->redirectToRoute('home');

        $message = new Message();
        $message->setSentAt(new \DateTime());
        $message->setUserOne($user1);
        $message->setUserTwo($user2);

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($message);
            $manager->flush();

            return $this->redirect($request->getUri());
        }

        $allMessages = $messageRepository->findAll();

        return $this->render('pages/usermessages.html.twig',[
            'current_menu' => 'messages',
            'form' => $form->createView(),
            'allMessages' => $allMessages
        ]);
    }
}
