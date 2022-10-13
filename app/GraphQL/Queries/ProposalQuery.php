<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\GraphQL\AuthorizeProposal;
use App\Models\Proposal;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class ProposalQuery extends Query
{
    use AuthorizeProposal;

    protected $attributes = [
        'name' => 'proposal',
        'description' => 'A query'
    ];

    public function type(): Type
    {
        return GraphQL::type('Proposal');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::id()),
            ]
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();
        $with = $fields->getRelations();

        return Proposal::select($select)
            ->with($with)
            ->findOrFail($args['id']);
    }
}
