<?php

declare(strict_types=1);

namespace App\GraphQL\Middleware;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory;
use Rebing\GraphQL\Support\Middleware;

class Authenticate extends Middleware
{
    public function __construct(
        private readonly Factory $auth,
    ) {
    }

    /**
     * @throws AuthenticationException
     */
    public function handle($root, array $args, $context, ResolveInfo $info, Closure $next)
    {
        $this->authenticate(['sanctum']);

        return $next($root, $args, $context, $info);
    }

    /**
     * Determine if the user is logged in to any of the given guards.
     * @param array $guards
     * @return void
     * @throws AuthenticationException
     */
    protected function authenticate(array $guards): void
    {
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                $this->auth->shouldUse($guard);

                return;
            }
        }

        throw new AuthenticationException(
            guards: $guards,
        );
    }
}
