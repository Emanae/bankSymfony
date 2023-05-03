<?php

namespace App\Form;


use App\Repository\AccountRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransfertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sender', EntityType::class, [
                'class' => Account::class,
                'query_builder' => function (AccountRepository $er,$options){
                    return $er->createQueryBuilder('a')
                    ->where('user');
                    
                },
                'choice_label' =>'name',
            ])
            ->add('receiver', TextType::class)
            ->add('amount', NumberType::class)
            ->add('submit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            "user" => null
            // Configure your form options here
        ]);
    }
}
