<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AppBundle\Form\Model\Tracker;

class NewTracker extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hours', TextType::class)
            ->add('activityId', ChoiceType::class, array(
                'choices'  => array(
                    '8' => 'Design',
                    '9' => 'Development',
                    '10' => 'Management',
                    '11' => 'Testing',
                )))
            ->add('comments', TextareaType::class)
            ->setMethod('POST')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Form\Model\Tracker',
            'em' => null
        ]);
    }
}