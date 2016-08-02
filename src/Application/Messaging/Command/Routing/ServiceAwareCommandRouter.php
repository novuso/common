<?php declare(strict_types = 1);

namespace Novuso\Common\Application\Messaging\Command\Routing;

use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandHandler;

/**
 * ServiceAwareCommandRouter matches commands from a service map
 *
 * @copyright Copyright (c) 2016, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ServiceAwareCommandRouter implements CommandRouter
{
    /**
     * Command map
     *
     * @var ServiceAwareCommandMap
     */
    protected $commandMap;

    /**
     * Constructs ServiceAwareCommandRouter
     *
     * @param ServiceAwareCommandMap $commandMap The command map
     */
    public function __construct(ServiceAwareCommandMap $commandMap)
    {
        $this->commandMap = $commandMap;
    }

    /**
     * {@inheritdoc}
     */
    public function match(Command $command): CommandHandler
    {
        return $this->commandMap->getHandler(get_class($command));
    }
}
