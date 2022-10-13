<?php

use App\Models\Proposal;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

const UPDATE_PROPOSAL_MUTATION = /** @lang GraphQL */'
mutation updateProposal($id: ID!, $title: String) {
    updateProposal(id: $id, proposal: {
        title: $title
    }) {
        id
        title
        user {
            id
            name
        }
    }
}
';

it('updates a proposal by id', function () {
    $user = authenticate();
    $proposal = Proposal::factory()
        ->for($user)
        ->create();

    post(route('graphql'), [
        'query' => UPDATE_PROPOSAL_MUTATION,
        'variables' => [
            'id' => $proposal->id,
            'title' => 'New title',
        ]
    ])
        ->assertJson([
            'data' => [
                'updateProposal' => [
                    'id' => $proposal->id,
                    'title' => 'New title',
                ],
            ],
        ]);

    assertDatabaseHas('proposals', [
        'id' => $proposal->id,
        'title' => 'New title',
    ]);
});

it("doesn't allow to update proposal of the other", function () {
    $user = authenticate();
    $proposal = Proposal::factory()
        ->create();

    post(route('graphql'), [
        'query' => UPDATE_PROPOSAL_MUTATION,
        'variables' => [
            'id' => $proposal->id,
            'title' => 'new title',
        ]
    ])
        ->assertGqlUnauthorized();

    assertDatabaseHas('proposals', [
        'id' => $proposal->id,
        'title' => $proposal->title,
    ]);
});

it('validates input', function ($title) {
    $user = authenticate();
    $proposal = Proposal::factory()
        ->for($user)
        ->create();

    post(route('graphql'), [
        'query' => UPDATE_PROPOSAL_MUTATION,
        'variables' => [
            'id' => $proposal->id,
            'title' => $title,
        ]
    ])
        ->assertGqlValidationErrorFor('proposal.title');
})->with([
    null,
    '2',
]);
