<?php

namespace App\Form;

use App\Entity\EventReview;
use App\Model\EventTags;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form for EventReview.
 *
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 */
class EventReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', RatingType::class, [
                'required' => true,
            ])
            ->add('selectedTags', ChoiceType::class, [
                'choices' => EventTags::getChoices(),
                'expanded' => true,
                'label' => 'Highlights',
                'multiple' => true,
                'required' => false,
             ])
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
        $resolver->setDefault('data_class', EventReview::class);
    }
}
