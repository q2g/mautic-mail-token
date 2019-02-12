<?php

namespace MauticPlugin\AddTokenToEmailBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailBuilderEvent;
use Mautic\EmailBundle\Event\EmailSendEvent;

// generate a random token
function genGuid($string) {
    $guid = com_create_guid();
    return "$guid$string";
}

/**
 * Class EmailSubscriber
 */
class EmailSubscriber extends CommonSubscriber
{

    /**
     * @return array
     */
    static public function getSubscribedEvents()
    {
        return array(
            EmailEvents::EMAIL_ON_BUILD   => array('onEmailBuild', 0),
            EmailEvents::EMAIL_ON_SEND    => array('onEmailGenerate', 0),
            EmailEvents::EMAIL_ON_DISPLAY => array('onEmailGenerate', 0)
        );
    }

    /**
     * Register the tokens and a custom A/B test winner
     *
     * @param EmailBuilderEvent $event
     */
    public function onEmailBuild(EmailBuilderEvent $event)
    {
        // Add email tokens
        $event->addTokenSection('addTokenToEmail.token', 'plugin.addTokenToEmail.header', '{token}');

        // Add AB Test Winner Criteria
        $event->addAbTestWinnerCriteria(
            'helloworld.planetvisits',
            array(
                // Label to group by
                'group'    => 'plugin.addTokenToEmail.header',

                // Label for this specific a/b test winning criteria
                'label'    => 'plugin.addTokenToEmail.emailtokens.',

                // Static callback function that will be used to determine the winner
                'callback' => '\MauticPlugin\AddTokenToEmailBundle\Helper\AbTestHelper::determinePlanetVisitWinner'
            )
        );
    }

    /**
     * Search and replace tokens with content
     *
     * @param EmailSendEvent $event
     */
    public function onEmailGenerate(EmailSendEvent $event)
    {
        // Get content
        $content = $event->getContent();

        // Search and replace tokens
        $content = str_replace('{token}', genGuid('test'), $content);

        // Set updated content
        $event->setContent($content);
    }
}
