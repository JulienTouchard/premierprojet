<?php

namespace App\Form;

use App\Entity\Moviesfull;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoviesfullType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('slug')
            ->add('title')
            ->add('year')
            ->add('genres')
            ->add('plot')
            ->add('directors')
            ->add('cast')
            ->add('writers')
            ->add('runtime')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Moviesfull::class,
        ]);
    }
}
