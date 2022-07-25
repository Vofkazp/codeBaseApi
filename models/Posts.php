<?php

class Posts {

    public static function getNameById($id) {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM post_name WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetch();
    }

    public static function getPostById($id) {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM posts WHERE post_name_id = :post_name_id';
        $result = $db->prepare($sql);
        $result->bindParam(':post_name_id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetch();
    }

    public static function getCodeById($id) {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM code_block WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetch();
    }

    public static function createPost($item) {
        $db = Db::getConnection();
        $sql = 'INSERT INTO post_name (title, module_name, category_id) VALUES (:title, :module_name, :category_id)';
        $result = $db->prepare($sql);
        $result->bindParam(':title', $item['title'], PDO::PARAM_STR);
        $result->bindParam(':module_name', $item['module_name'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $item['category_id'], PDO::PARAM_INT);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }

    public static function createCodePost($item) {
        $db = Db::getConnection();
        $sql = 'INSERT INTO code_block (title, code) VALUES (:title, :code)';
        $result = $db->prepare($sql);
        $result->bindParam(':title', $item['title'], PDO::PARAM_STR);
        $result->bindParam(':code', $item['code'], PDO::PARAM_STR);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }

    public static function createPostName($item) {
        $db = Db::getConnection();
        $sql = 'INSERT INTO posts (description, codeBlock, post_name_id) VALUES (:description, :codeBlock, :post_name_id)';
        $result = $db->prepare($sql);
        $result->bindParam(':description', $item['description'], PDO::PARAM_STR);
        $result->bindParam(':codeBlock', $item['codeBlock'], PDO::PARAM_STR);
        $result->bindParam(':post_name_id', $item['post_name_id'], PDO::PARAM_INT);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }

    public static function deletePostNameById($id) {
        $db = Db::getConnection();
        $sql = 'DELETE FROM post_name WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    public static function deletePostsById($id) {
        $db = Db::getConnection();
        $sql = 'DELETE FROM posts WHERE post_name_id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    public static function deleteCodeById($id) {
        $db = Db::getConnection();
        $sql = 'DELETE FROM code_block WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    public static function editPostNameById($item) {
        $db = Db::getConnection();
        $sql = "UPDATE post_name SET title = :title, module_name = :module_name, category_id = :category_id WHERE id = :id";
        $result = $db->prepare($sql);
        $result->bindParam(':id', $item['id'], PDO::PARAM_INT);
        $result->bindParam(':title', $item['title'], PDO::PARAM_STR);
        $result->bindParam(':module_name', $item['module_name'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $item['category_id'], PDO::PARAM_INT);
        return $result->execute();
    }

    public static function editCodeById($item) {
        $db = Db::getConnection();
        $sql = "UPDATE code_block SET title = :title, code = :code WHERE id = :id";
        $result = $db->prepare($sql);
        $result->bindParam(':id', $item['id'], PDO::PARAM_INT);
        $result->bindParam(':title', $item['title'], PDO::PARAM_STR);
        $result->bindParam(':code', $item['code'], PDO::PARAM_STR);
        return $result->execute();
    }

    public static function editPostById($item) {
        $db = Db::getConnection();
        $sql = "UPDATE posts SET description = :description, codeBlock = :codeBlock WHERE post_name_id = :id";
        $result = $db->prepare($sql);
        $result->bindParam(':id', $item['post_name_id'], PDO::PARAM_INT);
        $result->bindParam(':description', $item['description'], PDO::PARAM_STR);
        $result->bindParam(':codeBlock', $item['codeBlock'], PDO::PARAM_STR);
        return $result->execute();
    }

    public static function getListByCategoryId($id) {
        $db = Db::getConnection();
        $result = $db->query("SELECT * FROM post_name WHERE category_id = $id ORDER BY id ASC");
        $list = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $list[$i]['id'] = $row['id'];
            $list[$i]['title'] = $row['title'];
            $list[$i]['moduleName'] = $row['module_name'];
            $i++;
        }
        return $list;
    }

}
