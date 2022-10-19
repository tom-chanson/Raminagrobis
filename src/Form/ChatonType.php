<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Chaton;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Url;

class ChatonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Sterilise')
            ->add('SourcePhoto', ChoiceType::class, [
                'choices' => $options['choiceList'],
                'expanded' => true,
                'multiple' => false,
                'mapped' => false,
                'label' => 'Image',
                'required' => true,
                'data' => $options['defaultChoice'],
                'row_attr' => ['class'=>'form-check-input'],
                'attr' => ['class'=>'form-switch'],
            ])
            ->add('Photo', TextType::class, [
                'required' => false,
                'attr' => ['hidden' => true],
            ])
            ->add('File', FileType::class, [
                'required' => false,
                'label' => false,
                'mapped' => false,
                'attr' => ['accept' => 'image/jpeg, image/png, image/gif, image/svg+xml, image/webp, image/apng'],
                'constraints' => [
                    new Image([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/svg+xml',
                            'image/webp',
                            'image/apng',
                        ],
                        'mimeTypesMessage' => 'L\'image doit être au format JPEG, PNG, GIF, SVG, WEBP ou APNG.',
                        'maxSizeMessage' => 'L\'image ne doit pas dépasser 5 Mo.',
                        'detectCorrupted' => true,
                        'corruptedMessage' => 'L\'image est corrompue.',
                    ]),
                ],
                ])
            ->add('PhotoURL', UrlType::class, [
                'label' => false,
                'mapped' => false,
                'attr' => ['placeholder' => 'URL de l\'image'],
                'constraints' => [
                    new Callback(function ($value, $context) {
                        if (!\preg_match("^((http|https)://)(www[.])?([a-zA-Z0-9]|-)+([.][a-zA-Z0-9(-|/|=|?)?]+)+$^", $value) and $value != null) {
                            $context->addViolation('L\'URL doit être de la forme https://www.example.com/image.jpg');
                        }
                    }),
                ],
                'default_protocol' => 'https',
            ])
            ->add('Categorie', EntityType::class, [
                'class' => Categorie::class, //choix de la classe liée
                'choice_label' => 'titre', //choix de ce qui sera affiché comme texte
                'multiple' => false, //choix multiple ou non
                'expanded' => false, //liste déroulante ou non (boutons radio),
                'placeholder' => 'Choisissez une catégorie', //texte par défaut
            ])
            ->add('OK',SubmitType::class, ['label'=>'OK'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chaton::class,
            'choiceList' => ['Fichier' => '0', 'Internet' => '1', 'Image originale' => '2', 'Supprimer l\'image' => '3'],
            'defaultChoice' => '2',
            'SourcePhoto' => '',
        ]);
    }
}
