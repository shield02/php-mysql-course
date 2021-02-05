<?php

function admin_url($script_path) 
{
    if ($script_path[0] != '/') {
        $script_path = '/' . $script_path;
    }

    return ADMIN_ROOT . $script_path;
}

function app_url($script_path) 
{
    if ($script_path[0] != '/') {
        $script_path = '/' . $script_path;
    }

    return APP_ROOT . $script_path;
}

function redirect_to($string) 
{
    return header('Location: ' . $string);
}

function is_post_request() 
{
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_get_request()
{
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function display_errors($errors=array())
{
    $output = '';
    if (!empty($errors)) {
        $output .= "<div class=\"errors\">";
        $output .= "Please fix the following errors:";
        $output .= "<ul>";
        foreach($errors as $error) {
            $output .= "<li>" . htmlspecialchars($error) . "</li>";
        }
        $output .= "</ul>";
        $output .= "</div>";
    }
    return $output;
}



