<?php

require_once('../php/models/quizz.php');

$meta_title = "Quizz";

$quizzes = Quizz::getQuizzes();

// view page to choose a quizz
require_once('../php/views/quizz.php');
