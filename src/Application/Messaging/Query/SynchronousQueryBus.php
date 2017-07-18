<?php declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query;

use Novuso\Common\Application\Messaging\Query\Routing\QueryRouterInterface;
use Novuso\Common\Domain\Messaging\Query\QueryBusInterface;
use Novuso\Common\Domain\Messaging\Query\QueryInterface;
use Novuso\Common\Domain\Messaging\Query\QueryMessage;

/**
 * SynchronousQueryBus routes a query to a single handler
 *
 * @copyright Copyright (c) 2017, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class SynchronousQueryBus implements QueryBusInterface
{
    /**
     * Query router
     *
     * @var QueryRouterInterface
     */
    protected $router;

    /**
     * Constructs RoutingQueryBus
     *
     * @param QueryRouterInterface $router The query router
     */
    public function __construct(QueryRouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch(QueryInterface $query)
    {
        return $this->dispatch(QueryMessage::create($query));
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(QueryMessage $message)
    {
        /** @var QueryInterface $query */
        $query = $message->payload();

        return $this->router->match($query)->handle($message);
    }
}
