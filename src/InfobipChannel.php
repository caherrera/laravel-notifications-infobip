<?php

namespace Caherrera\Laravel\Notifications\Channels\Infobip\Omni;

use Caherrera\Laravel\Notifications\Channels\Infobip\Omni\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use infobip\api\model\omni\send\WhatsAppData;

class InfobipChannel
{
    /**
     * @var Infobip
     */
    protected $infobip;

    public function __construct(Infobip $infobip)
    {
        $this->infobip = $infobip;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     *
     * @return mixed
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $to      = $this->getTo($notifiable);
            $message = $notification->toInfobip($notifiable);

            if ( ! $message instanceof WhatsAppData) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }

            return $this->infobip->sendMessage($message, $to);
        } catch (\Exception $exception) {
            $event = new NotificationFailed($notifiable, $notification, 'infobip', ['message' => $exception->getMessage(), 'exception' => $exception]);
            if (function_exists('event')) { // Use event helper when possible to add Lumen support
                event($event);
            } else {
                $this->events->fire($event);
            }
        }
    }

    /**
     * Get the address to send a notification to.
     *
     * @param  mixed  $notifiable
     *
     * @return mixed
     * @throws CouldNotSendNotification
     */
    protected function getTo(Notifiable $notifiable)
    {
        if ($notifiable->routeNotificationFor('infobip')) {
            return $notifiable->routeNotificationFor('infobip');
        }
        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }
        throw CouldNotSendNotification::invalidReceiver();
    }
}
