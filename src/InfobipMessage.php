<?php

namespace Caherrera\Laravel\Notifications\Channels\Infobip\Omni;

use infobip\api\model\omni\send\WhatsAppData;

class InfobipMessage extends WhatsAppData
{

    /**
     * Create a new message instance.
     *
     * @param  array  $content
     */
    public function __construct($content = [])
    {
        $this->setTemplateName($content['templateName'] ?? null);
        $this->setTemplateNamespace($content['templateNamespace'] ?? null);
        $this->setTemplateData($content['templateData'] ?? null);
        $this->setLanguage($content['language'] ?? null);
    }

}
