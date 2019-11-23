<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Customized ChoiceType, to be used with jquery-bar-rating.
 *
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 */
class RatingType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'star-rating',
            ],
            'choices' => [
                'Very bad' => 1,
                'Bad' => 2,
                'Average' => 3,
                'Good' => 4,
                'Very good' => 5,
            ],
            'placeholder' => 'Please select a rating...', // We force a placeholder, so Rating is always empty at page load
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}
