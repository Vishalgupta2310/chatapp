<?php
session_start();
include_once "config.php";
$fname = mysqli_real_escape_string($conn, $_POST['fname']);
$lname = mysqli_real_escape_string($conn, $_POST['lname']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password)) {
    // we have to check email is valid or not    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) { //if email is valid   
        // let's check the email is already exists or not
        $sql = mysqli_query($conn, "SELECT email from users WHERE email='{$email}'");
        if (mysqli_num_rows($sql) > 0) //if Email is already exist
        {
            echo $email . "This Email is Already Exists";
        } else {
            //let 's check user upload file or not
            if (isset($_FILES['image'])) { //if file is uploaded
                $image_name = $_FILES['image']['name']; // getting user uploaded image name
                $image_type = $_FILES['image']['type']; // getting user uploaded image type
                $image_tmp = $_FILES['image']['tmp_name']; // getting user uploaded image temp name to move in save folder

                // let's explode image name to get last  extension like png or jpg
                $image_explode = explode('.', $image_name);
                $image_ext = end($image_explode); // here we get the extension of image
                $extension = ["jpg", "jpeg", "png"]; // these are some valid extension that we stored in array
                if (in_array($image_ext, $extension) === true) { // if user uploaded image extension match with our array
                    $time = time(); //this will return us current time
                    //we need this time  because when u uploding user img to inour folder we rename 
                    // user file with current time so all img file have unique name
                    // let's move the user file to particular folder
                    $new_img_name = $time . $image_name;
                    if (move_uploaded_file($image_tmp, "../images/" . $new_img_name)) { // if user uploaded image is successfully move to our folder
                        $status = "Active now"; // once user signup then the status will active now 
                        $random_id = rand(time(), 10000000);
                        // let's insert all data to the database
                        $sql2 = mysqli_query($conn, "INSERT INTO users (unique_id,fname,lname,email,password,img,status) 
                        values ('{$random_id}','{$fname}','{$lname}','{$email}','{$password}','{$new_img_name}','{$status}')");
                        if ($sql2) // if these data inserted
                        {
                            $sql3 = mysqli_query($conn, "SELECT * from users WHERE email = '{$email}'");
                            if (mysqli_num_rows($sql3) > 0) {
                                $row = mysqli_fetch_assoc($sql3);
                                $_SESSION['unique_id'] = $row['unique_id']; // using this session we used user unique id in other php file
                                echo "success";
                            }
                        } else {
                            echo "Something Went Wrong !";
                        }
                    }
                } else {
                    echo "Please Select Iamge File - jpg ,png ,jpeg !";
                }
            } else {
                echo "Please Select Image File!";
            }
        }
    } else {
        echo $email . "is not a Valid Email!";
    }
} else {
    echo "All Fields are Required!";
}
