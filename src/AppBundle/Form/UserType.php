<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\User;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!empty($options) && $options["type"] == "signin") {
            $builder
                ->add('login', TextType::class)
                ->add("password", PasswordType::class)
                ->add('Submit', SubmitType::class, array('label' => 'Sign in'))
                ->setMethod('post');
        } else {
            $builder
                ->add('login', TextType::class)
                ->add('name', TextType::class)
                ->add("password", PasswordType::class)
                ->add("picurl", FileType::class)
                ->add('Submit', SubmitType::class, array('label' => 'Sign Up'))
                ->setMethod('post');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'type' => "signup",
        ));
    }
}
