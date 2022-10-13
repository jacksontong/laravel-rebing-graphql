<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\GraphQL\Middleware\Authenticate;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class MeQuery extends Query
{
    protected $attributes = [
        'name' => 'me',
        'description' => 'Get the current authenticated user.',
    ];

    protected $middleware = [
        Authenticate::class
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return Auth::user();
    }
}
