<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class JobType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {

        $builder
            ->add('type')
            ->add('company')
            ->add('description')
            ->add('expires_at', null, [
                'widget' => 'single_text'
            ])
            ->add('email')
            ->add('image', EntityType::class, [
                'class' => Image::class,
                'choice_label' => 'alt',
            ])
            ->add('valider', SubmitType::class);
    }

    public function configureOptions(
        OptionsResolver $resolver
    ): void {

        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
