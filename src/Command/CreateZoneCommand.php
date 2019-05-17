<?php

namespace hiqdev\recon\dns\Command;

use hiapi\commands\BaseCommand;
use hiqdev\recon\core\Model\Service;
use hiqdev\recon\core\Model\ServiceAwareInterface;
use hiqdev\recon\dns\Model\Zone;

/**
 * Class CreateZoneCommand
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class CreateZoneCommand extends BaseCommand implements ServiceAwareInterface
{
    /**
     * @var Zone
     */
    public $zone;

    /**
     * @var Service
     */
    public $service;

    public function getService(): Service
    {
        return $this->service;
    }
}
