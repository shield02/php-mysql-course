<?php

// is_blank('abcd')
// * validate data presence
function is_blank($value) 
{
    return !isset($value) || trim($value) === '';
}

// has_presence('abcd')
// * validate data presence
function has_presence($value) 
{
    return !is_blank($value);
}

// has_length_greater_than('abcd', 3)
// * validate string length
function has_length_greater_than($value, $min) 
{
    $length = strlen($value);
    return $length > $min;
}

// has_length_less_than('abcd', 12)
// * validate string length
function has_length_less_than($value, $max) 
{
    $length = strlen($value);
    return $length < $max;
}

// has_length_exactly('abcd', 12)
// * validate string length
function has_length_exactly($value, $exact) 
{
    $length = strlen($value);
    return $length == $exact;
}

// has_length('abcd', ['min' => 2, 'max' => 12])
// * validate string length
function has_length($value, $options) 
{
    if (isset($options['min']) && !has_length_greater_than($value, $options['min'] - 1)) {
        return false;
    } elseif (isset($options['max']) && !has_length_less_than($value, $options['max'] + 1)) {
        return false;
    } elseif (isset($options['exact']) && !has_length_exactly($value, $options['exact'])) {
        return false;
    } else {
        return true;
    }
}

// has_inclusion_of(5, [1,2,3,4,4])
// * validate inclusion in a set
function has_inclusion_of($value, $set)
{
    return in_array($value, $set);
}

// has_exclusion_of(5, [1,2,3,4,4])
// * validate exclusion in a set
function has_exclusion_of($value, $set)
{
    return !in_array($value, $set);
}

// has_string('example@email.com', '.com')
// * validate inclusion of character(s)
function has_string($value, $required_string) 
{
    return strpos($value, $required_string) !== false;
}

// has_correct_email_format('example@email.com')
// * validate correct format for email addresses
function has_correct_email_format($value)
{
    $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\z/i';
    return preg_match($email_regex, $value) === 1;
}

// has_unique_page_name('History')
// * Validates uniqueness of pages.name
// * For mew record provide only the name.
// has_unique_page_name('History', 4)
function has_unique_page_name($name, $current_id="0") 
{
    global $db_conn;

    $sql = "SELECT * FROM pages ";
    $sql .= "WHERE id !=:current_id ";
    $sql .= "AND name=:name";

    $sth = $db_conn->prepare($sql);
    $sth->bindParam(':name', $name, PDO::PARAM_STR);
    $sth->bindParam(':current_id', $current_id, PDO::PARAM_INT);
    $sth->execute();

    $page_count = $sth->rowCount();

    confirm_result_set($sql, $sth, $page_count);

    $sth->closeCursor();

    return $page_count === 0;
}

// validate a page doesn't have any page before deleting


