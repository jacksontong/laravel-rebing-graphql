<?php

use App\Models\Proposal;
use function Pest\Laravel\post;

const FIND_PROPOSAL_QUERY = <<<'GQL'
query findProposal($id: ID!) {
    proposal(id: $id) {
        id
        title
        userId
        user {
            id
            name
        }
    }
}
GQL;

it('fetches proposal', function () {
    $user = authenticate();
    $proposal = Proposal::factory()
        ->for($user)
        ->create();

    post(route('graphql'), [
        'query' => FIND_PROPOSAL_QUERY,
        'variables' => [
            'id' => $proposal->id,
        ],
    ])
        ->assertJson([
            'data' => [
                'proposal' => [
                    'id' => $proposal->id,
                ],
            ],
        ]);
});

it('fetches proposal with user', function () {
    $user = authenticate();
    $proposal = Proposal::factory()
        ->for($user)
        ->create();

    post(route('graphql'), [
        'query' => FIND_PROPOSAL_QUERY,
        'variables' => [
            'id' => $proposal->id,
        ],
    ])
        ->assertJson([
            'data' => [
                'proposal' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                    ],
                ],
            ],
        ]);
});

it("doesn't allow to fetch proposal of other", function () {
    $user = authenticate();
    $proposal = Proposal::factory()
        ->create();

    post(route('graphql'), [
        'query' => FIND_PROPOSAL_QUERY,
        'variables' => [
            'id' => $proposal->id,
        ],
    ])
        ->assertJson([
            'errors' => [
                [
                    'extensions' => [
                        'category' => 'authorization',
                    ],
                ],
            ],
        ]);
});
