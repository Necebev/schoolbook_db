<?php
include_once 'config.php';
include_once 'html.php';

session_start();
echo "<p style='color: gray;'>".session_id()."</p>";


function execsql($sql){
    $mysqli = getConn();
}

function getConn($dbname = DB_NAME){
    try{
        $mysqli = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, $dbname);
        if (!$mysqli){
            throw new Exception("Kapcsolódási hiba az adatbázishoz: " . mysqli_connect_error());
        }
        return $mysqli;
    }
    catch (Exception $e){
        // displayMessage($e->getMessage(), 'error');
        error_log($e->getMessage());
        return null;
    }
}

function CreateDB($dbname = DB_NAME){
    echo 'szia';
    $database = getConn("mysql");
    $database->query("  CREATE DATABASE IF NOT EXISTS $dbname 
                        CHARACTER SET utf8 COLLATE utf8_hungarian_ci;");
    $database->close();

}


function createTable($dbname, $table, $fields){
    $query = "CREATE TABLE IF NOT EXISTS $dbname.$table(
        $fields
        )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;";
    $database = getConn("mysql");
    $database->query($query);
    $database->close();
}

function createStudentsTable(){
    $fields = "
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(255) NOT NULL,
    class_id INT NOT NULL
    ";
    createTable(DB_NAME, 'students', $fields);
}
function createSubjectsTable(){
    $fields = "
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(255) NOT NULL
    ";
    createTable(DB_NAME, 'subjects', $fields);
}
function createClassesTable(){
    $fields = "
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(255) NOT NULL,
    year INT NOT NULL
    ";
    createTable(DB_NAME, 'classes', $fields);
}
function createMarksTable(){
    $fields = "
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    mark INT NOT NULL,
    date varchar(255) NOT NULL
    ";
    createTable(DB_NAME, 'marks', $fields);
}

function InsertValues($table, $fields, $values){
    $database = getConn("school");
    $database->query("INSERT INTO $table($fields) VALUES($values)");
    $database->close();
}

function generateValues(){
    $date = date("Y-m-d_His");

    // subjects
    foreach(SUBJECTS as $subject){
        InsertValues("subjects", "name", "'$subject'");
    }

    // classes
    foreach(CLASSES as $class) {
        $className = str_split($class,2);
        InsertValues("classes", "name, year", "'$className[1]',$className[0]");
    }   

    // students + marks
    $class_id = 0;
    foreach(CLASSES as $class) {
        $class_id++;
        $studentCount = rand(10, 15);
        $student_id = 0;
        for ($i = 0; $i < $studentCount; $i++) {
            $student_id++;
            $lastName = NAMES['lastnames'][rand(0, count(NAMES['lastnames'])-1)];
            $gender = rand(1,2) == 1 ? "men" : "women";
            $firstName = NAMES['firstnames'][$gender][rand(0, count(NAMES['firstnames'])-1)];
            InsertValues("students", "name, class_id", "'$lastName $firstName', $class_id");
            $gradesCount = rand(3, 5);
            for ($j = 0; $j < count(SUBJECTS); $j++) {
                for ($k = 0; $k < $gradesCount; $k++)
                    $subject_id = $j;
                    $mark = rand(1,5);
                    $date = date("Y-m-d-his");
                    InsertValues("marks", "student_id, subject_id, mark, date", "$student_id, $subject_id, $mark, '$date'");
            }
        }
    }
}













