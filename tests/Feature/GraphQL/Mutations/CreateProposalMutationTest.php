<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

const CREATE_PROPOSAL_MUTATION = <<<'GQL'
mutation createProposal($title: String!) {
    createProposal(proposal: {
        title: $title
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
            'title' => '123'
        ],
    ])
        ->assertGqlValidationErrorFor('proposal.title');
});

it('creates proposal', function () {
    $user = authenticate();

    post(route('graphql'), [
        'query' => CREATE_PROPOSAL_MUTATION,
        'variables' => [
            'title' => 'test title',
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
