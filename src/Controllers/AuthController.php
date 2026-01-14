<?php

namespace SellNow\Controllers;

use App\Support\Csrf;
use App\Application\Auth\LoginService;

class AuthController
{

    // Imperfect: Manual dependency injection via constructor every time
    private $twig;
    private $db;

    public function __construct($twig, $db)
    {
        $this->twig = $twig;
        $this->db = $db;
    }

    public function loginForm()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: /dashboard");
            exit;
        }
        echo $this->twig->render('auth/login.html.twig');
    }

    public function login()
    {
        if (empty($_POST['email']) || empty($_POST['password'])) {
            die("Fill all fields");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify($_POST['_csrf'] ?? null);
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Raw SQL, no Model
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            die('Invalid credentials');
        } else {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: /dashboard");
            exit;
        }
    }

    public function registerForm()
    {
        echo $this->twig->render('auth/register.html.twig');
    }

    public function register()
    {
        if (
            empty($_POST['email']) ||
            empty($_POST['password']) ||
            empty($_POST['username']) ||
            empty($_POST['fullname'])
        ) {
            die("Fill all fields");
        }

        // ✅ HASH PASSWORD
        $hashedPassword = password_hash(
            $_POST['password'],
            PASSWORD_DEFAULT
        );

        if ($hashedPassword === false) {
            die("Failed to hash password");
        }

        $sql = "INSERT INTO users (email, username, Full_Name, password)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);

        try {
            $stmt->execute([
                $_POST['email'],
                $_POST['username'],
                $_POST['fullname'],
                $hashedPassword
            ]);
        } catch (\Exception $e) {
            die("Error registering: " . $e->getMessage());
        }

        header("Location: /login?msg=Registered successfully");
        exit;
    }

    public function dashboard()
    {
        if (!isset($_SESSION['user_id']))
            header("Location: /login");

        echo $this->twig->render('dashboard.html.twig', [
            'username' => $_SESSION['username']
        ]);
    }
}
