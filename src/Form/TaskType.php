<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, [
                "label" => false,
                "attr" => [
                    "class" => "form-control",
                    "placeholder" => "Libellé (Ex: Manger des alpagas extraterrestres)"
                ]
            ])
            ->add('start_at', TextType::class, [
                "label" => false,
                "attr" => [
                    "class" => "form-control datetimePicker",
                    "placeholder" => "Début (Ex: 2019-03-10 15:30)"
                ]
            ])
            ->add('end_at', TextType::class, [
                "label" => false,
                "attr" => [
                    "class" => "form-control datetimePicker",
                    "placeholder" => "Fin (Ex: 2019-03-10 15:30)"
                ]
            ])
            ->add('done', CheckboxType::class, [
                "required" => false,
                "label" => "Terminé",
                "attr" => [
                    "class" => "custom-select"
                ]
            ])
            ->add('employee', EntityType::class, [
                "class" => Employee::class,
                "label" => "Employé",
                "attr" => [
                    "class" => "custom-select"
                ]
            ])
            ->add('created_by', EntityType::class, [
                "class" => User::class,
                "label" => "Admin",
                "attr" => [
                    "class" => "custom-select"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
