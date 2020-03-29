<?php

namespace App\Form;

use App\Entity\Team;
use App\Entity\User;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            // Set various options for the radio buttons
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'rookie' => 'ROLE_ROOKIE',
                    'advanced' => 'ROLE_ADVANCED',
                ],
                'choice_label' => function($choice) {
                    // only way to get proper labels on the radio buttons
                    if($choice === 'ROLE_ROOKIE') {
                        return 'Rookie';
                    } else {
                        return 'Advanced';
                    }
                },
                'mapped' => false,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('randomField', TextareaType::class, array(
                "mapped" => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
