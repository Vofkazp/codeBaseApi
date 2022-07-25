<?php

class Category {

    public static function getCategoryList() {
        $db = Db::getConnection();
        $result = $db->query('SELECT * FROM category ORDER BY sort ASC');
        $list = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $list[$i]['id'] = $row['id'];
            $list[$i]['title'] = $row['title'];
            $list[$i]['moduleName'] = $row['module_name'];
            $list[$i]['sort'] = $row['sort'];
            $i++;
        }
        return $list;
    }

    public static function getCategoryListWithPostName() {
        $db = Db::getConnection();
        $result = $db->query('SELECT * FROM category ORDER BY sort ASC');
        $list = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $list[$i]['id'] = $row['id'];
            $list[$i]['title'] = $row['title'];
            $list[$i]['moduleName'] = $row['module_name'];
            $list[$i]['sort'] = $row['sort'];
            $list[$i]['submenu'] = Posts::getListByCategoryId($row['id']);
            $i++;
        }
        return $list;
    }

    public static function createCategory($item) {
        $db = Db::getConnection();
        $sql = 'INSERT INTO category (title, module_name, sort) VALUES (:title, :module_name, :sort)';
        $result = $db->prepare($sql);
        $result->bindParam(':title', $item['title'], PDO::PARAM_STR);
        $result->bindParam(':module_name', $item['module_name'], PDO::PARAM_STR);
        $result->bindParam(':sort', $item['sort'], PDO::PARAM_INT);
        if ($result->execute()) {
            return $db->lastInsertId();
        }
        return 0;
    }

    public static function deleteCategoryById($id) {
        $db = Db::getConnection();
        $sql = 'DELETE FROM category WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    public static function getById($id) {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM category WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        return $result->fetch();
    }

    public static function editCategory($item) {
        $db = Db::getConnection();
        $sql = "UPDATE category SET title = :title, module_name = :module_name WHERE id = :id";
        $result = $db->prepare($sql);
        $result->bindParam(':id', $item['id'], PDO::PARAM_INT);
        $result->bindParam(':title', $item['title'], PDO::PARAM_STR);
        $result->bindParam(':module_name', $item['module_name'], PDO::PARAM_STR);
        return $result->execute();
    }

    public static function sortCategory($item) {
        $db = Db::getConnection();
        $sql = "UPDATE category SET sort = :sort WHERE id = :id";
        $result = $db->prepare($sql);
        $result->bindParam(':id', $item['id'], PDO::PARAM_INT);
        $result->bindParam(':sort', $item['sort'], PDO::PARAM_INT);
        return $result->execute();
    }

    public static function countCategory() {
        $db = Db::getConnection();
        $result = $db->query('SELECT count(*) FROM category');
        $result = $result->fetch();
        return ++$result[0];
    }

}
