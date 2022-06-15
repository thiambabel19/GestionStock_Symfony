<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Roles;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('email', EmailType::class, array('label' => 'Adresse email', 'attr' => array('require' => 'require', 'class' => 'form-control form-group')))

        ->add('prenom', TextType::class, array('label' => 'Prénom', 'attr' => array('require' => 'require', 'class' => 'form-control form-group')))

        ->add('nom', TextType::class, array('label' => 'Nom', 'attr' => array('require' => 'require', 'class' => 'form-control form-group')))

        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new IsTrue([
                    'message' => 'You should agree to our terms.',
                ]),
            ],
            
        ])
        ->add('plainPassword', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password', 'class' => 'form-control form-group'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Le mot de passe doit comporter au moins {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ])

        ->add('roles', EntityType::class, [
            //'label' => 'Rôle(s) de l\'utilisateur',
            'attr' => array('require' => 'require', 'class' => 'form-control form-group'),
            'class' => Roles::class,
            'query_builder' => function(EntityRepository $r){
                $query = $r->createQueryBuilder('r');
                return $query;
            },
            'choice_label' => 'nomRole',
            'placeholder' => 'Select',
            'multiple' => true,
            'expanded' => true,
            'mapped' => false,
            'constraints' => array(
             new \Symfony\Component\Validator\Constraints\Count(['min' => 1, 'minMessage' => 'Veuillez selectionner un rôle pour l\'utilisateur...'])
            )
            //'attr' => ['data-placeholder' => 'choose roles']
        ])
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}