<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\GraphQL\Middleware\Authenticate;
use App\Models\Proposal;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class CreateProposalMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createProposal',
        'description' => 'Create a new proposal.',
    ];

    protected $middleware = [
        Authenticate::class,
    ];

    public function type(): Type
    {
        return GraphQL::type('Proposal');
    }

    public function args(): array
    {
        return [
            'proposal' => [
                'type' => GraphQL::type('CreateProposalInput'),
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        return Proposal::create([
            'title' => $args['proposal']['title'],
            'user_id' => Auth::id(),
        ]);
    }
}
