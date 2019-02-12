<?php
return array(
    'name'        => 'Add token to E-Mails',
    'description' => 'Adds a token to emails send with a token field',
    'version'     => '1.0.0',
    'author'      => 'Jonas Walther',

    'services'    => array(
        'events' => array(
            'mautic.addtokentoemail.emailbundle.subscriber' => array(
                'class' => 'MauticPlugin\AddTokenToEmailBundle\EventListener\EmailSubscriber'
            )
        ),
        /* 'forms'  => array(
         *     'mautic.form.type.fieldslist.selectidentifier'  => array(
         *         'class' => 'MauticPlugin\MauticDeskBundle\Form\Type\FormFieldsType',
         *         'arguments' => 'mautic.factory',
         *         'alias' => 'formfields_list'
         *     )
         * ) */
    ),
);

?>
