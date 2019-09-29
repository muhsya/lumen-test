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

        $baseUrl = $request->url() . '?';

        $pageLimit = 10;
        $pageOffset = 0;

        $queryParam = [
            'page_limit' => $pageLimit,
            'page_offset' => $pageOffset
        ];

        $query = $this->model->offset($pageOffset)->limit($pageLimit);

        if (!empty($param['filter'])) {
            $filter = $param['filter'];
            $field = '';
            $operator = '';
            $value = '';
            foreach ($filter as $key => $ops) {
                $field = $key;
                foreach ($ops as $op => $search) {
                    $operator = $op;
                    $value = $search;
                }
            }

            if ($field != '' && $operator != '' && $value != '') {
                if ($operator == 'between') {
                    $value = explode(',', $value);
                    $query = $query->where($field, '>=', $value[0])->where($field, '<=', $value[1]);
                } else {
                    $query = $query->where($field, $value);
                }
            }

            $queryParam['filter'] = $filter;
        }

        if (!empty($param['sort'])) {
            $sort = $param['sort'];
            $queryParam['sort'] = $sort;

            if (strpos($sort, '-') === 0) {
                $sort = str_replace('-', '', $sort);
                $query = $query->orderBy($sort, 'desc');
            } else {
                $query = $query->orderBy($sort);
            }
        }

        if (!empty($param['page_limit'])) {
            $pageLimit = $param['page_limit'];
            $query = $query->limit($pageLimit);
            $queryParam['page_limit'] = $pageLimit;
        }

        if (!empty($param['page_offset'])) {
            $pageOffset = $param['page_offset'];
            $query = $query->limit($pageOffset);
        }

        if (!empty($param['include']) && $param['include'] == 'items') {
            $query = $query->with('items');
        }

        $total = 0;
        if (count($parent) > 0) {
            $total = $this->model->where($parent['fieldName'], $parent['parent_id'])->count();
            $query = $query->where($parent['fieldName'], $parent['parent_id'])->offset($pageOffset)->limit($pageLimit);
        } else {
            $total = $this->model->count();
        }

        $data = $query->get();

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
            return [
                'status' => '404',
                'error' => 'Not Found'
            ];
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
            return [
                'status' => '404',
                'error' => 'Not Found'
            ];
        }

        $item->update($param);
        return $this->detail($request, $item->id);
    }

    public function delete($id) {
        $item = $this->model->find($id);

        if ($item == null) {
            return [
                'status' => '404',
                'error' => 'Not Found'
            ];
        }

        $result = $item->delete();

        if ($result) {
            return '204';
        }
    }
}