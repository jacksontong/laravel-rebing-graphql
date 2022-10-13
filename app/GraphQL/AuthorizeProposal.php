<?php

namespace App\GraphQL;

use App\Models\Proposal;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;

trait AuthorizeProposal
{
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        $proposal = Proposal::select('user_id')
            ->find($args['id']);

        return $proposal && $proposal->user_id == Auth::id();
    }
}
