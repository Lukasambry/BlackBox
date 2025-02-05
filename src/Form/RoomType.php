<?php

namespace App\Form;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a room name',
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Room name must be at least {{ limit }} characters long',
                        'maxMessage' => 'Room name cannot be longer than {{ limit }} characters',
                    ]),
                ],
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-lg bg-gray-800 border-gray-700 text-white',
                    'placeholder' => 'Enter room name',
                ],
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-300',
                ],
            ])
            ->add('maxCapacity', IntegerType::class, [
                'constraints' => [
                    new Range([
                        'min' => 2,
                        'max' => 10,
                        'notInRangeMessage' => 'The number of players must be between {{ min }} and {{ max }}',
                    ]),
                ],
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-lg bg-gray-800 border-gray-700 text-white',
                    'min' => 2,
                    'max' => 10,
                ],
                'label' => 'Maximum number of players',
                'label_attr' => [
                    'class' => 'block text-sm font-medium text-gray-300',
                ],
            ])
            ->add('isPrivate', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'rounded bg-gray-800 border-gray-700 text-purple-600',
                ],
                'label' => 'Private room',
                'label_attr' => [
                    'class' => 'text-sm font-medium text-gray-300',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
            'data_class' => Room::class,
            ]
        );
    }
}