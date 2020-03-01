<?php

/**
 * webtrees: online genealogy
 * Copyright (C) 2019 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace Fisharebest\Webtrees\Http\RequestHandlers;

use Fig\Http\Message\StatusCodeInterface;
use Fisharebest\Webtrees\Contracts\RepositoryFactoryInterface;
use Fisharebest\Webtrees\Exceptions\RepositoryNotFoundException;
use Fisharebest\Webtrees\Repository;
use Fisharebest\Webtrees\Services\TreeService;
use Fisharebest\Webtrees\Tree;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function redirect;

/**
 * Redirect URLs created by webtrees 1.x (and PhpGedView).
 */
class RedirectRepoPhp implements RequestHandlerInterface
{
    /** @var RepositoryFactoryInterface */
    private $repository_factory;

    /** @var TreeService */
    private $tree_service;

    /**
     * @param RepositoryFactoryInterface $repository_factory
     * @param TreeService                $tree_service
     */
    public function __construct(
        RepositoryFactoryInterface $repository_factory,
        TreeService $tree_service
    ) {
        $this->tree_service       = $tree_service;
        $this->repository_factory = $repository_factory;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $ged  = $request->getQueryParams()['ged'] ?? null;
        $tree = $this->tree_service->all()->get($ged);

        if ($tree instanceof Tree) {
            $xref       = $request->getQueryParams()['rid'] ?? '';
            $repository = $this->repository_factory->make($xref, $tree);

            if ($repository instanceof Repository) {
                return redirect($repository->url(), StatusCodeInterface::STATUS_MOVED_PERMANENTLY);
            }
        }

        throw new RepositoryNotFoundException();
    }
}
