<?php

namespace hiqdev\recon\dns\Command;

use Psr\Container\ContainerInterface;
use yii\base\InvalidConfigException;

/**
 * Class CreateZoneCommand
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class CreateZoneHandler
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

    public function handle(CreateZoneCommand $command)
    {
        if (!isset($this->soft[$command->service->soft])) {
            throw new InvalidConfigException('Do not know how to work with soft "' . $command->service->soft . '".');
        }

        return $this->di->get($this->soft[$command->service->soft])->handle($command);
    }
}
