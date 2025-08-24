<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\AvailabilitySlot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AvailabilitySlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $b, array $o): void
    {
        $b
            ->add('label', TextType::class, ['label' => 'Libellé', 'required' => false])
            ->add('startAt', DateTimeType::class, [
                'label' => 'Début',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'flatpickr-datetime'],
            ])
            ->add('endAt', DateTimeType::class, [
                'label' => 'Fin',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'flatpickr-datetime'],
            ])
            ->add('capacity', IntegerType::class, ['label' => 'Capacité'])
            ->add('booked', IntegerType::class, ['label' => 'Réservés'])
            ->add('isClosed', CheckboxType::class, ['label' => 'Fermé', 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => AvailabilitySlot::class]);
    }
}
