<?php
return array(
    'name'        => 'Add token to E-Mails',
    'description' => 'Adds a token to emails send with a token field',
    'version'     => '1.0.0',
    'author'      => 'Jonas Walther',

    'services'    => array(
        'events' => array(
            'plugin.addtokentoemail.emailbundle.subscriber' => array(
                'class' => 'MauticPlugin\AddTokenToEmailBundle\EventListener\EmailSubscriber'
            ),
            'plugin.addtokentoemail.pagebuilder.subscriber' => array(
                'class' => 'MauticPlugin\AddTokenToEmailBundle\EventListener\SlotSubscriber'
            )
        ),
        'forms'  => array(
            'plugin.addtokentoemail.form.type.slot.sertoken'  => array(
                'class' => 'MauticPlugin\AddTokenToEmailBundle\Form\Type\SlotSERTokenType',
                'arguments' => 'mautic.factory',
                'alias' => 'slot_sertoken'
            )
        )
    ),
);

?>
