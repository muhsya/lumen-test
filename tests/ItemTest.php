<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ItemTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function test_should_return_unauthorized() {
        $this->get('/items', []);

        $this->seeStatusCode(401);
    }

    /**
    * @test
    */
    public function test_should_return_all_item() {
        $user = factory(User::class)->make();
        factory(App\Models\Item::class, 10)->create();
        $this->actingAs($user)->get('/items', []);
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
                        "description",
                        "is_completed",
                        "completed_at",
                        "due",
                        "urgency",
                        "updated_by",
                        "created_by",
                        "checklist_id",
                        "assignee_id",
                        "task_id",
                        "deleted_at",
                        "created_at",
                        "updated_at",
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
    public function test_should_return_item_by_id() {
        $user = factory(User::class)->make();
        $item = factory(App\Models\Item::class)->create();
        $this->actingAs($user)->get('/items/' . $item->id, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        "description",
                        "is_completed",
                        "completed_at",
                        "due",
                        "urgency",
                        "updated_by",
                        "created_by",
                        "checklist_id",
                        "assignee_id",
                        "task_id",
                        "deleted_at",
                        "created_at",
                        "updated_at",
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
        $this->actingAs($user)->get('/items/1', []);
        $expected = ['status' => '404', 'error' => 'Not Found'];
        $this->seeStatusCode(200)->seeJson($expected);
        // $this->assertTrue($this->isSameArray($expected, json_decode($this->response->getContent())));
    }

    /**
    * @test
    */
    public function test_not_login_cannot_store_item() {
        $checklist = factory(App\Models\Checklist::class)->create();
        $user = factory(App\Models\User::class)->make();
        $parameters = [
            "description" => 'new description',
            "is_completed" => true,
            "completed_at" => '2019-09-01',
            "due" => '2019-09-01',
            "urgency" => 1,
            "updated_by" => 'Admin',
            "created_by" => 'Admin',
            "checklist_id" => $checklist->id,
            "assignee_id" => $user->id,
            "task_id" => 111,
            "deleted_at" => '2019-09-01',
            "created_at" => '2019-09-01',
            "updated_at" => '2019-09-01',
        ];

        $this->post('/items', $parameters, []);
        $this->seeStatusCode(401);
    }

    /**
    * @test
    */
    public function test_not_login_cannot_update_item() {
        $checklist = factory(App\Models\Checklist::class)->create();
        $user = factory(App\Models\User::class)->make();
        $parameters = [
            "description" => 'new description',
            "is_completed" => true,
            "completed_at" => '2019-09-01',
            "due" => '2019-09-01',
            "urgency" => 1,
            "updated_by" => 'Admin',
            "created_by" => 'Admin',
            "checklist_id" => $checklist->id,
            "assignee_id" => $user->id,
            "task_id" => 111,
            "deleted_at" => '2019-09-01',
            "created_at" => '2019-09-01',
            "updated_at" => '2019-09-01',
        ];

        $item = factory(App\Models\Item::class)->create();

        $this->post('/items/' . $item->id, $parameters, []);
        $this->seeStatusCode(401);
    }

    /**
    * @test
    */
    public function test_can_store_item() {
        $checklist = factory(App\Models\Checklist::class)->create();
        $user = factory(App\Models\User::class)->make();
        $parameters = [
            "description" => 'new description',
            "is_completed" => true,
            "completed_at" => '2019-09-01',
            "due" => '2019-09-01',
            "urgency" => 1,
            "updated_by" => 'Admin',
            "created_by" => 'Admin',
            "checklist_id" => $checklist->id,
            "assignee_id" => $user->id,
            "task_id" => 111,
            "deleted_at" => '2019-09-01',
            "created_at" => '2019-09-01',
            "updated_at" => '2019-09-01',
        ];

        $user = factory(User::class)->make();
        $this->actingAs($user)->post('/items', $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'description',
                        'is_completed',
                        'completed_at',
                        'due',
                        'urgency',
                        'updated_by',
                        'created_by',
                        'checklist_id',
                        'assignee_id',
                        'task_id',
                        'deleted_at',
                        'created_at',
                        'updated_at',
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
    public function test_can_update_item() {
        $checklist = factory(App\Models\Checklist::class)->create();
        $user = factory(App\Models\User::class)->make();
        $parameters = [
            "description" => 'new description',
            "is_completed" => true,
            "completed_at" => '2019-09-01',
            "due" => '2019-09-01',
            "urgency" => 1,
            "updated_by" => 'Admin',
            "created_by" => 'Admin',
            "checklist_id" => $checklist->id,
            "assignee_id" => $user->id,
            "task_id" => 111,
            "deleted_at" => '2019-09-01',
            "created_at" => '2019-09-01',
            "updated_at" => '2019-09-01',
        ];

        $user = factory(User::class)->make();
        $item = factory(App\Models\Item::class)->create();
        $this->actingAs($user)->post('/items/' . $item->id, $parameters, []);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(
            [
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'description',
                        'is_completed',
                        'completed_at',
                        'due',
                        'urgency',
                        'updated_by',
                        'created_by',
                        'checklist_id',
                        'assignee_id',
                        'task_id',
                        'deleted_at',
                        'created_at',
                        'updated_at',
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
    public function test_can_delete_item() {
        $item = factory(App\Models\Item::class)->create();

        $user = factory(User::class)->make();
        $this->actingAs($user)->delete('/items/' . $item->id, [], []);
        $this->seeStatusCode(200);
        $this->assertEquals(204, $this->response->getContent());
    }
}
