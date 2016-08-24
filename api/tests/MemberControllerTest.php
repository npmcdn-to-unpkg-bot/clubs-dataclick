<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class MemberControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $endpoint;

    /**
     * MemberControllerTest constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->endpoint = $this->baseUrl . '/members';
    }

    public function testSome()
    {
        $this->get($this->endpoint)
            ->seeStatusCode(204);

        $this->post($this->baseUrl . '/clubs', [
            'name' => 'Flamengo'
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Flamengo'
                ]
            ])
            ->seeStatusCode(201);

        $this->post($this->endpoint, [
            'name' => 'Leandro',
            'clubs' => [
                ['id' => 1],
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro'
                ]
            ])
            ->seeStatusCode(201);

        $this->get($this->endpoint)
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro'
                ]
            ])
            ->seeStatusCode(200);

        $this->post($this->baseUrl . '/clubs', [
            'name' => 'Fluminense'
        ])
            ->seeJsonEquals([
                [
                    'id' => 2,
                    'name' => 'Fluminense'
                ]
            ])
            ->seeStatusCode(201);

        $this->patch($this->endpoint . '/1', [
            'op' => 'add',
            'path' => '/clubs',
            'value' => [
                ['id' => 2]
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                    'clubs' => [
                        ['id' => 1, 'name' => 'Flamengo'],
                        ['id' => 2, 'name' => 'Fluminense']
                    ]
                ]
            ])
            ->seeStatusCode(200);

        $this->patch($this->endpoint . '/1', [
            'op' => 'delete',
            'path' => '/clubs',
            'value' => [
                ['id' => 1]
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                    'clubs' => [
                        ['id' => 2, 'name' => 'Fluminense']
                    ]
                ]
            ])
            ->seeStatusCode(200);
    }

    public function testErrorSameName()
    {
        $this->post($this->baseUrl . '/clubs', [
            'name' => 'Flamengo'
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Flamengo'
                ]
            ])
            ->seeStatusCode(201);

        $this->post($this->endpoint, [
            'name' => 'Leandro',
            'clubs' => [
                ['id' => 1],
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                ]
            ])
            ->seeStatusCode(201);

        $this->post($this->endpoint, [
            'name' => 'Leandro',
            'clubs' => [
                'id' => 1,
            ]
        ])
            ->seeJsonEquals([
                'error' => 'Error storing member',
                'error_description' => [
                    'name' => ['The name has already been taken.']
                ]
            ])
            ->seeStatusCode(400);
    }

    public function testNewSameClub()
    {
        //new Flamengo club
        $this->post($this->baseUrl . '/clubs', [
            'name' => 'Flamengo'
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Flamengo'
                ]
            ])
            ->seeStatusCode(201);

        //new Leandro member with Flamengo club
        $this->post($this->endpoint, [
            'name' => 'Leandro',
            'clubs' => [
                ['id' => 1],
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                ]
            ])
            ->seeStatusCode(201);

        //new club, Flamengo again, to Leandro member
        $this->patch($this->endpoint . '/1', [
            'op' => 'add',
            'path' => '/clubs',
            'value' => [
                ['id' => 1]
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                    'clubs' => [
                        [
                            'id' => 1,
                            'name' => 'Flamengo'
                        ]
                    ]
                ]
            ])
            ->seeStatusCode(200);
    }

    public function testMoreThanOneClubOnRegister()
    {
        //new Flamengo club
        $this->post($this->baseUrl . '/clubs', [
            'name' => 'Flamengo'
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Flamengo'
                ]
            ])
            ->seeStatusCode(201);

        //new Fluminense club
        $this->post($this->baseUrl . '/clubs', [
            'name' => 'Fluminense'
        ])
            ->seeJsonEquals([
                [
                    'id' => 2,
                    'name' => 'Fluminense'
                ]
            ])
            ->seeStatusCode(201);

        //new Leandro member with Flamengo and Fluminense clubs
        $this->post($this->endpoint, [
            'name' => 'Leandro',
            'clubs' => [
                ['id' => 1],
                ['id' => 2],
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                ]
            ])
            ->seeStatusCode(201);

        $this->get($this->endpoint . '/1')
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                    'clubs' => [
                        [
                            'id' => 1,
                            'name' => 'Flamengo'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Fluminense'
                        ]
                    ]
                ]
            ])
            ->seeStatusCode(200);
    }

    public function testErrorAddSameClub()
    {
        $this->post($this->baseUrl . '/clubs', [
            'name' => 'Flamengo'
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Flamengo'
                ]
            ])
            ->seeStatusCode(201);

        $this->post($this->endpoint, [
            'name' => 'Leandro',
            'clubs' => [
                ['id' => 1],
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                ]
            ])
            ->seeStatusCode(201);

        $this->patch($this->endpoint . '/1', [
            'op' => 'add',
            'path' => '/clubs',
            'value' => [
                ['id' => 1]
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                    'clubs' => [
                        [
                            'id' => 1,
                            'name' => 'Flamengo'
                        ]
                    ]
                ]
            ])
            ->seeStatusCode(200);

        $this->patch($this->endpoint . '/1', [
            'op' => 'add',
            'path' => '/clubs',
            'value' => [
                ['id' => 1]
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                    'clubs' => [
                        [
                            'id' => 1,
                            'name' => 'Flamengo'
                        ]
                    ]
                ]
            ])
            ->seeStatusCode(200);

        $this->get($this->endpoint . '/1')
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                    'clubs' => [
                        [
                            'id' => 1,
                            'name' => 'Flamengo'
                        ]
                    ]
                ]
            ])
            ->seeStatusCode(200);
    }

    public function testErrorAddAndRemoveClub()
    {
        $this->post($this->baseUrl . '/clubs', [
            'name' => 'Flamengo'
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Flamengo'
                ]
            ])
            ->seeStatusCode(201);

        $this->post($this->baseUrl . '/clubs', [
            'name' => 'Fluminense'
        ])
            ->seeJsonEquals([
                [
                    'id' => 2,
                    'name' => 'Fluminense'
                ]
            ])
            ->seeStatusCode(201);

        $this->post($this->endpoint, [
            'name' => 'Leandro',
            'clubs' => [
                ['id' => 1],
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                ]
            ])
            ->seeStatusCode(201);

        $this->patch($this->endpoint . '/1', [
            'op' => 'add',
            'path' => '/clubs',
            'value' => [
                ['id' => 2]
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                    'clubs' => [
                        [
                            'id' => 1,
                            'name' => 'Flamengo'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Fluminense'
                        ]
                    ]
                ]
            ])
            ->seeStatusCode(200);

        $this->patch($this->endpoint . '/1', [
            'op' => 'delete',
            'path' => '/clubs',
            'value' => [
                ['id' => 2]
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                    'clubs' => [
                        [
                            'id' => 1,
                            'name' => 'Flamengo'
                        ]
                    ]
                ]
            ])
            ->seeStatusCode(200);

        $this->patch($this->endpoint . '/1', [
            'op' => 'delete',
            'path' => '/clubs',
            'value' => [
                ['id' => 2]
            ]
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                    'clubs' => [
                        [
                            'id' => 1,
                            'name' => 'Flamengo'
                        ]
                    ]
                ]
            ])
            ->seeStatusCode(200);

        $this->get($this->endpoint . '/1')
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro',
                    'clubs' => [
                        [
                            'id' => 1,
                            'name' => 'Flamengo'
                        ]
                    ]
                ]
            ])
            ->seeStatusCode(200);
    }

    public function testTryStoreMemberWithoutClub() {
        $this->post($this->endpoint, [
            'name' => 'Leandro'
        ])
            ->seeStatusCode(400);
    }

    public function testDeleteMember() {

        $this->post($this->baseUrl . '/clubs', [
            'name' => 'Flamengo'
        ])
            ->seeJsonEquals([[
                'id' => 1,
                'name' => 'Flamengo'
            ]])
            ->seeStatusCode(201);

        $this->post($this->endpoint, [
            'name' => 'Leandro',
            'clubs' => [
                ['id' => 1]
            ]
        ])
            ->seeJsonEquals([[
                'id' => 1,
                'name' => 'Leandro'
            ]])
            ->seeStatusCode(201);

        $this->delete($this->endpoint . '/1')
            ->seeStatusCode(204);
    }
}
