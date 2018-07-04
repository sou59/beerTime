<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;
use App\Entity\Place;

class FormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nom de votre évènement : ',
            ))
            ->add('description', TextareaType::class, array(
                'label' => 'Description : ',
            ))
            ->add('capacity', null, array(
                'label' => 'Capacité : ',
            ))
            ->add('start_at', DateTimeType::class, array(
                'label' => 'Date de début :  ',
                'placeholder' => 'Select a value',
                'widget' => 'single_text',
                ))
            ->add('end_at', DateTimeType::class, array(
                'label' => 'Date de fin :  ',
                'placeholder' => 'Select a value',
                'widget' => 'single_text',
                ))
            ->add('price', null, array(
                'label' => 'Prix : ',
            ))

            // adresse URL
            // ->add('poster', TextType::class, array(
            //     'label' => 'Brochure (PDF file)',
            //     ))

            // fichier à uploader
            ->add('poster', FileType::class, array(
                'label' => 'Affiche : ',
            ))

            ->add('category', EntityType::class, array(
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => 'true',
                ))
            
            ->add('owner', EntityType::class, array(
                'class' => User::class,
                'choice_label' => 'username',
                'label' => 'Propriétaire : '
                ))
                
            ->add('place', EntityType::class, array(
                'class' => Place::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'choice_label' => 'name',
                'label' => 'Lieu de event : '
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
