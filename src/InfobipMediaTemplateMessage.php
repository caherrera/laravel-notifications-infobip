<?php

namespace Caherrera\Laravel\Notifications\Channels\Infobip\Omni;

use infobip\api\model\omni\send\WhatsAppData;

class InfobipMediaTemplateMessage extends WhatsAppData
{

    /**
     * Create a new message instance.
     *
     * @param  array  $content
     */
    public function __construct($content = [])
    {
        $this->setTemplateName($content['templateName'] ?? null);
        $this->setMediaTemplateData($content['templateData'] ?? null);
        $this->setLanguage($content['language'] ?? null);
    }

}
