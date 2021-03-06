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

$meta_title = "Quizz ". $quizz->getTitle();

// get quizz questions
$questions = $quizz->getQuestions();

// shuffle questions if user already answered this quizz
if (AccountAnswer::getNbAccountAnswersByQuizzAccount($quizz->getId(), $account->getId()) > 1) {
    shuffle($questions);
    $already_answered = true;
}
$i = 0;


// view quizz questions page
require_once('../php/views/quizz-questions.php');
