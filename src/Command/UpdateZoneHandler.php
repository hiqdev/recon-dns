<?php

namespace hiqdev\recon\dns\Command;

use Psr\Container\ContainerInterface;
use yii\base\InvalidConfigException;

/**
 * Class UpdateZoneHandler
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class UpdateZoneHandler
{
    /**
     * @var ContainerInterface
     */
    private $di;

    /**
     * @var string[]
     */
    public $soft;

    public function __construct(ContainerInterface $di)
    {
        $this->di = $di;
    }

    public function handle(UpdateZoneCommand $command)
    {
        if (!isset($this->soft[$command->service->soft])) {
            throw new InvalidConfigException('Do not know how to work with soft "' . $command->service->soft . '".');
        }

        return $this->di->get($this->soft[$command->service->soft])->handle($command);
    }
}
