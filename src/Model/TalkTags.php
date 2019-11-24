<?php

namespace App\Model;

/**
 * In TalkReview, we can select a few tags which apply to the talk - as "highlights".
 * They are meant to store some quick & positive feedback.
 * Those are stored denormalized, as we may add/remove tags in the future.
 *
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 */
class TalkTags
{
    public static function getAllWithLabel(): array
    {
        return array_flip(self::getChoices());
    }

    public static function getChoices(): array
    {
        return [
            'Important topic' => 'important_topic',
            'Interesting content' => 'interesting_content',
            'Great delivery' => 'great_delivery',
            'Awesome slides' => 'awesome_slides',
            'Adapted to the track or audience' => 'adapted_to_audience',
        ];
    }
}
