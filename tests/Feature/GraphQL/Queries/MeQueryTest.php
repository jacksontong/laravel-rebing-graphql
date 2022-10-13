<?php

use function Pest\Laravel\post;

const ME_QUERY = /** @lang GraphQL */'
{
    me {
        id
        name
        email
    }
}
';

it('fetches authenticated user', function () {
    $user = authenticate();

    post(route('graphql'), [
        'query' => ME_QUERY,
    ])
        ->assertJson([
            'data' => [
                'me' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ],
            ],
        ]);
});

it("doesn't allow unauthenticated user to access", function () {
    post(route('graphql'), [
        'query' => ME_QUERY,
    ])
        ->assertJson([
            'data' => [
                'me' => null,
            ],
        ]);
});
