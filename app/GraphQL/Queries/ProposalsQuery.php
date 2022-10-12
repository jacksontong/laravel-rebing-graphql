<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Proposal;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class ProposalsQuery extends Query
{
    protected $attributes = [
        'name' => 'proposals',
        'description' => 'A query'
    ];

    public function type(): Type
    {
        return GraphQL::paginate('Proposal');
    }

    public function args(): array
    {
        return [
            'page' => [
                'type' => Type::int(),
            ],
            'limit' => [
                'type' => Type::int(),
            ],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        /** @var SelectFields $fields */
        $fields = $getSelectFields();
        $select = $fields->getSelect();

        return Proposal::select($select)
            ->paginate(
                perPage: $args['limit'] ?? 10,
                page: $args['page'] ?? 1,
            );
    }
}
