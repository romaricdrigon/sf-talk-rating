<?php

namespace App\Form;

use App\Entity\TalkReview;
use App\Model\TalkTags;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form for TalkReview.
 *
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 */
class TalkReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', RatingType::class, [
                'required' => true,
            ])
            ->add('selectedTags', ChoiceType::class, [
                'choices' => TalkTags::getChoices(),
                'expanded' => true,
                'label' => 'Highlights',
                'multiple' => true,
                'required' => false,
             ])
            ->add('comment', TextareaType::class, [
                'attr' => [
                    'minlength' => 20,
                ],
                'help' => 'Minimum 20 characters. Your comment should respect <a href="https://symfony.com/doc/master/contributing/code_of_conduct/code_of_conduct.html">Symfony Code of Conduct</a> or it may be moderated.',
                'help_html' => true,
            ])
            ->add('contentRating', RatingType::class, [
                'help' => 'About the presentation content (the topic, what you got from the topic and the main point...)',
                'label' => 'Content',
                'required' => false,
            ])
            ->add('deliveryRating', RatingType::class, [
                'help' => 'About the way the presentation was given (slides, oral communication, performance of the speaker...)',
                'label' => 'Delivery',
                'required' => false,
            ])
            ->add('relevanceRating', RatingType::class, [
                'help' => 'Was the subject and the presentation adapted to the track and audience, and was it important to you',
                'label' => 'Relevance',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', TalkReview::class);
    }
}
