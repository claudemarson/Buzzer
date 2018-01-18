<?php

// Redirect to the actual web root in case the project is set up incorrectly
header('Location: /'.basename(__DIR__).'/public/');
