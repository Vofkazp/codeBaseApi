<?php

class PostsApi extends Api {

    public $apiName = 'posts';

    public function getAction() {
        $id = array_shift($this->requestUri);

        if ($id) {
            $item = Posts::getNameById($id);
            $result['title'] = $item['title'];
            $result['moduleName'] = $item['module_name'];
            $result['categoryId'] = $item['category_id'];
            $item2 = Posts::getPostById($id);
            $result['description'] = $item2['description'];
            $code = explode(', ', $item2['codeblock']);
            foreach ($code as $value) {
                $result['codeBlock'][] = Posts::getCodeById($value);
            }
            if ($result) {
                return $this->response($result, 200);
            }
        }
        return $this->response('Data not found', 404);
    }

    public function createAction() {
        $formData = json_decode(file_get_contents('php://input'), true);
        $post_name['category_id'] = $formData['categoryId'];
        $post_name['module_name'] = $formData['moduleName'];
        $post_name['title'] = $formData['title'];
        $id = Posts::createPost($post_name);
        if ($id) {
            $codeBlock = $formData['codeBlock'];
            $codeArr = [];
            foreach ($codeBlock as $value) {
                $ids = Posts::createCodePost($value);
                if ($ids) {
                    $codeArr[] = $ids;
                }
            }
            if (count($codeBlock) === count($codeArr)) {
                $item['codeBlock'] = implode(', ', $codeArr);
                $item['description'] = $formData['description'];
                $item['post_name_id'] = $id;
                $result = Posts::createPostName($item);
                if ($result) {
                    return $this->response($result, 200);
                }
            }
        }
        return $this->response("Saving error", 500);
    }

    public function deleteAction() {
        $id = array_shift($this->requestUri);
        $result = [];
        if ($id) {
            $result[] = Posts::deletePostNameById($id);
            $item = Posts::getPostById($id);
            $result[] = Posts::deletePostsById($id);
            $codeArray = explode(', ', $item['codeblock']);
            foreach ($codeArray as $value) {
                $result[] = Posts::deleteCodeById($value);
            }
//            $isDelete = array_search(false, $result);
//            if ($isDelete === false) {
                return $this->response(true, 200);
//            }
        }
        return $this->response("Delete error", 500);
    }

    public function updateAction() {
        $id = array_shift($this->requestUri);
        $formData = json_decode(file_get_contents('php://input'), true);
        $result = [];
        if ($id) {
            $item['id'] = $id;
            $item['title'] = $formData['title'];
            $item['module_name'] = $formData['moduleName'];
            $item['category_id'] = $formData['categoryId'];
            $result[] = Posts::editPostNameById($item);
            $codeItem = [];
            $codeBlock = $formData['codeBlock'];
            foreach ($codeBlock as $value) {
                if ($value['id'] === null && $value['isDeleted'] === false) {
                    $tempId = Posts::createCodePost($value);
                    if ($tempId !== 0) {
                        $codeItem[] = $tempId;
                        $result[] = true;
                    } else {
                        $result[] = false;
                    }
                } elseif ($value['id'] !== null && $value['isDeleted'] === false) {
                    if (Posts::editCodeById($value)) {
                        $codeItem[] = $value['id'];
                        $result[] = true;
                    } else {
                        $result[] = false;
                    }
                } elseif ($value['id'] !== null && $value['isDeleted'] === true) {
                    $result[] = Posts::deleteCodeById($value['id']);
                }
            }
            $post['codeBlock'] = implode(', ', $codeItem);
            $post['description'] = $formData['description'];
            $post['post_name_id'] = $id;
            $result[] = Posts::editPostById($post);
            $isOk = array_search(false, $result);
            if ($isOk === false) {
                return $this->response('Data updated.', 200);
            }
        } else {
            return $this->response("Update error", 500);
        }
    }

}
