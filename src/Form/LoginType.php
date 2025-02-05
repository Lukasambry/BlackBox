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
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'Email',
                    'attr' => [
                        'class' => 'mt-2 block w-full rounded-lg bg-gray-800 border-gray-700 text-white px-4 py-2'
                    ],
                    'label_attr' => [
                        'class' => 'mt-1 block text-sm font-medium text-gray-300 mb-1'
                    ]
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'label' => 'Password',
                    'toggle' => true,
                    'attr' => [
                        'class' => 'mt-2 block w-full rounded-lg bg-gray-800 border-gray-700 text-white px-4 py-2'
                    ],
                    'label_attr' => [
                        'class' => 'mt-1 block text-sm font-medium text-gray-300 mb-1'
                    ]
                ]
            )
            ->add(
                '_remember_me',
                CheckboxType::class,
                [
                    'label'    => 'Remember me',
                    'required' => false,
                    'attr'     => [
                        'class' => 'my-4 mx-2 rounded bg-gray-800 border-gray-700'
                    ],
                    'label_attr' => [
                        'class' => 'text-sm text-gray-300'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'csrf_protection' => true,
                'csrf_field_name' => '_csrf_token',
                'csrf_token_id'   => 'authenticate',
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
