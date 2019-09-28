<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TemplateTest extends TestCase
{
    /**
    * @test
    */
    public function test_should_return_unauthorized() {
        $this->get('/checklists/templates', []);

        $this->seeStatusCode(401);
    }

    /**
    * @test
    */
    public function test_should_return_all_template() {
        $user = factory(User::class)->make();
        $this->actingAs($user)->get('/checklists/templates', []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                "meta" => [
                    "count",
                    "total"
                ],
                "links" => [
                    "first",
                    "last",
                    "next",
                    "prev"
                ],
                "data" => [
                    [
                        "name",
                        "links" => [
                            "self"
                        ]
                    ]
                ]
            ]
        );
    }

    /**
    * @test
    */
    public function test_should_return_template_by_id() {
        $user = factory(User::class)->make();
        $this->actingAs($user)->get('/checklists/templates/2', []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name'
                    ],
                    'links' => [
                        'self'
                    ]
                ]
            ]
        );
    }

    /**
    * @test
    */
    public function test_should_return_not_found_tempate() {
        $user = factory(User::class)->make();
        $this->actingAs($user)->get('/checklists/templates/20000', []);
        $this->seeStatusCode(404);
    }
}
