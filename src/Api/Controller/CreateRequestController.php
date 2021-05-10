<?php

/*
 * This file is part of fof/username-request.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\UserRequest\Api\Controller;

use Flarum\Api\Controller\AbstractCreateController;
use Flarum\User\Exception\NotAuthenticatedException;
use FoF\UserRequest\Api\Serializer\RequestSerializer;
use FoF\UserRequest\Command\CreateRequest;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateRequestController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = RequestSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user',
        'user.user_requests',
    ];

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        if (!$actor->checkPassword(Arr::get($request->getParsedBody(), 'meta.password'))) {
            throw new NotAuthenticatedException();
        }

        return $this->bus->dispatch(
            new CreateRequest($actor, Arr::get($request->getParsedBody(), 'data', []))
        );
    }
}
