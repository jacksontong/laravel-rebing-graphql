<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\GraphQL\Fields\FormattableDate;
use App\Models\Proposal;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProposalType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Proposal',
        'description' => 'A proposal.',
        'model' => Proposal::class,
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
            'userId' => [
                'type' => Type::nonNull(Type::int()),
                'alias' => 'user_id',
            ],

            /* RELATIONS */
            'user' => [
                'type' => GraphQL::type('User'),
            ],
        ];
    }
}
