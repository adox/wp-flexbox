<?php
/*
Plugin Name: Flex Box Page Builder
Description: 
Author: ITE
Text Domain: flexbox
Domain Path: /languages/
Version: 1.0
*/

function flexbox_filter($content)
{
    $flexbox_content = '';

    // check if the flexible content field has rows of data
    if( have_rows('flexbox') ):

        // loop through the rows of data
        while ( have_rows('flexbox') ) : the_row();

            $ModelName = get_row_layout();
            $ModelPath = get_template_directory() . "/flexbox/" . $ModelName . ".php";

            $ViewName = get_row_layout() . "_view.php";
            $ViewPath = get_template_directory() . "/flexbox/" . $ViewName;

            if(file_exists($ModelPath))
                include_once($ModelPath);

            $data = array();

            if(function_exists($ModelName))
                $data = $ModelName();
                

            if(file_exists($ViewPath))
                $flexbox_content .= flexbox_render($ViewPath, $data);
            else
                echo "View not found " . $ViewPath;


        endwhile;

    endif;

    return $content . $flexbox_content;
}
add_filter('the_content', 'flexbox_filter');



function flexbox_render($template_part, $data){
    if($data)
        extract($data);

    ob_start();
    require( $template_part );
    $result = ob_get_contents();
    ob_end_clean();

    return $result;
}

