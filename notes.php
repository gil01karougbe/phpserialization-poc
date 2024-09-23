<?php
class User{
    public $name = "";
    public $level = "";
    public $filename = "logs.txt";
    public $message = "Request From ";
    
    public function __destruct(){
        $currentDate = date('Y-m-d H:i:s');
        file_put_contents(__DIR__ . "/" . $this->filename, $this->message . $this->name . " " . $currentDate . ".\n", FILE_APPEND);
    }
}

$notes = array(
    array(
        "id" => 1,
        "title" => "First Note",
        "author" => "admin",
        "content" => "This is the content of the first note."
    ),
    array(
        "id" => 2,
        "title" => "Second Note",
        "author" => "user",
        "content" => "This is the content of the second note."
    ),
    array(
        "id" => 3,
        "title" => "Third Note",
        "author" => "admin",
        "content" => "This is the content of the second note."
    ),
    array(
        "id" => 4,
        "title" => "Fourth Note",
        "author" => "john",
        "content" => "This is the content of the third note."
    ),
    array(
        "id" => 5,
        "title" => "Fith Note",
        "author" => "admin",
        "content" => "This is the content of the second note."
    ),

);

if (isset($_COOKIE['user_token'])) {
    $token = $_COOKIE['user_token'];
    $decoded = base64_decode($token);
    $usr = unserialize($decoded);

    // Now you can access $usr->name and $usr->level
    //echo "Welcome back, " . $usr->name . "! Your level is: " . $usr->level;

    $userNotes = array();
    foreach ($notes as $note) {
        if ($note['author'] === $usr->name) {
            $userNotes[] = $note;
        }
    }
    $userNotesJSON = json_encode($userNotes);
    header('Content-Type: application/json');
    echo $userNotesJSON;

} else {
    echo "Token not found. User not authenticated.";
}
?>
