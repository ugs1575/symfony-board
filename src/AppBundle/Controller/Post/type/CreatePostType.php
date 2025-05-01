<?php

namespace AppBundle\Controller\Post\type;

use AppBundle\Controller\Post\dto\CreatePostDto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreatePostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, ['class' => 'AppBundle:User', 'choice_label' => 'username',])
            ->add('title', TextType::class)
            ->add('content', TextareaType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CreatePostDto::class,
            'csrf_protection' => false,
        ]);
    }
}