<?php

use App\Models\Proposal;
use function Pest\Laravel\post;

const FETCH_PROPOSALS_QUERY = /** @lang GraphQL */'
{
    proposals {
        data {
            id
            title
            user {
                id
                name
            }
        }
        total
    }
}
';

it('fetches proposal', function () {
    $user = authenticate();
    $proposals = Proposal::factory(10)
        ->for($user)
        ->create();

    post(route('graphql'), [
        'query' => FETCH_PROPOSALS_QUERY,
    ])
        ->assertJson([
            'data' => [
                'proposals' => [
                    'data' => [
                        [
                            'id' => $proposals[0]->id,
                        ],
                    ],
                ],
            ],
        ])
        ->assertJsonStructure([
            'data' => [
                'proposals' => [
                    'data' => [
                        [
                            'id',
                        ],
                    ],
                    'total'
                ],
            ],
        ]);
});

it('fetches proposals with user', function () {
    $user = authenticate();
    $proposals = Proposal::factory(10)
        ->for($user)
        ->create();

    post(route('graphql'), [
        'query' => FETCH_PROPOSALS_QUERY,
    ])
        ->assertJson([
            'data' => [
                'proposals' => [
                    'data' => [
                        [
                            'user' => [
                                'id' => $proposals[0]->user_id,
                                'name' => $proposals[0]->user->name,
                            ],
                        ],
                    ],
                ],
            ],
        ]);
});

it("doesn't fetch proposals of other", function () {
    $user = authenticate();
    Proposal::factory(3)
        ->for($user)
        ->create();
    $proposal = Proposal::factory()
        ->create();

    $response = post(route('graphql'), [
        'query' => FETCH_PROPOSALS_QUERY,
    ]);

    expect($response->collect('data.proposals.data'))
        ->contains('id', $proposal->id)->toBeFalse();
});
