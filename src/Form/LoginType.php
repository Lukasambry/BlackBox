<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-lg bg-gray-800 border-gray-700 text-white px-4 py-2'
                ],
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-300 mb-1'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-lg bg-gray-800 border-gray-700 text-white px-4 py-2'
                ],
                'toggle' => true,
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-300 mb-1'
                ]
            ])
            ->add('remember_me', CheckboxType::class, [
                'label' => 'Remember me',
                'required' => false,
                'attr' => [
                    'class' => 'rounded bg-gray-800 border-gray-700'
                ],
                'label_attr' => [
                    'class' => 'ml-2 text-sm text-gray-300'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'authenticate',
        ]);
    }
}