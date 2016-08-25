<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ClubControllerTest extends TestCase
{
    use DatabaseMigrations;

    private $endpoint;

    public function __construct()
    {
        parent::__construct();

        $this->endpoint = $this->baseUrl . '/clubs';
    }

    public function testSomeActions()
    {
        //new club
        $this->post($this->endpoint, [
            'name' => 'Flamengo',
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Flamengo'
                ]
            ])
            ->seeStatusCode(200);

        //new club
        $this->post($this->endpoint, [
            'name' => 'Fluminense'
        ])
            ->seeJsonEquals([
                [
                    'id' => 2,
                    'name' => 'Fluminense'
                ]
            ])
            ->seeStatusCode(200);

        //get clubs
        $this->get($this->endpoint . '?fields=name')
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Flamengo',
                ],
                [
                    'id' => 2,
                    'name' => 'Fluminense',
                ]
            ])
            ->seeStatusCode(200);

        //delete the club
        $this->delete($this->endpoint . '/1')
            ->seeStatusCode(204);

        //delete the club that does not exist
        $this->delete($this->endpoint . '/1')
            ->seeStatusCode(404);

        //get the club that does not exist
        $this->get($this->endpoint . '/1')
            ->seeStatusCode(404);

        //get the clubs
        $this->get($this->endpoint)
            ->seeJsonEquals([
                [
                    'id' => 2,
                    'name' => 'Fluminense',
                    'members' => []
                ]
            ])
            ->seeStatusCode(200);

        //get the club
        $this->get($this->endpoint . '/2')
            ->seeJsonEquals([
                [
                    'id' => 2,
                    'name' => 'Fluminense',
                    'members' => []
                ]
            ])
            ->seeStatusCode(200);

        //delete the club
        $this->delete($this->endpoint . '/2')
            ->seeStatusCode(204);

        //get the clubs
        $this->get($this->endpoint)
            ->seeStatusCode(204);
    }

    public function testSameClubTwice()
    {
        //new club
        $this->post($this->endpoint, [
            'name' => 'Flamengo'
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Flamengo'
                ]
            ])
            ->seeStatusCode(200);

        //new same club
        $this->post($this->endpoint, [
            'name' => 'Flamengo'
        ])
            ->seeJsonEquals([
                'error' => 'Error storing club',
                'error_description' => [
                    'name' => ['The name has already been taken.']
                ]
            ])
            ->seeStatusCode(400);
    }

    public function testIndex()
    {

        $this->post($this->endpoint, [
            'name' => 'Flamengo'
        ]);

        $this->post($this->endpoint, [
            'name' => 'Fluminense'
        ]);

        $this->post($this->baseUrl . '/members', [
            'name' => 'Leandro',
            'clubs' => [
                ['id' => 1],
                ['id' => 2]
            ]
        ]);

        $this->get($this->endpoint)
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Flamengo',
                    'members' => [
                        [
                            'id' => 1,
                            'name' => 'Leandro'
                        ]
                    ]
                ],
                [
                    'id' => 2,
                    'name' => 'Fluminense',
                    'members' => [
                        [
                            'id' => 1,
                            'name' => 'Leandro'
                        ]
                    ]
                ],
            ])
            ->seeStatusCode(200);

        $this->get($this->endpoint . '?fields=name')
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Flamengo'
                ],
                [
                    'id' => 2,
                    'name' => 'Fluminense'
                ]
            ])
            ->seeStatusCode(200);
    }
}
