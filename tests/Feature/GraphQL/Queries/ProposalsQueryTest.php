<?php

use App\Models\Proposal;
use function Pest\Laravel\post;

it('fetches proposal', function () {
    $user = authenticate();
    $proposals = Proposal::factory(10)
        ->for($user)
        ->create();

    post(route('graphql'), [
        'query' => <<<GQL
{
    proposals {
        data {
            id
            createdAt
        }
        total
    }
}
GQL
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
