<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;

class Checklist extends Model {
    protected $fillable = [
    	'type',
		'object_domain',
		'object_id',
		'description',
		'is_completed',
		'due',
		'task_id',
		'urgency',
		'completed_at',
		'updated_by'
    ];

    protected $visible = [
    	'type',
    	'object_domain',
		'object_id',
		'description',
		'is_completed',
		'due',
		'task_id',
		'urgency',
		'completed_at',
		'updated_by',
		'created_at',
		'updated_at'
    ];

    public function items() {
    	return $this->hasMany(Item::class, "checklist_id");
    }

    public function getSelfLink() {
    	return 'checklists/' . $this->id;
    }

    public function getIsCompletedAttribute($value) {
    	return $value != 0;
    }
}