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

    public function updateBulk(Request $request) {
        $params = $request->all();

        $itemResponse = [];
        foreach ($params as $param) {
            foreach ($param as $data) {
                if (!empty($data['id']) && !empty($data['action']) && $data['action'] == 'update') {
                    $id = $data['id'];
                    $item = Item::find($id);
                    $result = [
                        "id" => $id,
                        "action" => "update"
                    ];

                    if ($item != null) {
                        $attributes = $data['attributes'];
                        $item->update($attributes);
                        $result['status'] = 200;
                    } else {
                        $result['status'] = 404;
                    }

                    $itemResponse[] = $result;
                }
            }
        }
        
        return [
            'data' => $itemResponse
        ];
    }
}