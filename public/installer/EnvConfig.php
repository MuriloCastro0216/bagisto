<?php

$errors  = array();      // array to hold validation errors
$data    = array();      // array to pass back data

// validate the variables ======================================================
    // if any of these variables don't exist, add an error to our $errors array

    if (empty($_POST['app_url']))
        $errors['app_url'] = 'App Url is required.';

    if (empty($_POST['app_name']))
        $errors['app_name'] = 'App Name is required.';

    if (empty($_POST['host_name']))
        $errors['host_name'] = 'Host Name is required.';

    if (empty($_POST['database_name']))
        $errors['database_name'] = 'Database Name is required.';

    if (empty($_POST['user_name']))
        $errors['user_name'] = 'User Name is required.';

    if (empty($_POST['user_password']))
        $errors['user_password'] = 'User Password is required.';

    if (empty($_POST['port_name']))
        $errors['port_name'] = 'Port Name is required.';

    if (preg_match('/\s/', $_POST['app_url']))
        $errors['app_url_space'] = 'There should be no space in App URL ';

    if (preg_match('/\s/', $_POST['app_name']))
        $errors['app_name_space'] = 'There should be no space in App Name.';

    if (preg_match('/\s/', $_POST['host_name']))
        $errors['host_name_space'] = 'There should be no space in Host Name.';

    if (preg_match('/\s/', $_POST['database_name']))
        $errors['database_name_space'] = 'There should be no space in Database Name.';

    if (preg_match('/\s/', $_POST['user_name']))
        $errors['user_name_space'] = 'There should be no space in User Name.';

    if (preg_match('/\s/', $_POST['user_password']))
        $errors['user_password_space'] = 'There should be no space in User Password.';

    if (preg_match('/\s/', $_POST['port_name']))
        $errors['port_name_space'] = 'There should be no space in Port Name.';
// return a response ===========================================================

    // if there are any errors in our errors array, return a success boolean of false
    if ( ! empty($errors)) {

        // if there are items in our errors array, return those errors
        $data['success'] = false;
        $data['errors']  = $errors;

    } else {

        // if there are no errors process our form, then return a message

        // getting env file location
        $currentLocation = explode("/", getcwd());
        array_pop($currentLocation);
        array_pop($currentLocation);
        $desiredLocation = implode("/", $currentLocation);
        $envFile = $desiredLocation . '/' . '.env';

        $envExampleFile = $desiredLocation . '/' . '.env.example';

        if (!file_exists($envFile)) {
            if (file_exists($envExampleFile)) {
                copy($envExampleFile, $envFile);
            } else {
                touch($envFile);
            }
        }

        // reading env content
        $str= file_get_contents($envFile);

        // converting env content to key/value pair
        $data = explode(PHP_EOL,$str);
        $keyValueData = [];

        if ($data) {
            foreach ($data as $line) {
                $rowValues = explode('=', $line);
                if (count($rowValues) === 2) {
                    $keyValueData[$rowValues[0]] = $rowValues[1];
                } else if (count($rowValues) > 2) {
                    $eqPos = strpos($line, '=');
                    $keyValueData[substr($line, 0, $eqPos)] = substr($line,$eqPos+1);
                }
            }
        }

        // inserting form data to empty array
        $keyValueData['DB_HOST'] = $_POST["host_name"];
        $keyValueData['DB_DATABASE'] = $_POST["database_name"];
        $keyValueData['DB_USERNAME'] = $_POST["user_name"];
        $keyValueData['DB_PASSWORD'] = $_POST["user_password"];
        $keyValueData['APP_NAME'] = $_POST["app_name"];
        $keyValueData['APP_URL'] = $_POST["app_url"];
        $keyValueData['DB_CONNECTION'] = $_POST["database_connection"];
        $keyValueData['DB_PORT'] = $_POST["port_name"];

        // making key/value pair with form-data for env
        $changedData = [];
        foreach ($keyValueData as $key => $value) {
            $changedData[] = $key . '=' . $value;
        }

        // inserting new form-data to env
        $changedData = implode(PHP_EOL, $changedData);
        file_put_contents($envFile, $changedData);

        // checking database connection(mysql only)
        if ($_POST["database_connection"] == 'mysql') {
            // Create connection
            $conn = new mysqli($_POST["host_name"], $_POST["user_name"], $_POST["user_password"], $_POST["database_name"]);

            // check connection
            if ($conn->connect_error) {
                $errors['database_error'] = $conn->connect_error;
                $data['errors'] = $errors;
                $data['success'] = false;
            } else {
                $data['success'] = true;
                $data['message'] = 'Success!';
            }
        } else {
            $data['success'] = true;
            $data['message'] = 'Success!';
        }

        // show a message of success and provide a true success variable

    }

    // return all our data to an AJAX call
    echo json_encode($data);