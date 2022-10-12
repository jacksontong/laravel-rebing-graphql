<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\GraphQL\Fields\FormattableDate;
use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A user',
        'model' => User::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::ID()),
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'email' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'emailVerifiedAt' => new FormattableDate([
                'alias' => 'email_verified_at'
            ]),
            'createdAt' => new FormattableDate([
                'alias' => 'created_at'
            ]),
            'updatedAt' => new FormattableDate([
                'alias' => 'updated_at'
            ]),
        ];
    }
}
