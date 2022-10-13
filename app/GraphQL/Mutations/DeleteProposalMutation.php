<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\GraphQL\AuthorizeProposal;
use App\GraphQL\Middleware\Authenticate;
use App\Models\Proposal;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class DeleteProposalMutation extends Mutation
{
    use AuthorizeProposal;

    protected $attributes = [
        'name' => 'deleteProposal',
        'description' => 'Delete a proposal by id.',
    ];

    protected $middleware = [
        Authenticate::class,
    ];

    public function type(): Type
    {
        return Type::id();
    }

    public function args(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Proposal id',
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        Proposal::destroy($args['id']);

        return $args['id'];
    }
}
