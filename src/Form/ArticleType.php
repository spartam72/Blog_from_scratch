<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image(JPG,JPEG or PNG file)',
                'required' => false,
                'download_uri' => false,
                'image_uri' => false,
                'allow_delete' => false,
                // 'delete_label' => 'Remove Image',
                //'imagine_pattern' => 'squared_thumbnail_medium'
            ])
            ->add('title')
            ->add('content');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
