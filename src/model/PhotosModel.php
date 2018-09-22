<?php

class PhotosModel
{
    public static function createAvatar($user, $name, $file)
    {
        // check avatar folder writing rights, check if upload fits all rules
        if (self::isAvatarFolderWritable() AND self::validateImageFile($file)) {

            switch ($name) {
                case 'student':
                    $target_file_path = Config::get('PATH_AVATARS_STUDENTS') . $name. '_' . $user;
                    $type = 2;
                    break;
                case 'teacher':
                    $target_file_path = Config::get('PATH_AVATARS_USERS') . $name. '_' . $user;
                    $type = 1;
                    break;
                case 'user':
                    $target_file_path = Config::get('PATH_AVATARS_USERS') . $name. '_' . $user;
                    $type = 0;
                    break;
            }

            // create a jpg file in the avatar folder, write marker to database
            self::resizeAvatarImage($file['tmp_name'], 
                                    $target_file_path, 
                                    Config::get('AVATAR_SIZE'), 
                                    Config::get('AVATAR_SIZE'));

            $save = self::writeAvatarToDatabase($user, $name.'_'.$user, $type);
            if ($save) {
                return true;
            } else {
                return false;
            }
            
        }
    }

    /**
     * Checks if the avatar folder exists and is writable
     *
     * @return bool success status
     */
    public static function isAvatarFolderWritable()
    {
        if (is_dir(Config::get('PATH_AVATARS_STUDENTS')) AND is_writable(Config::get('PATH_AVATARS_STUDENTS'))) {
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_FOLDER_DOES_NOT_EXIST_OR_NOT_WRITABLE'));
        return false;
    }

    /**
     * Validates the image
     * Only accepts gif, jpg, png types
     * @see http://php.net/manual/en/function.image-type-to-mime-type.php
     *
     * @return bool
     */
    public static function validateImageFile($file)
    {
        if (!isset($_FILES['avatar_file'])) {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED'));
            return false;
        }

        // if input file too big (>5MB)
        if ($file['size'] > 5000000) {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_BIG'));
            return false;
        }

        // get the image width, height and mime type
        $image_proportions = getimagesize($_FILES['avatar_file']['tmp_name']);

        // if input file too small, [0] is the width, [1] is the height
        if ($image_proportions[0] < Config::get('AVATAR_SIZE') OR $image_proportions[1] < Config::get('AVATAR_SIZE')) {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_TOO_SMALL'));
            return false;
        }

        // if file type is not jpg, gif or png
        if (!in_array($image_proportions['mime'], array('image/jpeg', 'image/gif', 'image/png'))) {
            Session::add('feedback_negative', Text::get('FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE'));
            return false;
        }

        return true;
    }

    /**
     * Writes marker to database, saying user has an avatar now
     */
    public static function writeAvatarToDatabase($user_id, $avatar, $type)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        switch ($type) {
            case 0:
            case 1:
                $query = $database->prepare("UPDATE users 
                                            SET user_avatar = :avatar 
                                            WHERE user_id = :user_id LIMIT 1");
                $save = $query->execute(array(':user_id' => $user_id, ':avatar' => $avatar));
                
                break;
            case 2:
                $query = $database->prepare("UPDATE students 
                                            SET avatar = :avatar 
                                            WHERE student_id = :user_id LIMIT 1");
                $save = $query->execute(array(':user_id' => $user_id, ':avatar' => $avatar));
                break;
        }

        if ($save) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Resize avatar image (while keeping aspect ratio and cropping it off in a clean way).
     * Only works with gif, jpg and png file types. If you want to change this also have a look into
     * method validateImageFile() inside this model.
     */
    public static function resizeAvatarImage($source_image, $destination, $final_width = 100, $final_height = 100)
    {
        $imageData = getimagesize($source_image);
        $width     = $imageData[0];
        $height    = $imageData[1];
        $mimeType  = $imageData['mime'];

        if (!$width || !$height) {
            return false;
        }

        switch ($mimeType) {
            case 'image/jpeg': $myImage = imagecreatefromjpeg($source_image); break;
            case 'image/png': $myImage = imagecreatefrompng($source_image); break;
            case 'image/gif': $myImage = imagecreatefromgif($source_image); break;
            default: return false;
        }

        // calculating the part of the image to use for thumbnail
        if ($width > $height) {
            $verticalCoordinateOfSource = 0;
            $horizontalCoordinateOfSource = ($width - $height) / 2;
            $smallestSide = $height;
        } else {
            $horizontalCoordinateOfSource = 0;
            $verticalCoordinateOfSource = ($height - $width) / 2;
            $smallestSide = $width;
        }

        // copying the part into thumbnail, maybe edit this for square avatars
        $thumb = imagecreatetruecolor($final_width, $final_height);
        imagecopyresampled($thumb, $myImage, 0, 0, $horizontalCoordinateOfSource, $verticalCoordinateOfSource, $final_width, $final_height, $smallestSide, $smallestSide);

        // add '.jpg' to file path, save it as a .jpg file with our $destination_filename parameter
        imagejpeg($thumb, $destination . '.jpg', Config::get('AVATAR_JPEG_QUALITY'));
        imagedestroy($thumb);

        if (file_exists($destination)) {
            return true;
        }
        return false;
    }

    /**
     * Delete a user's avatar
     *
     * @param int $userId
     * @return bool success
     */
    public static function deleteAvatar($student_id, $avatar)
    {
        if (!ctype_digit($student_id)) {
            Session::add("feedback_negative", Text::get("FEEDBACK_AVATAR_IMAGE_DELETE_FAILED"));
            return false;
        }

        // try to delete image, but still go on regardless of file deletion result
        self::deleteAvatarImageFile($avatar);

        $database = DatabaseFactory::getFactory()->getConnection();

        $sth = $database->prepare("UPDATE students 
                                   SET avatar = 'default' 
                                   WHERE student_id = :student_id LIMIT 1");
        $sth->execute(array(':student_id' => $student_id));

        if ($sth->rowCount() === 1) {
            Session::add("feedback_positive", Text::get("FEEDBACK_AVATAR_IMAGE_DELETE_SUCCESSFUL"));
            return true;
        } else {
            Session::add("feedback_negative", Text::get("FEEDBACK_AVATAR_IMAGE_DELETE_FAILED"));
            return false;
        }
    }

    /**
     * Removes the avatar image file from the filesystem
     *
     * @param integer $userId
     * @return bool
     */
    public static function deleteAvatarImageFile($avatar)
    {
        // Check if file exists
        if (!file_exists(Config::get('PATH_AVATARS') . $avatar . ".jpg")) {
            Session::add("feedback_negative", Text::get("FEEDBACK_AVATAR_IMAGE_DELETE_NO_FILE"));
            return false;
        }

        // Delete avatar file
        if (!unlink(Config::get('PATH_AVATARS') . $avatar . ".jpg")) {
            Session::add("feedback_negative", Text::get("FEEDBACK_AVATAR_IMAGE_DELETE_FAILED"));
            return false;
        }

        return true;
    }
}
