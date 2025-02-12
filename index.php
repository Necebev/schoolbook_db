<?php
include_once "config.php";
include_once "php-sql.php";
include_once "html.php";

head();
displayBodyStart();
displayNav();
displayBodyEnd();

if(isset($_POST["create"])){
    CreateDB();
    createStudentsTable();
    createSubjectsTable();
    createMarksTable();
    createClassesTable();
    generateValues();
}