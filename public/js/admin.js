"use strict";

/**
 * Request to reset the game and to clear the screen
 */
function reset() {
    fetch("ajax/resetscreen.php");
}

/**
 * Request to set the visibility of the answers on the screen
 * @param {MouseEvent} event
 */
function setAnswerVisibility(event) {
    fetch("ajax/revealanswers.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "reveal=" + +event.target.value
    }).then(function() {
        document.querySelector("#reveal_answers_form .active").classList
            .remove("active");
        event.target.classList.add("active");
    });
}

/**
 * Request to set the next buzzer type
 */
function changeBuzzerType() {
    let buzzerTypeInput = document.querySelector("[name='buzzertype']:checked");
    
    fetch("ajax/changebuzzertype.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "buzzertype=" + +buzzerTypeInput.value
    }).then(function() {
        document.querySelector("#buzzer_type_form .active").classList
            .remove("active");
        buzzerTypeInput.parentNode.classList.add("active");
    });
}

/**
 * Initialise the event listeners
 */
function init() {
    document.getElementById("reset_screen").addEventListener("click", reset);
    document.getElementById("hide_answers").addEventListener("click",
        setAnswerVisibility);
    document.getElementById("reveal_answers").addEventListener("click",
        setAnswerVisibility);
    document.getElementById("change_buzzer_type").addEventListener("click",
        changeBuzzerType);
}

init();
