<?php

namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('price')
            ->add('heat',ChoiceType::class,[
                'choices'=>$this->getChoices()
            ])
            ->add('city')
            ->add('address')
            ->add('postal_code')
            ->add('sold')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // translation_domain : forms ( fichier forms.fr.yaml crée dans le dossier translation pour la traduction des labels du formulaire
        $resolver->setDefaults([
            'data_class' => Property::class,
            'translation_domain' => 'forms',
        ]);
    }

    // On crée une méthode privée pour générer les choix de la liste déroulante
    // parce qu'en utilisant 'choices'=>Property::HEAT,ca nous renvoie plutot les clés de la constante(HEAT)
    private function getChoices(): array
    {
        $choices = Property::HEAT;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }
}
