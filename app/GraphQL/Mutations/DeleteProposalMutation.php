<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Proposal;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class DeleteProposalMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteProposal',
        'description' => 'Delete a proposal by id.',
    ];

    public function type(): Type
    {
        return Type::id();
    }

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        $proposal = Proposal::select('user_id')
            ->find($args['id']);

        return $proposal && $proposal->user_id == Auth::id();
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
