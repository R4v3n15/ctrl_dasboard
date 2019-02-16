<?php

/**
 * Class RegistrationModel
 */
class RegistrationModel
{

    public static function registerNewUser() {
        // clean the input
        $real_name = strip_tags(Request::post('real_name'));
        $last_name = strip_tags(Request::post('last_name'));
        $user_type = strip_tags(Request::post('user_type'));
        $user_name = strip_tags(Request::post('user_name'));
        $user_email = strip_tags(Request::post('user_email'));
        $user_phone = strip_tags(Request::post('user_phone'));
        $user_password_new = Request::post('user_password_new');
        $user_password_repeat = Request::post('user_password_repeat');
        $user_access_code = Request::post('user_password_new');

        // stop registration flow if registrationInputValidation() returns false (= anything breaks the input check rules)
        $validation_result = self::registrationInputValidation(
                                        $real_name,
                                        $user_name,
                                        $user_type,
                                        $user_password_new, 
                                        $user_password_repeat, 
                                        $user_email);
        if (!$validation_result) {
            return false;
        }

        // crypt the password with the PHP 5.5's password_hash() function, 
        $user_password_hash = password_hash($user_password_new, PASSWORD_DEFAULT);

        // make return a bool variable, so both errors can come up at once if needed
        $return = true;

        // check if username already exists
        if (UserModel::doesUsernameAlreadyExist($user_name)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_ALREADY_TAKEN'));
            $return = false;
        }

        // check if email already exists
        if (UserModel::doesEmailAlreadyExist($user_email)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USER_EMAIL_ALREADY_TAKEN'));
            $return = false;
        }

        // if Username or Email were false, return false
        if (!$return) return false;

        // generate random hash for email verification (40 char string)
        $user_activation_hash = sha1(uniqid(mt_rand(), true));

        // write user data to database
        if (!self::writeNewUserToDatabase($real_name, $last_name, $user_type, $user_phone, $user_name, $user_password_hash, $user_email, time(), $user_activation_hash, $user_access_code)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_CREATION_FAILED'));
            return false; // no reason not to return false here
        }

        // get user_id of the user that has been created, to keep things clean we DON'T use lastInsertId() here
        $user_id = UserModel::getUserIdByUsername($user_name);

        if (!$user_id) {
            Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
            return false;
        }

        return true;
    }

    /**
     * Validates the registration input
     */
    public static function registrationInputValidation($real_name, $user_name, $user_type,$user_password_new, $user_password_repeat, $user_email){
        $return = true;

        // if username, email and password are all correctly validated, but make sure they all run on first sumbit
        if (self::validateRealName($real_name) AND 
            self::validateUserName($user_name) AND
            self::validateUserType($user_type) AND 
            self::validateUserEmail($user_email) AND 
            self::validateUserPassword($user_password_new, $user_password_repeat) AND 
            $return) {
                return true;
        }

        // otherwise, return false
        return false;
    }


    public static function validateRealName($real_name){
        if (empty($real_name)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_FIELD_EMPTY'));
            return false;
        }

        // if username is too short (3), too long (50) or does not fit the pattern (aZ09)
        if (!preg_match('/^[a-zA-Z]{3,50}$/', $real_name)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN'));
            return false;
        }

        return true;
    }

    /**
     * Validates the username
     */
    public static function validateUserName($user_name){
        if (empty($user_name)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_FIELD_EMPTY'));
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9]{3,50}$/', $user_name)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN'));
            return false;
        }

        return true;
    }

    public static function validateUserType($user_type){
        if (empty($user_type)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_FIELD_EMPTY'));
            return false;
        }

        return true;
    }

    /**
     * Validates the email
     */
    public static function validateUserEmail($user_email) {
        if (empty($user_email)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_EMAIL_FIELD_EMPTY'));
            return false;
        }

        // validate the email with PHP's internal filter
        // side-fact: Max length seems to be 254 chars
        // @see http://stackoverflow.com/questions/386294/what-is-the-maximum-length-of-a-valid-email-address
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN'));
            return false;
        }

        return true;
    }

    /**
     * Validates the password
     */
    public static function validateUserPassword($user_password_new, $user_password_repeat) {
        if (empty($user_password_new) OR empty($user_password_repeat)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_FIELD_EMPTY'));
            return false;
        }

        if ($user_password_new !== $user_password_repeat) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_REPEAT_WRONG'));
            return false;
        }

        if (strlen($user_password_new) < 5) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_TOO_SHORT'));
            return false;
        }

        return true;
    }

    /**
     * Writes the new user's data to the database
     */
    public static function writeNewUserToDatabase($real_name, $lastname, $user_type, $user_phone, $user_name, 
                                                  $user_password_hash, $user_email, $user_creation_timestamp, 
                                                  $user_activation_hash, $user_access_code) {
        $database = DatabaseFactory::getFactory()->getConnection();

        // write new users data into database
        $sql = "INSERT INTO users (name, 
                                   lastname, 
                                   user_type,
                                   user_name,
                                   user_phone, 
                                   user_password_hash, 
                                   user_email,
                                   user_access_code,
                                   user_creation_timestamp,  
                                   user_active)
                    VALUES (:real_name, 
                            :lastname, 
                            :user_type,
                            :user_name,
                            :user_phone,
                            :user_password_hash, 
                            :user_email,
                            :access_code, 
                            :user_creation_timestamp,  
                            :user_active)";

        $query = $database->prepare($sql);
        $query->execute(array(':real_name' => $real_name, 
                              ':lastname' => $lastname,
                              ':user_type' => $user_type,
                              ':user_name' => $user_name,
                              ':user_phone' => $user_phone,
                              ':user_password_hash' => $user_password_hash,
                              ':user_email' => $user_email,
                              ':access_code' => $user_access_code,
                              ':user_creation_timestamp' => $user_creation_timestamp,
                              ':user_active' => 1));
        $count =  $query->rowCount();
        if ($count === 1) {
            if ((int)$user_type === 3) {
                $sql = $database->prepare("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1;");
                $sql->execute();
                $teacher = $sql->fetch()->user_id;
                if (!empty($_FILES) && isset($_FILES['avatar_file']) && $_FILES['avatar_file']['tmp_name'] !== "") {
                    PhotosModel::createAvatar($teacher, 'teacher', $_FILES['avatar_file']);
                }
                if (!empty($_FILES) && isset($_FILES['avatar']) && $_FILES['avatar']['tmp_name'] !== "") {
                    PhotosModel::createAvatar($teacher, 'teacher', $_FILES['avatar']);
                }
            }
            return true;
        }

        return false;
    }

    /**
     * Deletes the user from users table.
     */
    public static function rollbackRegistrationByUserId($user_id) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("DELETE FROM users WHERE user_id = :user_id");
        $query->execute(array(':user_id' => $user_id));
    }

}
