<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add(
                'agreeTerms',
                CheckboxType::class,
                [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(
                        [
                        'message' => 'You should agree to our terms.',
                        ]
                    ),
                ],
                ]
            )
            ->add(
                'plainPassword',
                PasswordType::class,
                [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'toggle' => true,
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
            )
            ->add(
                'confirmPassword',
                PasswordType::class,
                [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'toggle' => true,
                'constraints' => [
                    new NotBlank(
                        [
                        'message' => 'Please confirm your password',
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
            )
            ->add(
                'nickname',
                null,
                [
                'label' => 'Nickname',
                'constraints' => [
                    new NotBlank(
                        [
                        'message' => 'Please enter a nickname',
                        ]
                    ),
                    new Length(
                        [
                        'min' => 3,
                        'minMessage' => 'Your nickname should be at least {{ limit }} characters',
                        'max' => 255,
                        ]
                    ),
                ],
                ]
            );

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $plainPassword = $form->get('plainPassword')->getData();
                $confirmPassword = $form->get('confirmPassword')->getData();

                if ($plainPassword !== $confirmPassword) {
                    $form->get('confirmPassword')->addError(new FormError('Passwords do not match.'));
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
            'data_class' => User::class,
            ]
        );
    }
}
