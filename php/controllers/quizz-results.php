<?php

// verify if quizz page requested
if (!isset($_GET['quizz']) || empty($_GET['quizz'])) {
    header('Location: /quizz');
    die();
}

// secure quizz name
$quizz_name = htmlspecialchars($_GET['quizz']);

// verify if the quizz exists
if (!Quizz::quizzExistByName($quizz_name)) {
    header('Location: /quizz');
    die();
}

// get the quizz with the name
$quizz = Quizz::getQuizzByName($quizz_name);

$meta_title = "Results quizz ". $quizz->getTitle();

// get quizz questions
$results_date = AccountAnswer::getAccountAnswerDatesByQuizz($quizz->getId());


// view quizz questions page
require_once('../php/views/quizz-results.php');
