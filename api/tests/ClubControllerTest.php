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

    /**
     * Tests in laravel is so poor.
     * Need other test library.
     */
    public function testSomeActions()
    {
        //it returns no body and status 204
        $this->post($this->endpoint, [
            'name' => 'Flamengo',
        ])
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Flamengo'
                ]
            ])
            ->seeStatusCode(201);

        //it returns no body and status 204
        $this->post($this->endpoint, [
            'name' => 'Fluminense'
        ])
            ->seeJsonEquals([
                [
                    'id' => 2,
                    'name' => 'Fluminense'
                ]
            ])
            ->seeStatusCode(201);

        //it returns status code 204
        $this->get($this->endpoint)
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

        $this->delete($this->endpoint . '/1')
            ->seeStatusCode(204);

        $this->delete($this->endpoint . '/1')
            ->seeStatusCode(404);

        $this->get($this->endpoint . '/1')
            ->seeStatusCode(404);

        $this->get($this->endpoint)
            ->seeJsonEquals([
                [
                    'id' => 2,
                    'name' => 'Fluminense'
                ]
            ])
            ->seeStatusCode(200);

        $this->get($this->endpoint . '/2')
            ->seeJsonEquals([
                [
                    'id' => 2,
                    'name' => 'Fluminense',
                    'members' => [
                    ]
                ]
            ])
            ->seeStatusCode(200);

        $this->delete($this->endpoint . '/2')
            ->seeStatusCode(204);

        $this->get($this->endpoint)
            ->seeStatusCode(204);
    }
}
