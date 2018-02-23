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
 * List all teams
 */
function listTeams() {
    fetch("ajax/listteams.php").then(function(response) {
        response.text().then(function(tableRows) {
            let teamsTBody = document.getElementById("teams");
            teamsTBody.innerHTML = tableRows;
            document.getElementById("team_count").innerText = teamsTBody
                .childElementCount;
            teamsTBody.querySelectorAll(".remove-team")
                .forEach(function(button) {
                    button.addEventListener("click", removeTeam);
                });
        });
    });
}

/**
 * Add a team
 */
function addTeam() {
    fetch("ajax/addteam.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        }
    }).then(listTeams);
}

/**
 * Remove a team
 * @param {MouseEvent} event
 */
function removeTeam(event) {
    fetch("ajax/removeteam.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "teamid=" + +event.target.parentNode.parentNode.dataset.teamId
    }).then(listTeams);
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
    document.getElementById("reload_teams").addEventListener("click",
        listTeams);
    document.getElementById("add_team").addEventListener("click", addTeam);
    document.querySelectorAll(".remove-team").forEach(function(button) {
        button.addEventListener("click", removeTeam);
    });
}

init();
