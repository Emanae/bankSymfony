<?php

namespace App\Controller;

use App\Form\TransfertType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class TransferController extends AbstractController
{
    #[Route('/transfers', name: 'app_transfers')]
    public function transfer(UserInterface $user,Request $request,EntityManagerInterface $em)
    {
            $form = $this->createForm(TransferType::class, null, ["user" => $user]);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                dd($form);
            }

            return $this->render('account/transfer.html.twig',[
                'form' => $form->createView(),
            ]);
    }
}
