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
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

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
        // qd texte par défaut, ne pas mettre TextType ou TextArea
            ->add('name', TextType::class, array(
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nom de levent'
                ]
            ))

            ->add('description', TextareaType::class, array(
                'label' => 'Description : ',
            ))
            // quand il n'y a pas de Type noter null
            // impose un minimum = 0
            ->add('capacity', null, array(
                'label' => 'Nombre maximum de participants : ',
                'attr' => [
                    'min' => 0,
                ]

            ))

            ->add('start_at', null, array(
                'label' => 'Date de début :  ',
                'widget' => 'single_text',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'),
                ]
                ))

            ->add('end_at', DateTimeType::class, array(
                'label' => 'Date de fin :  ',
                'widget' => 'single_text',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'),
                ]
                ))

            ->add('price', MoneyType::class, array(
                'label' => 'Prix : ',
                'currency' => false,
                'attr' => [
                    'min' => 0,
                ]
            ))

            // adresse URL
            // ->add('poster', TextType::class, array(
            //     'label' => 'Brochure (PDF file)',
            //     ))

            // fichier à uploader
            ->add('posterFile', FileType::class, array(
                'label' => 'Image : ',
                'attr' => [
                    'accept' => 'image/*',
                    'class' => 'input-group-text',
                    'class' => 'col-2',
                ]
            ))

            // fichier URL
            ->add('posterURL', UrlType::class, array(
                'label' => 'URL :',
            ))

            ->add('category', EntityType::class, array(
                'class' => Category::class,
                'choice_label' => 'name', 
                'multiple' => 'true',
                'expanded' => true,
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
                'label' => 'Lieu de event : ',
                'expanded' => true,
                'placeholder' => "Choisissez un lieu"
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
