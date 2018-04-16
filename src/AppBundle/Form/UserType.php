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

/**
 * Class UserType
 * @package AppBundle\Form
 */
class UserType extends AbstractType
{

    /**
     * @var string
     */
    const signIn = "Sign In";

    /**
     * @var string
     */
    const signUp = "Sign Up";

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!empty($options) && $options["type"] == self::signIn) {
            $builder
                ->add('login', TextType::class)
                ->add("password", PasswordType::class)
                ->add('Submit', SubmitType::class, array('label' => self::signIn))
                ->setMethod('post');
        } else {
            $builder
                ->add('login', TextType::class)
                ->add('name', TextType::class)
                ->add("password", PasswordType::class)
                ->add("picurl", FileType::class)
                ->add('Submit', SubmitType::class, array('label' => self::signUp))
                ->setMethod('post');
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'type' => self::signUp,
        ));
    }
}
