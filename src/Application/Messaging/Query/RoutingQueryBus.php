<?php

declare(strict_types=1);

namespace Novuso\Common\Application\Messaging\Query;

use Novuso\Common\Application\Messaging\Query\Routing\QueryRouter;
use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Messaging\Query\QueryBus;
use Novuso\Common\Domain\Messaging\Query\QueryMessage;

/**
 * Class RoutingQueryBus
 */
final class RoutingQueryBus implements QueryBus
{
    /**
     * Constructs RoutingQueryBus
     */
    public function __construct(protected QueryRouter $router)
    {
    }

    /**
     * @inheritDoc
     */
    public function fetch(Query $query): mixed
    {
        return $this->dispatch(QueryMessage::create($query));
    }

    /**
     * @inheritDoc
     */
    public function dispatch(QueryMessage $message): mixed
    {
        /** @var Query $query */
        $query = $message->payload();

        return $this->router->match($query)->handle($message);
    }
}
