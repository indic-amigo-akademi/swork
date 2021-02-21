<?php
// Error reporting is turned up to 11 for the purposes of this demo
ini_set('display_errors', 1);
ERROR_REPORTING(E_ALL);

// Exception handling
set_exception_handler('exception_handler');
function exception_handler($exception)
{
    echo $exception->getMessage();
}

// Load the Template class
include_once project_include('/src/tests/Template.php');

$template = new Template();

// Set the testing template file location
$template->template_file = 'test.ptml';

$template->entries[] = (object) [
    'test' => 'This was inserted using template tags!',
];

$extra = (object) [
    'header' => (object) ['header_stuff' => 'Some extra content.'],
    'footer' => (object) ['footerStuff' => 'More extra content.'],
];

// Output the template markup
// echo $template->generate_markup($extra);


function _curry($function, $num_args)
{
    return function () use ($function, $num_args) {
        // Store the passed arguments in an array
        $args = func_get_args();

        // Execute the function if the right number of arguments were passed
        if (count($args) >= $num_args) {
            echo "hello12";
            return call_user_func_array("$function", $args);
        }

        // Export the function arguments as executable PHP code
        $args = var_export($args, 1);

        // Return a new function with the arguments stored otherwise
        return function () use ($function, $args) {
            $a = func_get_args();
            $z = $args;
            $a = array_merge($z, $a);
            echo "hello32";

            return call_user_func_array($function, $a);
        };
    };
}

function add($a, $b) { return $a + $b; } 
  
$func = _curry("add", 2); 

$func2 = $func(4); // Stores 1 as the first argument of add() 
  
echo $func2(7); // Executes add() with 2 as the second arg and outputs 3 

/**
 * Loads entries from the Envato API for a given site
 * @link https://marketplace.envato.com/api/documentation
 * @param string $site The site from which entries should be loaded
 * @return array An array of objects containing entry data
 */

function load_envato_blog_posts($site = 'themeforest')
{
    // Set up the request for the Envato API
    $url =
        'http://marketplace.envato.com/api/edge/blog-posts:' . $site . '.json';

    // Initialize an empty array to store entries
    $entries = [];

    // Load the data from the API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $ch_data = curl_exec($ch);
    curl_close($ch);

    // If entries were returned, load them into the array
    if (!empty($ch_data)) {
        // Convert the JSON into an array of entry objects
        $json_data = json_decode($ch_data, true);
        foreach ($json_data['blog-posts'] as $entry) {
            $entries[] = (object) $entry;
        }

        return $entries;
    } else {
        die('Something went wrong with the API request!');
    }
}

// echo '<pre>', print_r(load_envato_blog_posts(), true), '</pre>';

?>
