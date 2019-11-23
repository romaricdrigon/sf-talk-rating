<?php

namespace App\Form;

use App\Entity\EventComment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form for EventComment.
 *
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 */
class EventCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', RatingType::class, [
                'required' => true,
            ])
            //->add('selectedTags')
            ->add('comment', TextareaType::class, [
                'attr' => [
                    'minlength' => 20,
                ],
                'help' => 'Minimum 20 characters. Your review should respect <a href="https://symfony.com/doc/master/contributing/code_of_conduct/code_of_conduct.html">Symfony Code of Conduct</a> or it may be moderated.',
                'help_html' => true,
            ])
            ->add('contentRating', RatingType::class, [
                'help' => 'About the conference schedule, the selection of topics...',
                'label' => 'Content',
                'required' => false
            ])
            ->add('locationRating', RatingType::class, [
                'label' => 'Location',
                'required' => false
            ])
            ->add('foodRating', RatingType::class, [
                'label' => 'Food',
                'required' => false
            ])
            ->add('socialEventRating', RatingType::class, [
                'help' => 'If you attended the social event on Thursday night',
                'label' => 'Social event',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', EventComment::class);
    }
}
