<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\GraphQL\Fields\FormattableDate;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProposalType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Proposal',
        'description' => 'A proposal.'
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::ID()),
            ],
            'title' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'createdAt' => new FormattableDate([
                'alias' => 'created_at'
            ]),
            'updatedAt' => new FormattableDate([
                'alias' => 'updated_at'
            ]),
        ];
    }
}
