<?php
class User{
    public $name = "";
    public $level = "";
}

$users = array(
    "admin" => "admin",
    "user" => "iloveyou!",
    "john" => "soccer"
);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (array_key_exists($username, $users)) {
            if ($password === $users[$username]) {
                // Authentication successful
                $usr = new User();
                $usr->name = $username;
                $usr->level  = "Authenticated";
                $serialised = serialize($usr);
                $Token = base64_encode($serialised);

                setcookie("user_token", $Token, time() + 3600, "/");
                header("Location: ./views/dashboard.html");
                exit;
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "Invalid username!";
        }
    } else {
        echo "Please enter both username and password.";
    }
}
?>
