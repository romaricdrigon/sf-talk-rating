<?php

namespace App\Form;

use App\Entity\SfConnectUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Romaric Drigon <romaric.drigon@gmail.com>
 */
class SpeakerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
            ])
            ->add('name', TextType::class, [
                'label' => 'Full name (required)',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email address',
                'required' => false,
            ])
            ->add('uuid', TextType::class, [
                'label' => 'UUID from SFConnect (required)',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', SfConnectUser::class);
    }
}
