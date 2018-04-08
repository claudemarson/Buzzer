<!DOCTYPE html>
<html lang="<?= LANG ?>">
    <head>
        <meta charset="utf-8"/>
        <title><?= LANG_SCREEN ?></title>
        <style>
            div {
                background-image: linear-gradient(to bottom, #3b679e 0%, #2b88d9 50%, #207cca 51%, #7db9e8 100%);
                background-color: blue;
                color: white;
                margin-top: 0.5em;
                margin-bottom: 0.5em;
                padding-left: 0.25em;
                padding-right: 0.25em;
                font-family: "Impact";
                font-size: 400%;
                letter-spacing: 4px;
                word-wrap: break-word;
                overflow-wrap: break-word;
            }
        </style>
        <style>
            .answer {
                display: none;
            }
        </style>
        <script defer="defer">
            "use strict";
            function init() {
                let es = new EventSource("stream.php");
                es.addEventListener("answers", function(e) {
                    let answers = JSON.parse(e.data);
                    for (let i=0; i<answers.length; i++) {
                        display(answers[i].teamName, answers[i].answer);
                    }
                });
                es.addEventListener("clear", function() {
                    let divs = document.querySelectorAll("body > div");
                    for (let i=0; i<divs.length; i++) {
                        divs[i].remove();
                    }
                });
                es.addEventListener("reveal", function() {
                    reveal(true);
                });
                es.addEventListener("hide", function() {
                    reveal(false);
                });
            }
            function display(teamname, answer) {
                let newDiv = document.createElement("div");
                newDiv.innerText = teamname;
                let newSpan = document.createElement("span");
                newSpan.innerText = ": " + answer;
                newSpan.className = "answer";
                newDiv.appendChild(newSpan);
                document.body.appendChild(newDiv);
            }
            function reveal(reveal) {
                document.styleSheets[1].disabled = reveal;
            }
            init();
        </script>
    </head>
    <body></body>
</html>
