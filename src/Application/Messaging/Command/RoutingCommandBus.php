<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Command;

use Novuso\Common\Application\Messaging\Command\Routing\CommandRouter;
use Novuso\Common\Domain\Messaging\Command\Command;
use Novuso\Common\Domain\Messaging\Command\CommandBus;

/**
 * RoutingCommandBus routes a command to a single handler
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class RoutingCommandBus implements CommandBus
{
    /**
     * Command router
     *
     * @var CommandRouter
     */
    protected $router;

    /**
     * Constructs RoutingCommandBus
     *
     * @param CommandRouter $router The command router
     */
    public function __construct(CommandRouter $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Command $command)
    {
        $this->router->match($command)->handle($command);
    }
}
