<?php

use App\Models\Proposal;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\post;

const DELETE_PROPOSAL_MUTATION = /** @lang GraphQL */'
mutation deleteProposal($id: ID!) {
    deleteProposal(id: $id)
}
';

it('deletes a proposal', function () {
    $user = authenticate();
    $proposal = Proposal::factory()
        ->for($user)
        ->create();

    post(route('graphql'), [
        'query' => DELETE_PROPOSAL_MUTATION,
        'variables' => [
            'id' => $proposal->id,
        ],
    ])
        ->assertJson([
            'data' => [
                'deleteProposal' => $proposal->id,
            ],
        ]);

    assertDatabaseMissing('proposals', [
        'id' => $proposal->id,
    ]);
});

it("doesn't delete a proposal belong to the other", function () {
    $user = authenticate();
    $proposal = Proposal::factory()
        ->create();

    post(route('graphql'), [
        'query' => DELETE_PROPOSAL_MUTATION,
        'variables' => [
            'id' => $proposal->id,
        ],
    ])
        ->assertGqlUnauthorized();

    assertDatabaseHas('proposals', [
        'id' => $proposal->id,
    ]);
});
