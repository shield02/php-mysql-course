<?php

    // Queries for subject model

    function find_all_subjects() 
    {
        global $db_conn;

        $sql = "SELECT * FROM subjects ";
        $sql .= "ORDER BY position ASC";

        $sth = $db_conn->prepare($sql);
        $sth->execute();

        $result = $sth->fetchAll();

        confirm_result_set($sql, $sth, $result);

        $sth->closeCursor();
        // $sth = null;

        return $result;
    }

    function count_all_subjects()
    {
        global $db_conn;

        $sql = "SELECT * FROM subjects";

        $sth = $db_conn->prepare($sql);
        $sth->execute();

        $subject_count = $sth->rowCount();

        confirm_result_set($sql, $sth, $subject_count);

        $sth->closeCursor();

        return $subject_count;
    }

    function find_subject_by_id($id)
    {
        global $db_conn;
        
        $sql = "SELECT * FROM subjects ";
        $sql .= "WHERE id=:id ";

        $sth = $db_conn->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);

        $sth->execute();

        $result = $sth->fetch();

        confirm_result_set($sql, $sth, $result);

        $sth->closeCursor();

        return $result;
    }

    function validate_subject($subject) 
    {
        $errors = [];

        // name
        if (is_blank($subject['name'])) {
            $errors[] = "Name cannot be blank.";
        } elseif (!has_length($subject['name'], ['min' => 2, 'max' => 255])) {
            $errors[] = "Name must be between 2 and 255 characters.";
        }

        // position 
        // Make sure we are working with an integer
        $position_int = (int) $subject['position'];
        if ($position_int <= 0) {
            $errors[] = "Position must be greater than zero.";
        }
        if ($position_int > 999) {
            $errors[] = "Position must be less than 999.";
        }

        // visible
        // Make sure we are working with a string
        $visible_str = (string) $subject['visible'];
        if (!has_inclusion_of($visible_str, ["0","1"])) {
            $errors[] = "Visible must be true or false";
        }

        return $errors;
    }

    function insert_subject($subject)
    {
        global $db_conn;
        
        $errors = validate_subject($subject);
        if (!empty($errors)) {
            return $errors;
        }

        $sql = "INSERT INTO subjects ";
        $sql .= "(name, position, visible) ";
        $sql .= "VALUES (?, ?, ?)";
        
        $db_conn->beginTransaction();

        try {
            $sth = $db_conn->prepare($sql);
            $result = $sth->execute(
                array(
                    $subject['name'], 
                    $subject['position'], 
                    $subject['visible']
                )
            );

            confirm_result_set($sql, $sth, $result);

            $new_id = $db_conn->lastInsertId();

            $db_conn->commit();

            $sth->closeCursor();
        } catch (PDOException $e) {
            $db_conn->rollback();
            die("Error: " . $e->getMessage()) . "<br/>";
        }

        return $new_id;
    }

    function update_subject($subject) 
    {
        global $db_conn;

        $errors = validate_subject($subject);
        if (!empty($errors)) {
            return $errors;
        }

        $sql = "UPDATE subjects SET ";
        $sql .= "name=:name, position=:position, visible=:visible ";
        $sql .= "WHERE id=:id ";
        $sql .= "LIMIT 1";

        $db_conn->beginTransaction();

        try {
            $sth = $db_conn->prepare($sql);
            $sth->bindParam(':id', $subject['id'], PDO::PARAM_INT);
            $sth->bindParam(':name', $subject['name'], PDO::PARAM_STR);
            $sth->bindParam(':position', $subject['position'], PDO::PARAM_INT);
            $sth->bindParam(':visible', $subject['visible'], PDO::PARAM_INT);

            $result = $sth->execute();
            
            $db_conn->commit();

            $sth->closeCursor();
        } catch (PDOException $e) {
            $db_conn->rollBack();
            die("Error: " . $e->getMessage()) . "<br/>";
        }

        return $result;
    }

    function delete_subject($id) 
    {
        global $db_conn;

        $sql = "DELETE FROM subjects ";
        $sql .= "WHERE id=:id ";
        $sql .= "LIMIT 1";
    
        $db_conn->beginTransaction();

        try {
            $sth = $db_conn->prepare($sql);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);

            $result = $sth->execute();
            $db_conn->commit();

            $sth->closeCursor();
        } catch (PDOException $e) {
            $sth->rollBack();
            die("Error: " . $e->getMessage()) . "<br/>";
        }

        return $result;
    }


    // Queries for pages model
    function find_all_pages() 
    {
        global $db_conn;

        $sql = "SELECT * FROM pages ";
        $sql .= "ORDER BY subject_id ASC, position ASC";

        $sth = $db_conn->prepare($sql);
        $sth->execute();

        $result = $sth->fetchAll();

        confirm_result_set($sql, $sth, $result);

        $sth->closeCursor();
        // $sth = null;

        return $result;
    }

    function count_all_pages()
    {
        global $db_conn;

        $sql = "SELECT * FROM pages";

        $sth = $db_conn->prepare($sql);
        $sth->execute();

        $page_count = $sth->rowCount();

        confirm_result_set($sql, $sth, $page_count);

        $sth->closeCursor();

        return $page_count;
    }

    function find_page_by_id($id) 
    {
        global $db_conn;
        
        $sql = "SELECT * FROM pages ";
        $sql .= "WHERE id=:id";

        $sth = $db_conn->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);

        $sth->execute();

        $result = $sth->fetch();

        confirm_result_set($sql, $sth, $result);

        $sth->closeCursor();

        return $result;
    }

    function validate_page($page) 
    {
        $errors = [];

        // subject_id
        if (is_blank($page['subject_id'])) {
            $errors[] = "Subject cannot be blank.";
        }

        // name
        if (is_blank($page['name'])) {
            $errors[] = "Name cannot be blank.";
        } elseif (!has_length($page['name'], ['min' => 2, 'max' => 255])) {
            $errors[] = "Name must be between 2 and 255 characters.";
        }
        
        // $current_id = $page['id'] ?? '0';
        // if (!has_unique_page_name($page['name'], $current_id)) {
        //     $errors[] = "Name must be unique.";
        // }

        // position 
        // Make sure we are working with an integer
        $position_int = (int) $page['position'];
        if ($position_int <= 0) {
            $errors[] = "Position must be greater than zero.";
        }
        if ($position_int > 999) {
            $errors[] = "Position must be less than 999.";
        }

        // visible
        // Make sure we are working with a string
        $visible_str = (string) $page['visible'];
        if (!has_inclusion_of($visible_str, ["0","1"])) {
            $errors[] = "Visible must be true or false";
        }

        // content
        if (is_blank($page['content'])) {
            $errors[] = "Content cannot be blank.";
        }

        return $errors;
    }

    function insert_page($page) 
    {
        global $db_conn;

        $errors = validate_page($page);
        if (!empty($errors)) {
            return $errors;
        }

        $sql = "INSERT INTO pages ";
        $sql .= "(subject_id, name, position, visible, content) ";
        $sql .= "VALUES (:subject_id, :name, :position, :visible, :content)";
        
        $db_conn->beginTransaction();
        
        try {
            $sth = $db_conn->prepare($sql);
            $sth->bindParam(':subject_id', $page['subject_id'], PDO::PARAM_INT);
            $sth->bindParam(':name', $page['name'], PDO::PARAM_STR);
            $sth->bindParam(':position', $page['position'], PDO::PARAM_INT);
            $sth->bindParam(':visible', $page['visible'], PDO::PARAM_INT);
            $sth->bindParam(':content', $page['content'], PDO::PARAM_STR);

            $result = $sth->execute();
            confirm_result_set($sql, $sth, $result);

            $new_id = $db_conn->lastInsertId();
            $db_conn->commit();
            
            $sth->closeCursor();
        } catch (PDOException $e) {
            $db_conn->rollBack();
            die("Error: " . $e->getMessage());
        }

        return $new_id;

    }

    function update_page($page) 
    {
        global $db_conn;

        $errors = validate_page($page);
        if (!empty($errors)) {
            return $errors;
        }

        $sql = "UPDATE pages SET ";
        $sql .= "subject_id=:subject_id, name=:name, position=:position, visible=:visible, content=:content ";
        $sql .= "WHERE id=:id ";
        $sql .= "LIMIT 1";

        $db_conn->beginTransaction();

        try {
            $sth = $db_conn->prepare($sql);
            $sth->bindParam(':id', $page['id'], PDO::PARAM_INT);
            $sth->bindParam(':subject_id', $page['subject_id'], PDO::PARAM_INT);
            $sth->bindParam(':name', $page['name'], PDO::PARAM_STR);
            $sth->bindParam(':position', $page['position'], PDO::PARAM_INT);
            $sth->bindParam(':visible', $page['visible'], PDO::PARAM_INT);
            $sth->bindParam(':content', $page['content'], PDO::PARAM_STR);
            
            $result = $sth->execute();
            $db_conn->commit();

            $sth->closeCursor();
        } catch (PDOException $e) {
            $db_conn->rollBack();
            die("Error: " . $e->getMessage()) . "<br/>";
        }

        return $result;

    }

    function delete_page($id) 
    {
        global $db_conn;

        $sql = "DELETE FROM pages ";
        $sql .= "WHERE id=:id ";
        $sql .= "LIMIT 1";

        $db_conn->beginTransaction();

        try {
            $sth =$db_conn->prepare($sql);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);

            $result = $sth->execute();
            $db_conn->commit();

            $sth->closeCursor();
        } catch (PDOException $e) {
            $db_conn->rollBack();
            die("Error: " . $e->getMessage()) . "<br />";
        }

        return $result;
    }


    // new category
    function find_pages_by_subject_id($subject_id) 
    {
        global $db_conn;
        
        $sql = "SELECT * FROM pages ";
        $sql .= "WHERE subject_id=:id ";
        $sql .= "ORDER BY position ASC";

        $sth = $db_conn->prepare($sql);
        $sth->bindParam(':id', $subject_id, PDO::PARAM_INT);

        $sth->execute();

        $result = $sth->fetchAll();

        confirm_result_set($sql, $sth, $result);

        $sth->closeCursor();

        return $result;

    }

