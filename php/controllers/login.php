<?php

$meta_title = "Login";

// redirect to account if user is connected
if (isset($_SESSION['user_id'])) {
    header('Location: /account');
    die();
}

// gérer la connexion
if (isset($_POST['form-login'])) {
    
    // vérifier les champs
    if (isset($_POST['username'], $_POST['password']) && !empty($_POST['username']) && !empty($_POST['username'])) {
        
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);
        
        $bdd_account = Account::getAccountByUsername($username);

        // vérifier si le compte existe
        if ($bdd_account) {
            // vérifier le mot de passe
            if ($bdd_account->verifyPassword($password)) {
                // création de la session de connexion
                $_SESSION['user_id'] = $bdd_account->getId();

                // mettre a jour la date de connexion
                $bdd_account->updateLastConnexion();

                // handle redirection
                if (isset($_GET['next'])) {
                    $next = htmlspecialchars($_GET['next']);
                } else {
                    $next = 'account';
                }
                header('Location: /' . $next);
                die();
            } else {
                $error = "Username or password incorrect";
            }
        } else {
            $error = "Username or password incorrect";
        }
    }
    else {
        $error = "Fields missing";
    }
    
}


require_once('../php/views/login.php');
