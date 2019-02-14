<?php

namespace MauticPlugin\AddTokenToEmailBundle\EventListener;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\CoreBundle\Form\Type\SlotTextType;
use Mautic\CoreBundle\CoreEvents;
use Mautic\PageBundle\Event\PageBuilderEvent;
use Mautic\PageBundle\Helper\TokenHelper;
use Mautic\PageBundle\Model\PageModel;
use Mautic\PageBundle\PageEvents;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailBuilderEvent;
use Mautic\EmailBundle\Event\EmailSendEvent;
use Mautic\EmailBundle\Model\EmailModel;
use Mautic\CoreBundle\Event\CustomAssetsEvent;
use Mautic\CoreBundle\Templating\Helper\AssetsHelper;
/**
* Class SlotSubscriber
*/
class SlotSubscriber extends CommonSubscriber {
    /**
    * {@inheritdoc}
    */
    public static function getSubscribedEvents() {
        return [
            PageEvents::PAGE_ON_BUILD   => ['onPageBuild', 0],
            EmailEvents::EMAIL_ON_BUILD => ['onEmailBuild', 0],
        ];
    }
    /**
    * Add new slots in builder.
    *
    * @param Events\PageBuilderEvent $event
    */
    
    public function onPageBuild(PageBuilderEvent $event){
        if ($event->slotTypesRequested()) {
            $event->addSlotType(
                'sertoken',
                'SER Token mit Parameter',
                'key',
                'AddTokenToEmailBundle:Slots:token.html.php',
                'slot_sertoken',
                200
            );
        }
    }
    /**
    * @param EmailBuilderEvent $event
    */
    public function onEmailBuild(EmailBuilderEvent $event){
        if ($event->slotTypesRequested()) {
            $event->addSlotType(
                'sertoken',
                'SER Token mit Parameter',
                'key',
                'AddTokenToEmailBundle:Slots:token.html.php',
                'slot_sertoken',
                200
            );
        }
    }
}  

?>