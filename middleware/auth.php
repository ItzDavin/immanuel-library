<?php
const LOGIN_URL = "/pages/auth/login.php";
const BOOK_INDEX_URL = "/pages/books/index.php";
const FORBIDEN_URL = "pages/errors/403.php";

//cek sesion mulai atau tidak
function sessionStart() 
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
// cek log in
function isLoggedIn()
{
  sessionStart();
  $isLoggedIn = isset($_SESSION['user']);

  return $isLoggedIn;
}

// mendapatkan data pengguna yang udh log in
function getAuthUser()
{
    sessionStart();
    $user = $_SESSION['user'] ?? null;

    return $user;
}

// cek role pengguna sesuai
function hasRole(array $roles)
{
    $user = getAuthUser();
    $isUserHasAuthorizeRole = $user != null && in_array($user['role'], $roles);

    return $isUserHasAuthorizeRole;
}

// Handel pengguna yang belum login
function requierAuth()
{
    if(!isLoggedIn()){
        $_SESSION['errors'] = [
            'login' => 'Login terlebih dahulu',
        ];

        header('Location: ' . LOGIN_URL);
        exit;
    } 
}
//Handel pengguna yang punyarole sesuai
function requireRole(array $roles)
{
    requierAuth();

    if (!hasRole($roles)){
        header('Loaction: '. FORBIDEN_URL);
        exit;
    }
    
}

// Handel pengguna yg udah login sebelumnya
function requireGuest()
{
    if(isLoggedIn()){
        header('Location: '. BOOK_INDEX_URL);
        exit;
    }
}
?>