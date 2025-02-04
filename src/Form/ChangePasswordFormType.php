<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                'type' => PasswordType::class,
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'mt-1 block w-full rounded-lg bg-gray-800 border-gray-700 text-white px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent'
                    ],
                ],
                'first_options' => [
                    'label' => 'New password',
                    'label_attr' => [
                        'class' => 'block text-sm font-medium text-gray-300 mb-1'
                    ],
                    'toggle' => true,
                ],
                'second_options' => [
                    'label' => 'Confirm new password',
                    'label_attr' => [
                        'class' => 'block text-sm font-medium text-gray-300 mb-1'
                    ],
                    'toggle' => true,
                ],
                'invalid_message' => 'The password fields must match.',
                'constraints' => [
                    new NotBlank(
                        [
                        'message' => 'Please enter a password',
                        ]
                    ),
                    new Length(
                        [
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                        ]
                    ),
                ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
