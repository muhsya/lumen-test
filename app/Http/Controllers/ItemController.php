<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends BaseController {
    public function __construct(Item $model) {
        $this->model = $model;
    }

    public function getByChecklist(Request $request, $checklistId) {
        $parent = [
            'fieldName' => 'checklist_id',
            'parent_id' => $checklistId
        ];
        $result = $this->index($request, $parent);

        return $result;
    }

    public function summaries(Request $request) {
        $param = $request->all();
    }
}