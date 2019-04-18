<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder->add('username', TextType::class)
       ->add('email', TextType::class)
       ->add('password', PasswordType::class,[
           'constraints' => [
               new NotBlank([
                   'message' => 'Please enter a password'
               ]),
               new Length([
                   'min' => 6,
                   'minMessage' => 'Your password should be at least {{ limit }} characters',
                   // max length allowed by Symfony for security reasons
                   'max' => 4096,
               ])
        ]])
       ->add('image', FileType::class, ['required' => false ])
       ->add('first_name', TextType::class)
       ->add('last_name', TextType::class)
       ->add('phone', NumberType::class)
       ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}