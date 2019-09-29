<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TemplateTest extends TestCase
{
    use DatabaseMigrations;

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
        factory(App\Models\Template::class, 10)->create();
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
        $template = factory(App\Models\Template::class)->create();
        $this->actingAs($user)->get('/checklists/templates/' . $template->id, []);
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
        $this->actingAs($user)->get('/checklists/templates/1', []);
        $expected = ['status' => '404', 'error' => 'Not Found'];
        $this->seeStatusCode(200)->seeJson($expected);
        // $this->assertTrue($this->isSameArray($expected, json_decode($this->response->getContent())));
    }

    /**
    * @test
    */
    public function test_not_login_cannot_store_template() {
        $parameters = [
            'type' => 'template',
            'name' => 'new template'
        ];

        $this->post('/checklists/templates', $parameters, []);
        $this->seeStatusCode(401);
    }

    /**
    * @test
    */
    public function test_not_login_cannot_update_template() {
        $parameters = [
            'type' => 'template',
            'name' => 'new template'
        ];

        $template = factory(App\Models\Template::class)->create();

        $this->post('/checklists/templates/' . $template->id, $parameters, []);
        $this->seeStatusCode(401);
    }

    /**
    * @test
    */
    public function test_can_store_template() {
        $parameters = [
            'type' => 'template',
            'name' => 'new template'
        ];

        $user = factory(User::class)->make();
        $this->actingAs($user)->post('/checklists/templates', $parameters, []);
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
    public function test_can_update_template() {
        $parameters = [
            'type' => 'template',
            'name' => 'new template'
        ];

        $user = factory(User::class)->make();
        $template = factory(App\Models\Template::class)->create();
        $this->actingAs($user)->post('/checklists/templates/' . $template->id, $parameters, []);
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
    public function test_can_delete_template() {
        $template = factory(App\Models\Template::class)->create();

        $user = factory(User::class)->make();
        $this->actingAs($user)->delete('/checklists/templates/' . $template->id, [], []);
        $this->seeStatusCode(200);
        $this->assertEquals(204, $this->response->getContent());
    }

    private function isSameArray($arr1, $arr2) {
        if (count($arr1) != count($arr2)) {
            return false;
        }

        foreach ($arr1 as $key => $value) {
            if (empty($arr2[$key]) || (!empty($arr2[$key]) && $arr2[$key] != $value)) {
                return false;
            }
        }

        return true;
    }
}
