<?php

class PlanController
{
    public static function index($slug)
    {
        $db = $_SERVER['PHP_AUTH_APP']['database'];

        $plan = $db->findOneBy('plans', [
            'slug' => $slug,
        ]);

        if (isset($_SESSION['PHP_AUTH_USER'])) {
            $breadcrumb['user'] = $_SESSION['PHP_AUTH_USER']->getUsername();

            if (!is_null($plan) && isset($plan) && $plan) {
                $breadcrumb['plan'] = $plan['name'];
                return Template::view('board.ptml', [
                    'title' => 'Plan Viewer',
                    'user' => isset($_SESSION['PHP_AUTH_USER'])
                        ? $_SESSION['PHP_AUTH_USER']
                        : null,
                    'breadcrumb' => json_decode(json_encode($breadcrumb)),
                    'plan' => json_decode(json_encode($plan)),
                ]);
            } else {
                return '<script>
                </script>
                ';
            }
        }
        return '<script>
        alert("You are not authorised to access plans");
        history.back();
        </script>';
    }

    public static function new()
    {
        $db = $_SERVER['PHP_AUTH_APP']['database'];

        $user_id = $_SESSION['PHP_AUTH_USER']->getId();
        $name = $_POST['name'];

        $plan = $db->findOneBy('plans', [
            'author' => $user_id,
            'name' => $name,
        ]);

        if (!is_null($plan) && isset($plan) && $plan) {
            return json_encode([
                'msg' => 'Another board with same name present!',
                'status' => '200',
            ]);
        }
        $slug = generateString();
        $users = implode(', ', [$user_id]);
        if (
            $db->insert('plans', [
                'author' => $user_id,
                'name' => $name,
                'slug' => $slug,
                'users' => $users,
            ])
        ) {
            return json_encode([
                'msg' => 'New board added successfully!',
                'status' => '200',
            ]);
        }
        return json_encode([
            'status' => '500',
            'error' => 'Internal Server',
        ]);
    }

    public static function edit()
    {
    }

    public static function update()
    {
    }

    public static function loadXML()
    {
        $slug = $_POST['slug'];

        $file_path = PROJECT_ROOT . "/public/data/{$slug}.xml";
        if (file_exists($file_path)) {
            $data = file_get_contents($file_path);
            if (!is_null($data) && isset($data) && $data) {
                return json_encode([
                    'data' => $data,
                    'status' => '200',
                ]);
            }
        }
        return json_encode([
            'msg' => 'No data found',
            'status' => '200',
        ]);
    }

    public static function saveXML()
    {
        $data = $_POST['data'];
        $slug = $_POST['slug'];
        try {
            $file_path = PROJECT_ROOT . "/public/data/{$slug}.xml";

            file_put_contents($file_path, $data);

            return json_encode([
                'data' => $data,
                'status' => '200',
            ]);
        } catch (Exception $e) {
            return json_encode([
                'status' => '500',
                'error' => $e->getMessage(),
            ]);
        }
    }
}

?>
