<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    protected $fillable = [
    	'type',
		'description',
		'is_completed',
		'due',
		'task_id',
		'urgency',
		'completed_at',
		'updated_by',
		'checklist_id',
		'assignee_id',
		'created_by',
		'deleted_at'
    ];

    protected $visible = [
    	'type',
		'description',
		'is_completed',
		'due',
		'task_id',
		'urgency',
		'completed_at',
		'updated_by',
		'created_at',
		'updated_at',
		'checklist_id',
		'assignee_id',
		'created_by',
		'deleted_at'
    ];

    public function getSelfLink() {
    	return 'checklists/' . $this->checklist_id . '/templates/' . $this->id;
    }
}