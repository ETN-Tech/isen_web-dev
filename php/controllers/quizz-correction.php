<?php

// verify if quizz-name is set
if (!isset($_POST['quizz-name']) || empty($_POST['quizz-name'])) {
    header('Location: /quizz');
    die();
}

$quizz_name = htmlspecialchars($_POST['quizz-name']);

// verify if the quizz exists
if (!Quizz::quizzExistByName($quizz_name)) {
    header('Location: /quizz');
    die();
}


// get the quizz with the name
$quizz = Quizz::getQuizzByName($quizz_name);

// get quizz questions
$questions = $quizz->getQuestions();


// check all required questions are answered
foreach($questions as $question) {
    // if required field
    if (in_array($question->getType(), ['input', 'radio', 'select'])) {
        // test if user answered
        if (!isset($_POST[$question->getId()]) || empty($_POST[$question->getId()])) {
            array_push($quizz_error, $question->getId());
        }
    }
}

// initialize correction date
$date = date("Y-m-d H:i:s");


// save user's answers for each question
foreach($questions as $question) {

    // get all answers from bdd
    $answers = $question->getAnswers();

    // if question type is input
    if ($question->getType() == 'input') {
        // get user answer
        $user_answer = htmlspecialchars($_POST[$question->getId()]);

        // get first answer
        $answer = $answers[0];

        // verify user answer
        if (strtolower($answer->getAnswer()) == trim(strtolower($user_answer))) {
            $answer = new AccountAnswer(null, $account->getId(), $answer->getId(), $date);
            $answer->insertBdd();
        }
    }
    // if question type is checkbox
    else if ($question->getType() == 'checkbox') {

        // check each possible answer
        foreach ($answers as $answer) {
            $proposition_id = $question->getId() . '-' . $answer->getId();

            // check if user ticked this proposition
            if (isset($_POST[$proposition_id])) {
                $answer = new AccountAnswer(null, $account->getId(), $answer->getId(), $date);
                $answer->insertBdd();
            }
        }
    }
    // if question type is radio/select
    else if (in_array($question->getType(), ['radio', 'select'])) {
        // get user answer
        $user_answer = htmlspecialchars($_POST[$question->getId()]);

        foreach ($answers as $answer) {
            // check if it's user's answer
            if ($user_answer == $answer->getId()) {
                $answer = new AccountAnswer(null, $account->getId(), $answer->getId(), $date);
                $answer->insertBdd();
                break;
            }
        }
    }
}

// redirect to quizz-score
header('Location: /quizz/score/'. $date);

