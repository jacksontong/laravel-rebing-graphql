<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Proposal;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class CreateProposalMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createProposal',
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
            'user_id' => $args['proposal']['userId'],
        ]);
    }
}
