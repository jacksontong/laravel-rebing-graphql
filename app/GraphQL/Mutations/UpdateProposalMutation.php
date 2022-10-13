<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\GraphQL\AuthorizeProposal;
use App\GraphQL\Middleware\Authenticate;
use App\Models\Proposal;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class UpdateProposalMutation extends Mutation
{
    use AuthorizeProposal;

    protected $attributes = [
        'name' => 'updateProposal',
        'description' => 'Update a proposal by id.'
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
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => 'Proposal id.',
            ],
            'proposal' => [
                'type' => GraphQL::type('UpdateProposalInput'),
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        $proposal = Proposal::select($select)
            ->with($with)
            ->findOrFail($args['id']);
        $proposal->update($args['proposal']);

        return $proposal;
    }
}
