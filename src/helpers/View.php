<?php
class View
{
    private $template_file, $_template;
    private $vars = [];

    public function __construct($file)
    {
        $this->template_file = $file;
    }

    public function __set($key, $val)
    {
        $this->vars[$key] = $val;
    }

    public function __get($key)
    {
        return isset($this->vars[$key]) ? $this->vars[$key] : null;
    }

    private function _load_template()
    {
        // Check for a custom template
        $template_file = project_include('views/' . $this->template_file);
        if (file_exists($template_file) && is_readable($template_file)) {
            $path = $template_file;
        }
        // Look for a system template
        elseif (
            file_exists(
                $default_file = project_include('views/default.html')
            ) &&
            is_readable($default_file)
        ) {
            $path = $default_file;
        }
        // If the default template is missing, throw an error
        else {
            throw new Exception('No default template found');
        }

        // Load the contents of the file and return them
        $this->_template = file_get_contents($path);
    }

    private function _parse_template()
    {
        $template = $this->_template;

        // Remove any PHP-style comments from the template
        $comment_pattern = ['#/\*.*?\*/#s', '#(?<!:)//.*#'];
        $template = preg_replace($comment_pattern, null, $template);

        // Define a regex to match any template tag
        $tag_pattern = '/??(\w+)??/';

        // Curry the function that will replace the tags with entry data
        $callback = lambda(function ($entry, $matches) {
            // Unserialize the object
            $entry = unserialize($entry);
            // Make sure the template tag has a matching array element
            if (property_exists($entry, $matches[1])) {
                // Grab the value from the Entry object
                return $entry->{$matches[1]};
            }

            // Otherwise, simply return the tag as is
            else {
                return '??' . $matches[1] . '??';
            }
        });

        foreach ($this->vars as $key => $value) {
            $template = str_replace(
                '??' . $key . '??',
                '<?php echo htmlentities($value) ?>',
                $template
            );
        }

        foreach ($this->vars as $key => $value) {
            $template = str_replace('!!' . $key . '!!', $value, $template);
        }

        return $template;
    }

    public function render()
    {
        $this->_load_template();

        // //start output buffering (so we can return the content)
        // ob_start();
        // //bring all variables into "local" variables using "variable variable names"
        // foreach ($this->vars as $k => $v) {
        //     $$k = $v;
        // }

        // //include view
        // include $this->file;

        // $str = ob_get_contents(); //get the entire view.
        // ob_end_clean(); //stop output buffering

        return $this->_parse_template();
    }
}
?>
