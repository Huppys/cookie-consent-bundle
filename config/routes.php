<?php

declare(strict_types=1);

use huppys\CookieConsentBundle\Controller\CookieConsentController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('cookie_consent.view', '/cookie-consent/view')
        // the controller value has the format [controller_class, method_name]
        ->controller([CookieConsentController::class, 'view']);
    $routes->add('cookie-consent.update', '/cookie-consent/update')
        // the controller value has the format [controller_class, method_name]
        ->controller([CookieConsentController::class, 'update']);

        // if the action is implemented as the __invoke() method of the
        // controller class, you can skip the 'method_name' part:
        // ->controller(BlogController::class)

};
