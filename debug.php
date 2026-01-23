<?php
// debug.php file
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Debug pagina</h1>";
echo "PHP werkt!<br>";
echo "PHP versie: " . phpversion() . "<br>";


class TestClass {
    public function hello() {
        return "Hello World!";
    }
}

$test = new TestClass();
echo $test->hello();
?>  