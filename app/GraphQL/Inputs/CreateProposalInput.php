<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class CreateProposalInput extends InputType
{
    protected $attributes = [
        'name' => 'CreateProposalInput',
        'description' => 'An example input',
    ];

    public function fields(): array
    {
        return [
            'title' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Proposal title',
                'rules' => ['required', 'string', 'min:5', 'max:255']
            ],
        ];
    }
}
