<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Form\TransfertType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(Request $request, UserInterface $user, EntityManagerInterface $em): Response
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $account = $form->getData();
            $account->setUser($user);
            $account->setAmount(0);

            $em->persist($account);
            $em->flush();
            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('account/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    #[Route('/account/{id}', name: 'app_get_account')]
    public function account(Account $account, UserInterface $user)
    {
        if ($account->getUser() !== $user){
            $this->redirectToRoute('app_homepage');
        }

        return $this->render('account/account.html.twig',[
            'account' =>$account,
        ]);
    }


    #[Route('/account/{id}/reload', name: 'app_account_reload')]
    public function reload(Account $account, UserInterface $user,Request $request,EntityManagerInterface $em)
    {
        if ($account->getUser() !== $user){
        $this->redirectToRoute('app_homepage');
        }

        $form = $this->createFormBuilder ()
        ->add('card', TextType::class)
        ->add('amount', NumberType::class)
        ->add('submit', SubmitType::class)
        ->getForm();

        $form ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $account->setAmount($account->getAmount() + abs($data['amount']));
            $em->persist($account);
            $em->flush();

            return $this->redirectToRoute('app_get_account',['id'=>$account->getId()]);
        }


        return $this->render('account/reload.html.twig',[ 
            'form' => $form->createView()
        ]);
    }
}