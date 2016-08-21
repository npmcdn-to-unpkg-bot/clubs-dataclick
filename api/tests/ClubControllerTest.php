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

    public function testIndexAction()
    {
        //it returns status code 204
        $this->json('GET', $this->endpoint)
            ->seeStatusCode(204);
    }

    public function testStoreAction()
    {
        //it returns no body and status 204
        $this->json('POST', $this->endpoint, [
            'name' => 'Leandro',
        ])
            ->seeJsonEquals([
                'id' => 1,
                'name' => 'Leandro'
            ])
            ->seeStatusCode(201);

        //it returns no body and status 204
        $this->json('POST', $this->endpoint, [
            'name' => 'Lourenci'
        ])
            ->seeJsonEquals([
                'id' => 2,
                'name' => 'Lourenci'
            ])
            ->seeStatusCode(201);

        //it returns status code 204
        $this->json('GET', $this->endpoint)
            ->seeJsonEquals([
                [
                    'id' => 1,
                    'name' => 'Leandro'
                ],
                [
                    'id' => 2,
                    'name' => 'Lourenci'
                ]
            ])
            ->seeStatusCode(200);

        $this->delete($this->endpoint . '/1')
            ->seeStatusCode(204);

        $this->delete($this->endpoint . '/1')
            ->seeStatusCode(404);

        $this->json('GET', $this->endpoint . '/1')
            ->seeStatusCode(404);

        $this->json('GET', $this->endpoint, [
            'name' => 'Lourenci'
        ])
            ->seeJsonEquals([
                [
                    'id' => 2,
                    'name' => 'Lourenci'
                ]
            ])
            ->seeStatusCode(200);


        $this->json('GET', $this->endpoint . '/2')
            ->seeJsonEquals([
                [
                    'id' => 2,
                    'name' => 'Lourenci',
                    'members' => [
                    ]
                ]
            ])
            ->seeStatusCode(200);

    }


}
