<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Roles;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, array('label' => 'Prénom', 'attr' => array('require' => 'require', 'class' => 'form-control form-group')))

            ->add('nom', TextType::class, array('label' => 'Nom', 'attr' => array('require' => 'require', 'class' => 'form-control form-group')))

            ->add('email', EmailType::class, array('label' => 'Adresse email', 'attr' => array('require' => 'require', 'class' => 'form-control form-group')))

            ->add('password', PasswordType::class, array('label' => 'Mot de passe', 'attr' => array('require' => 'require', 'class' => 'form-control form-group')))

            ->add('roles', EntityType::class, [
                'label' => 'Rôle(s) de l\'utilisateur',
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

            ->add('Valider', SubmitType::class, array('attr' => array('class' => 'btn btn-success form-group')));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}