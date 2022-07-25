<?php

class CategoryApi extends Api
{
    public $apiName = 'category';

    public function listAction()
    {
        $list = Category::getCategoryList();
        if ($list) {
            return $this->response($list, 200);
        }
        return $this->response('Data not found', 404);
    }

    public function menuListAction()
    {
        $list = Category::getCategoryListWithPostName();
        if ($list) {
            return $this->response($list, 200);
        }
        return $this->response('Data not found', 404);
    }

    public function createAction()
    {
        $formData = json_decode(file_get_contents('php://input'), true);
        $item['title'] = $formData['title'];
        $item['module_name'] = $formData['moduleName'];
        $item['sort'] = Category::countCategory();
        if (!empty($item)) {
            $id = Category::createCategory($item);
            if ($id !== 0) {
                return $this->response($id, 200);
            }
        }
        return $this->response("Saving error", 500);
    }

    public function deleteAction()
    {
        $id = array_shift($this->requestUri);

        if (Category::deleteCategoryById($id)) {
            return $this->response('Data deleted.', 200);
        } else {
            return $this->response("Delete error", 500);
        }
    }

    public function getAction()
    {
        $id = array_shift($this->requestUri);

        if ($id) {
            $item = Category::getById($id);
            $result['title'] = $item['title'];
            $result['moduleName'] = $item['module_name'];
            if ($result) {
                return $this->response($result, 200);
            }
        }
        return $this->response('Data not found', 404);
    }

    public function editAction()
    {
        $id = array_shift($this->requestUri);
        $formData = json_decode(file_get_contents('php://input'), true);
        $item['id'] = $id;
        $item['title'] = $formData['title'];
        $item['module_name'] = $formData['moduleName'];
        if (!empty($item)) {
            $id = Category::editCategory($item);
            if ($id !== 0) {
                return $this->response($id, 200);
            }
        }
        return $this->response("Saving error", 500);
    }

    public function sortAction()
    {
        $formData = json_decode(file_get_contents('php://input'), true);
        $array = $formData['data'];
        $array_count = count($array);
        $count = 0;

        foreach ($array as $value) {
            if (Category::sortCategory($value)) {
                $count++;
            }
        }

        if ($count === $array_count) {
            return $this->response(true, 200);
        }
        return $this->response("Saving error", 500);
    }
}
