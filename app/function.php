<?php
//unset all given sessions

/**
 * @param mixed $name
 */
if (!function_exists('unsetSession')) {

    function unsetSession()
    {
        $argument  = func_get_args();

        foreach ($argument as $arg) {
            unset($_SESSION[$arg]);
        }
    }
}
//unset all given posts
if (!function_exists('unsetPost')) {
    function unsetPost()
    {
        $argument  = func_get_args();

        foreach ($argument as $arg) {
            unset($_POST[$arg]);
        }
    }
}

if (!function_exists('saveCheckImg')) {
    /*
    $postOrProfileImg is where the db will get the db

    $img is the file array that is checked with filesize and type.

    $redirectPath is to redirect back when there is an error.

    $folderPath is where the image will be saved

    return a imgPath session that is used to for the db.
    */
    function saveCheckImg(int $id, string $postOrProfileImg, array $img, string $redirectPath, string $folderPath)
    {
        $error = [];
        if ($img['size'] > 2000000) {
            $error[] = $img['name'] . ' exceeded filesize the filesize limit';
        }

        if (!in_array($img['type'], ['image/jpeg', 'image/png'])) {
            $error = 'only accept jpeg and png images';
        }
        if (!empty($error)) {
            for ($i = 0; $i < 2; $i++) {

                $_SESSION['error']['img'] = $error[$i];
            }
            redirect($redirectPath);
        } else {
            $newImagePath  = $folderPath . $id . '-' . time()  . '-' . $img['name'];
            move_uploaded_file($img['tmp_name'], $newImagePath);

            if ($postOrProfileImg === 'post') {
                return $_SESSION['imgPath'] = '/assets/Images/post_img/' . $id . '-' . time()  . '-' . $img['name'];
            }

            if ($postOrProfileImg === 'profile') {
                return $_SESSION['imgPath'] = '/assets/Images/profile_img/' . $id . '-' . time() . '-' . $img['name'];
            }
        }
    }
}

if (!function_exists('checkMail')) {
    function checkMail($pdo, string $email, string $type)
    {
        $emailCheck = 'SELECT email, profile_name FROM users where email = :email';
        $statementEmail = $pdo->prepare($emailCheck);
        $statementEmail->execute([':email' => $email]);
        $checkEmail = $statementEmail->fetchAll(PDO::FETCH_ASSOC);

        for ($i = 0; $i < sizeof($checkEmail); $i++) {
            if ($checkEmail[$i]['email'] === $email) {
                $_SESSION['error'][$type] = 'email already exists';
                redirect('/');
            }
        }
    }
}


if (!function_exists('redirect')) {
    /**
     * Redirect the user to given path.
     *
     * @param string $path
     *
     * @return void
     */
    function redirect($path)
    {
        header("Location: ${path}");
        exit;
    }
}

if (isset($_SESSION["user"])) {
    $id = $_SESSION["user"]['id'];
    $id = (int) $id;
} else {
    $id = null;
};



if (!function_exists('getProfile')) {
    /**
     * Fetch the profile information from database.
     *
     * @param PDO $pdo
     * @param string $profileName
     * @return void
     */
    function getProfile(PDO $pdo, string $profileName)
    {
        $statement = $pdo->prepare('SELECT id, profile_name, profile_image, profile_bio
        FROM users WHERE profile_name = ?');

        $statement->execute([$profileName]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('isFollowing')) {
    /**
     * See if user is following a user.
     *
     * @param PDO $pdo
     * @param integer $userId
     * @param integer $followId
     * @return void
     */
    function isFollowing(PDO $pdo, int $userId, int $followId)
    {
        $statement = $pdo->prepare('SELECT * FROM user_follows WHERE user_id = ? AND follow_id = ?');

        $statement->execute([$userId, $followId]);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('getAmountFollowings')) {
    /**
     * Fetch user followings.
     *
     * @param PDO $pdo
     * @param integer $userId
     * @return void
     */
    function getAmountFollowings($pdo, int $userId)
    {
        $statement = $pdo->prepare('SELECT COUNT(user_id)
        FROM user_follows
        WHERE user_id=?');

        $statement->execute([$userId]);

        $following = $statement->fetch(PDO::FETCH_COLUMN);

        return $following;
    }
}

if (!function_exists('getAmountFollowers')) {
    /**
     * Fetch user followers.
     *
     * @param PDO $pdo
     * @param integer $userId
     * @return void
     */
    function getAmountFollowers($pdo, int $userId)
    {
        $statement = $pdo->prepare('SELECT COUNT(follow_id)
        FROM user_follows
        WHERE follow_id=?');

        $statement->execute([$userId]);

        $followers = $statement->fetch(PDO::FETCH_COLUMN);

        return $followers;
    }
}

/**
 * Callback function for sorting items in descending order.
 *
 * @param array $a
 * @param array $b
 * @return void
 */
function sortById(array $a, array $b)
{
    return ($a['post_id'] < $b['post_id']);
}
