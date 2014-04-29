<?php
/**
 * User controller
 * @package session
 */
class userController {

	function checkForDuplicates($username){
		global $con, $error_handler;
		$prep_stmt = "SELECT id FROM gb_users WHERE username = ? LIMIT 1";
		$stmt = $con->prepare($prep_stmt);
	 
		if ($stmt) {
			$stmt->bind_param('s', $username);
			$stmt->execute();
			$stmt->store_result();
	 
			if ($stmt->num_rows == 1) {
				return true;
			}
		} else {
			$err_handler->logError('User creation', 'Database error', true);
		}
		return false;
	}

	/**
	 * Register a new user to the system using a form
	 * Ideally to be used with the prepared route named 'signup' by default
	 * @return [type] [description]
	 */
	function registerUser(){
		global $con, $error_handler;
		$username = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
		
		if($this::checkForDuplicates($username)) {
			$error_handler->logError('User creation', 'There is a user with the same name already!', true);
			return false;
		}

		$permissions = filter_input(INPUT_POST, 'permissions', FILTER_SANITIZE_STRING);
		$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);

		if (strlen($password) != 128) {
			// The hashed pwd should be 128 characters long.
			// If it's not, something really odd has happened
			$err_handler->logError('User creation', 'Password is invalid or a threat', true);
			return false;
		}

		// Create a random salt
        $random_salt = hash('sha512', uniqid(openssl_random_pseudo_bytes(16), TRUE));
 
        // Create salted password 
        $password = hash('sha512', $password . $random_salt);
 
        // Insert the new user into the database 
        if ($insert_stmt = $con->prepare("INSERT INTO gb_users (username, password, salt, permissions) VALUES (?, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssss', $username, $password, $random_salt, $permissions);
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                $error_handler->logError('Insert error', 'There was a problem executing the query', true);
            }
            $error_handler->logError('Success', 'Successfully created new user', true);
            return true;
        }

	}

	


}