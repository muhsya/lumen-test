<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ChecklistTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function test_should_return_unauthorized() {
        $this->get('/checklists', []);

        $this->seeStatusCode(401);
    }

    /**
    * @test
    */
    public function test_should_return_all_checklist() {
        $user = factory(User::class)->make();
        factory(App\Models\Checklist::class, 10)->create();
        $this->actingAs($user)->get('/checklists', []);
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
                        "object_domain",
                        "object_id",
                        "description",
                        "is_completed",
                        "due",
                        "task_id",
                        "urgency",
                        "completed_at",
                        "updated_by",
                        "updated_at",
                        "created_at",
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
    public function test_should_return_checklist_by_id() {
        $user = factory(User::class)->make();
        $checklist = factory(App\Models\Checklist::class)->create();
        $this->actingAs($user)->get('/checklists/' . $checklist->id, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'object_domain',
                        'object_id',
                        'description',
                        'is_completed',
                        'due',
                        'task_id',
                        'urgency',
                        'completed_at',
                        'updated_by',
                        'updated_at',
                        'created_at'
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
        $this->actingAs($user)->get('/checklists/1', []);
        $expected = ['status' => '404', 'error' => 'Not Found'];
        $this->seeStatusCode(200)->seeJson($expected);
        // $this->assertTrue($this->isSameArray($expected, json_decode($this->response->getContent())));
    }

    /**
    * @test
    */
    public function test_not_login_cannot_store_checklist() {
        $parameters = [
            'object_domain' => 'new object domain',
            'object_id' => 'new object id',
            'description' => 'new description',
            'is_completed' => true,
            'due' => '2019-09-01',
            'task_id' => 123,
            'urgency' => 2,
            'completed_at' => '2019-09-01',
            'updated_by' => 'Admin',
            'updated_at' => null,
            'created_at' => '2019-09-01'
        ];

        $this->post('/checklists', $parameters, []);
        $this->seeStatusCode(401);
    }

    /**
    * @test
    */
    public function test_not_login_cannot_update_checklist() {
        $parameters = [
            'object_domain' => 'new object domain',
            'object_id' => 'new object id',
            'description' => 'new description',
            'is_completed' => true,
            'due' => '2019-09-01',
            'task_id' => 123,
            'urgency' => 2,
            'completed_at' => '2019-09-01',
            'updated_by' => 'Admin',
            'updated_at' => null,
            'created_at' => '2019-09-01'
        ];

        $checklist = factory(App\Models\Checklist::class)->create();

        $this->post('/checklists/' . $checklist->id, $parameters, []);
        $this->seeStatusCode(401);
    }

    /**
    * @test
    */
    public function test_can_store_checklist() {
        $parameters = [
            'object_domain' => 'new object domain',
            'object_id' => 'new object id',
            'description' => 'new description',
            'is_completed' => true,
            'due' => '2019-09-01',
            'task_id' => 123,
            'urgency' => 2,
            'completed_at' => '2019-09-01',
            'updated_by' => 'Admin',
            'updated_at' => null,
            'created_at' => '2019-09-01'
        ];

        $user = factory(User::class)->make();
        $this->actingAs($user)->post('/checklists', $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'object_domain',
                        'object_id',
                        'description',
                        'is_completed',
                        'due',
                        'task_id',
                        'urgency',
                        'completed_at',
                        'updated_by',
                        'updated_at',
                        'created_at'
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
    public function test_can_update_checklist() {
        $parameters = [
            'object_domain' => 'new object domain',
            'object_id' => 'new object id',
            'description' => 'new description',
            'is_completed' => true,
            'due' => '2019-09-01',
            'task_id' => 123,
            'urgency' => 2,
            'completed_at' => '2019-09-01',
            'updated_by' => 'Admin',
            'updated_at' => null,
            'created_at' => '2019-09-01'
        ];

        $user = factory(User::class)->make();
        $checklist = factory(App\Models\Checklist::class)->create();
        $this->actingAs($user)->post('/checklists/' . $checklist->id, $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'object_domain',
                        'object_id',
                        'description',
                        'is_completed',
                        'due',
                        'task_id',
                        'urgency',
                        'completed_at',
                        'updated_by',
                        'updated_at',
                        'created_at'
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
    public function test_can_delete_checklist() {
        $checklist = factory(App\Models\Checklist::class)->create();

        $user = factory(User::class)->make();
        $this->actingAs($user)->delete('/checklists/' . $checklist->id, [], []);
        $this->seeStatusCode(200);
        $this->assertEquals(204, $this->response->getContent());
    }
}
