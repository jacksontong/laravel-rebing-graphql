<?php

use App\Models\Proposal;
use function Pest\Laravel\post;

it('fetches proposal', function () {
    $user = authenticate();
    $proposal = Proposal::factory()
        ->for($user)
        ->create();

    post(route('graphql'), [
        'query' => <<<GQL
{
    proposal(id: $proposal->id) {
        id
        title
    }
}
GQL
    ])
        ->assertJson([
            'data' => [
                'proposal' => [
                    'id' => $proposal->id,
                ]
            ]
        ])
        ->assertJsonMissingPath('data.proposal.createdAt');
});
