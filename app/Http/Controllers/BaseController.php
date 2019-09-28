<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseController extends Controller {
    protected $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function index(Request $request, $parent = []) {
        $param = $request->all();
        $query = $request->query();

        $baseUrl = $request->url() . '?';

        $pageLimit = 10;
        $pageOffset = 0;

        $queryParam = [
            'page_limit' => $pageLimit,
            'page_offset' => $pageOffset
        ];

        if (!empty($param['filter'])) {
            $filter = $param['filter'];
            $queryParam['filter'] = $filter;
        }

        if (!empty($param['short'])) {
            $short = $param['short'];
            $queryParam['short'] = $short;
        }

        if (!empty($param['fields'])) {
            $fields = $param['fields'];
            $queryParam['fields'] = $fields;
        }

        if (!empty($param['page_limit'])) {
            $pageLimit = $param['page_limit'];
            $queryParam['page_limit'] = $pageLimit;
        }

        if (!empty($param['page_offset'])) {
            $pageOffset = $param['page_offset'];
        }


        $itemRelation = false;
        if (!empty($param['include']) && $param['include'] == 'items') {
            $itemRelation = true;
            $queryParam['include'] = $param['include'];
        }

        $total = 0;
        $data = [];
        if (count($parent) > 0) {
            $total = $this->model->where($parent['fieldName'], $parent['parent_id'])->count();
            $data = $this->model->where($parent['fieldName'], $parent['parent_id'])->offset($pageOffset)->limit($pageLimit)->get();
        } else {
            $total = $this->model->count();
            $data = $this->model->offset($pageOffset)->limit($pageLimit)->get();
        }

        $firstUrl = $baseUrl . http_build_query($queryParam);
        $lastOffset = (int)($total / $pageLimit) * $pageLimit;
        $queryParam['page_offset'] = $lastOffset >= $total ? $total - $pageLimit : $lastOffset;
        $lastUrl = $baseUrl . http_build_query($queryParam);
        $nextUrl = null;
        if ($pageOffset + $pageLimit < $total) {
            $queryParam['page_offset'] = $pageOffset + $pageLimit;
            $nextUrl = $baseUrl . http_build_query($queryParam);
        }
        
        $prevUrl = null;
        if ($pageOffset != 0) {
            $queryParam['page_offset'] = ($pageOffset - $pageLimit) < 0 ? 0 : $pageOffset - $pageLimit;
            $prevUrl = $baseUrl . http_build_query($queryParam);
        }

        $result['meta'] = [
            'count' => count($data),
            'total' => $total
        ];

        $result['links'] = [
            'first' => $firstUrl,
            'last' => $lastUrl,
            'next' => $nextUrl,
            'prev' => $prevUrl
        ];

        $items = [];
        foreach ($data as $item) {
            $link = url($item->getSelfLink());
            $item = $item->toArray();
            unset($item['type']);
            $item['links']['self'] = $link;
            $items[] = $item;
        }

        $result['data'] = $items;

        return $result;
    }

    public function detail(Request $request, $id) {
        $param = $request->all();
        $item = null;
        $result = [];
        $include = false;

        if (!empty($param['include']) && $param['include'] == 'items') {
            $include = true;
            $item = $this->model->with('items')->find($id);
        } else {
            $item = $this->model->find($id);
        }

        if ($item == null) {
            return response('Not Found', 404);
            // $result = [
            //     'status' => '404',
            //     'error' => 'Not Found'
            // ];

            // return $result;
        }

        $attributes = [];
        foreach ($item->getVisible() as $attr) {
            if ($attr != 'type') {
                $attributes[$attr] = $item->{$attr};
            }
        }

        $checklist = new Checklist;
        if ($include && $this->model instanceof $checklist) {
            $attributes['items'] = $item->items;
        }

        $result ['data'] = [
            'type' => $item->type,
            'id' => $item->id,
            'attributes' => $attributes,
            'links' => [
                'self' => url($item->getSelfLink())
            ]
        ];

        return $result;
    }

    public function store(Request $request) {
        $param = $request->all();
        $item = $this->model->create($param);

        return $this->detail($request, $item->id);
    }

    public function update(Request $request, $id) {
        $param = $request->all();

        $item = $this->model->find($id);

        if ($item == null) {
            return response('Not Found', 404);
            // $result = [
            //     'status' => '404',
            //     'error' => 'Not Found'
            // ];

            // return $result;
        }

        $item->update($param);
        return $this->detail($request, $item->id);
    }

    public function delete($id) {
        $item = $this->model->find($id);

        if ($item == null) {
            return response('Not Found', 404);
            // $result = [
            //     'status' => '404',
            //     'error' => 'Not Found'
            // ];

            // return $result;
        }

        $result = $item->delete();

        if ($result) {
            return '204';
        }
    }
}