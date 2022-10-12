<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

const CREATE_PROPOSAL_MUTATION = <<<'GQL'
mutation createProposal($title: String!, $userId: Int!) {
    createProposal(proposal: {
        title: $title
        userId: $userId
    }) {
        id
        title
        userId
    }
}
GQL;

it('validates input', function () {
    $user = authenticate();

    post(route('graphql'), [
        'query' => CREATE_PROPOSAL_MUTATION,
        'variables' => [
            'title' => '123',
            'userId' => 123,
        ],
    ])
        ->assertJsonStructure([
            'errors' => [
                [
                    'extensions' => [
                        'validation' => [
                            'proposal.title',
                            'proposal.userId',
                        ],
                    ],
                ]
            ],
        ]);

    post(route('graphql'), [
        'query' => CREATE_PROPOSAL_MUTATION,
        'variables' => [
            'title' => 'test title',
            'userId' => $user->id,
        ],
    ])
        ->assertJsonMissing([
            'errors' => [
                [
                    'extensions' => [
                        'validation' => [
                            'proposal.title',
                            'proposal.userId',
                        ],
                    ],
                ]
            ],
        ]);
});

it('creates proposal', function () {
    $user = authenticate();

    post(route('graphql'), [
        'query' => CREATE_PROPOSAL_MUTATION,
        'variables' => [
            'title' => 'test title',
            'userId' => $user->id,
        ],
    ])
        ->assertJson([
            'data' => [
                'createProposal' => [
                    'title' => 'test title',
                    'userId' => $user->id,
                ],
            ],
        ]);

    assertDatabaseHas('proposals', [
        'title' => 'test title',
        'user_id' => $user->id,
    ]);
});
