<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\InputType;

class UpdateProposalInput extends InputType
{
    protected $attributes = [
        'name' => 'UpdateProposalInput',
    ];

    public function fields(): array
    {
        return [
            'title' => [
                'type' => Type::string(),
                'description' => 'Proposal title',
                'rules' => ['string', 'min:5', 'max:255'],
            ],
        ];
    }
}
