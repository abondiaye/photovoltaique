<?php
declare(strict_types=1);

namespace App\Form;

use App\Entity\MaintenanceRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class MaintenanceRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, ['label' => 'Nom complet'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('phone', TextType::class, ['label' => 'Téléphone', 'required' => false])
            ->add('addressLine1', TextType::class, ['label' => 'Adresse'])
            ->add('addressLine2', TextType::class, ['label' => 'Complément d\'adresse', 'required' => false])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('postalCode', TextType::class, ['label' => 'Code postal'])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de demande',
                'choices' => [
                    'Nettoyage' => MaintenanceRequest::TYPE_CLEANING,
                    'Inspection' => MaintenanceRequest::TYPE_INSPECTION,
                    'Réparation' => MaintenanceRequest::TYPE_REPAIR,
                ],
            ])
            ->add('comment', TextareaType::class, ['label' => 'Commentaire', 'required' => false])
            ->add('requestedDate', DateTimeType::class, [
                'label' => 'Date souhaitée',
                'widget' => 'single_text',
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'En attente' => MaintenanceRequest::STATUS_PENDING,
                    'Confirmée' => MaintenanceRequest::STATUS_CONFIRMED,
                    'Réalisée' => MaintenanceRequest::STATUS_DONE,
                    'Annulée' => MaintenanceRequest::STATUS_CANCELLED,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MaintenanceRequest::class,
        ]);
    }
}
