<?php

namespace MauticPlugin\AddTokenToEmailBundle\EventListener;

require __DIR__ . '/../vendor/firebase/php-jwt/src/JWT.php';

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailBuilderEvent;
use Mautic\EmailBundle\Event\EmailSendEvent;
use Firebase\JWT\JWT;

$privateKey = <<<EOT
-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEAnsgSAAPEbCP8xb7oxWBDdZ17XUUttJLD0GgN9Wnq3Ub7vVc9
p1OKrtDALX/OuYzFP4eAVuEVOnvgG7WJZVXYVQcwe95Al+dJX+QRkce52lcpjLpw
VWCua/iR+JbQlj3yRfmsJG0UXQJuPWVfJcEgsso1Eu9/11glbXcZqTox4JYO4AGH
5RGiFeMmdtZNc8q27IHdJc1AWXq3RdGAklixR636rl1/eaIE0jFKhEn9seZq3CCQ
niDV4cwWO7oTMgqOqIfxE0nLuaWzKiaLxfnpA9tW4Gw0PEnp3Po/iqQ66DwFWdOx
xWLqvO/VCjcxlhgieG3qWnyRYKcFDjJdme/4lwIDAQABAoIBACRYFU6tjhNWtXAp
/6vWGfrc/jTeNdUEVw/oEXHgOaNGsS6ovnMDe1YCcEq52367Sxb8lBLO2IYXfre4
+OcaHDdyOtXPjKd4iGILmT3uIotoshkFP9s7ExGhxv/IvZv2N/Nub8myshuMB+HD
x2Cj8TEbecSIz5FiGscQoO4MZQhL0pjQD9M/bsY/erpIT1P9o9iu3UJonqcsZjLn
mNmAxXlkiRhhovJ0/bfbmQZ2gzMQPsPVYND6OVxb+1ONYOopvbS6HR940Xu0uO5M
LqTqJ56UUMZgyQxdjhqv+X/IhsOXUiPDBy07TMSViUz9MW6qKqTKT7bSL/UYSexm
Vs4L89ECgYEA9mP4JM4TktkOL4Oe7xgehO2AbFB907Z00mEFyuhcVDlbLp1q4kT3
yMDN0rIZ/DCzezf+62YhBS1WEoQPYR+1LGv0iwO+ugPj9YT3CkgyyJOzKjfZFvMd
WSr/WD0uSbkkKgS4BLJA+CxJjS/9J8czYhkINAOsZT8Z0qLXamjAEBkCgYEApPlj
iIsaFKt/XUdvHHri0yhPJB545H3WGrI3jQd+iiymEY720tuvta3xO7/VORgnLk3u
VddFa3ZWLPSp/OlqkHWLKJkGLkBtgvThMW9t3WjEx1zcx200yGSaiANGw39eNJnP
ohSBvIDgM/qtSq066PjnCxWSxj66ujtuSB7lpC8CgYEAqrTskaNtkEpuN6E7lm/h
hTt/xIuoAezh13h9KO4AOiJa+Fr8WLRr8F0UvSvEMSQU1gQfzDxAGHQmtEFm3mW3
goVxMndvxzU21T5AYpFPDgS3F0MGV44tAUB3FU2eKlnpomsOi8JsnQUiGH3tKYgs
I4UwV8lLwgIMBBHqqcDVgNECgYBqCZIcVaxx1LmMrzmPyLy6lJIp2RxDYU6Y4iwq
jcKb9Y7YglfLuED8Oc1wZiEbDZdBf/3NVwzbwbgqNSh901oXeDX15kW+vNKm9dc4
+zJWudyhd+LAnETs+R0Kh3CYf+mBTcvTlfK9wuhZAKsZ8LaIwFNhIICyw+cphMGh
wZpBKQKBgQCOaDHOcafHyH0c2yQaAgWYDySqGLoXh5VsqMo2p2Phr9J9oxQvJ5Mo
ruQJRxHDRanrzrPY/nZY1ffd0ASjbLD0j2g+TtOpTEIVP4dceAIWobp3SOqSAOxp
+Mx6yKS97R3sRGGewXjA5l7hy5DqnZpneVNZJ++IOnYqwJuKg+QsWQ==
-----END RSA PRIVATE KEY-----
EOT;


// generate a random token
function getToken() {
    global $privateKey;
    $token = array(
        'UserId' => 'administrator',
        'UserDirectory' => 'TESTSERVER'
    );
    
    return JWT::encode($token, $privateKey, 'RS512');
}

/**
* Class EmailSubscriber
*/
class EmailSubscriber extends CommonSubscriber {
    
    /**
    * @return array
    */
    static public function getSubscribedEvents() {
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
    public function onEmailBuild(EmailBuilderEvent $event) {
        $tokens = array(
            '{ser-token}' => 'SER Token',
        );
        
        // Add email tokens
        $event->addTokenSection('addTokenToEmail.token', 'plugin.addTokenToEmail.header', '{token}');
        if ($event->tokensRequested(array_keys($tokens))) {
            $event->addTokens(
                $event->filterTokens($tokens),
                true
            );
        };
        
    }
    
    /**
    * Search and replace tokens with content
    *
    * @param EmailSendEvent $event
    */
    public function onEmailGenerate(EmailSendEvent $event) {
        
        // $privateKey = file_get_contents('./key.text', true);
        
        $tokens = array(
            '{ser-token}' => 'SER Token',
        );
        // Get content
        $content = $event->getContent();
        
        // Search and replace tokens
        $content = str_replace(array_keys($tokens)[0], getToken(), $content);
        
        // Set updated content
        $event->setContent($content);
    }
}
