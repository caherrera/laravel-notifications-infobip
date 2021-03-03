<?php

namespace Caherrera\Laravel\Notifications\Channels\Infobip\Omni;

use Caherrera\Laravel\Notifications\Channels\Infobip\Omni\Exceptions\AuthMethodException;
use infobip\api\client\SendAdvancedOmniMessage;
use infobip\api\configuration\ApiKeyAuthConfiguration;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\omni\Destination;
use infobip\api\model\omni\send\OmniAdvancedRequest;
use infobip\api\model\omni\To;

class Infobip
{
    const AUTH_BASIC  = 'basic';
    const AUTH_APIKEY = 'apikey';
    public $client;

    public function __construct()
    {
        switch ($auth_method = config('services.infobip.auth')) {
            case static::AUTH_BASIC:
                $auth = new BasicAuthConfiguration(
                    config('services.infobip.username'),
                    config('services.infobip.password'),
                    config('services.infobip.baseUrl')
                );
                break;
            case static::AUTH_APIKEY:
                $auth = new ApiKeyAuthConfiguration(config('services.infobip.apikey'), config('services.infobip.baseUrl'));
                break;
            default:
                throw new AuthMethodException($auth_method);
        }
        $this->client = new SendAdvancedOmniMessage($auth);
    }

    public function sendMessage(InfobipWhatsAppInterface $message, string $to)
    {
        $To          = new To();
        $destination = new Destination();
        $request     = new OmniAdvancedRequest();
        $To->setPhoneNumber(str_ireplace('+', '', $to));
        $destination->setTo($To);
        $request->setScenarioKey(config('services.infobip.scenario_key'));
        $request->setDestinations([$destination]);
        $request->setWhatsApp($message);

        return $this->client->execute($request);
    }
}
