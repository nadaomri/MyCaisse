<?php

namespace App\Form;

use App\Entity\Caisse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType,ButtonType,EmailType,HiddenType,PasswordType,TextareaType,SubmitType,NumberType,DateType,MoneyType,BirthdayType,FileType};
use Symfony\Component\Validator\Constraints\File;

class CaisseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            //->add('solde', HiddenType::class)
            ->add('balance')
            ->add('montant')
            ->add('employe')
            ->add('category')
            ->add('date',DateType::Class, array(
                     'widget' => 'choice',
                     'years' => range(date('Y'), date('Y'), 2),
                     'months' => range(date('m'), 12),
                     'days' => range(date('d'), 31),
                 ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Caisse::class,
        ]);
    }
}
