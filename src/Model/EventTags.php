<?php

namespace App\Model;

/**
 * In EventReview, we can select a few tags which apply to the event.
 * Those are stored denormalized, as we may add/remove tags in the future.
 *
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 */
class EventTags
{
    public static function getAllWithLabel(): array
    {
        return array_flip(self::getChoices());
    }

    public static function getChoices(): array
    {
        return [
            'Easily accessible' => 'easily_accessible',
             'Great food' => 'great_food',
            'I went to the Unconference' => 'been_unconference',
            'I used the quiet room' => 'used_quiet_room',
            'I attended Social event' => 'attended_social_event',
        ];
    }
}
