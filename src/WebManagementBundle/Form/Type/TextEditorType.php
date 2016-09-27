<?php
namespace WebManagementBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextEditorType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text'
        ));

    }

    public function getParent()
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'texteditor';
    }
}

?>