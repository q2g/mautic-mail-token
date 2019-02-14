<?php

namespace MauticPlugin\AddTokenToEmailBundle\Form\Type;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Mautic\CoreBundle\Form\Type\SlotType;

class SlotSERTokenType extends SlotType {
    /**
    * @param FormBuilderInterface $builder
    * @param array                $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('href', 'text', [
            'label' => 'URL',
            'label_attr' => ['class' => 'control-label'],
            'required' => true,
            'attr' => [
                'value' => "https://testserver/extensions/q2g-web-jwtproxyredirect/q2g-web-jwtproxyredirect.html?bearer={ser-token}",
                'class' => 'form-control',
                'data-slot-param' => 'href'
            ],
        ]);
        $builder->add('link-text', 'text', [
            'label' => 'Button Text',
            'label_attr' => ['class' => 'control-label'],
            'required' => false,
            attr => [
                'class' => 'form-control',
                'data-slot-param' => 'link-text',
            ],
        ]);
    
        parent::buildForm($builder, $options);
    }
    /**
    * @return string
    */
    public function getName() {
        return 'slot_sertoken';
    }
}

?>