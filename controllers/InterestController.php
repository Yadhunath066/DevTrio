<?php

class InterestController {

    public function store() {

        // Get data from form
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $programme_id = $_POST['programme_id'];

        // Check if empty
        if(empty($name) || empty($email)){
            echo "<h2>Error: All fields required</h2>";
            return;
        }

        // Success message
        echo "<h2>Interest Registered Successfully!</h2>";
        echo "<p><strong>Name:</strong> $name</p>";
        echo "<p><strong>Email:</strong> $email</p>";
        echo "<p><strong>Programme ID:</strong> $programme_id</p>";

        echo '<br><a href="index.php?url=home">Go Back Home</a>';
    }
}