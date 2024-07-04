<?php

include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
secure();

include('includes/header.php');

if (isset($_POST['username'])) {

    $username = $_POST['username'];
    $email = $_POST['email'];
    // $hashed = SHA1($_POST['password']);
    $active = $_POST['active'];
    $id = $_GET['id'];
    $admin = $_POST['role'];

    if ($stm = $pdo->prepare('UPDATE users set  username = ?,email = ? , active = ? ,role = ? WHERE id = ?')) {
        // $stm->bind_param('sssi', $_POST['username'], $_POST['email'], $_POST['active'], $_GET['id']);
        $stm->bindValue(1, $username, PDO::PARAM_INT);
        $stm->bindValue(2, $email, PDO::PARAM_INT);
        $stm->bindValue(3, $active, PDO::PARAM_INT);
        $stm->bindValue(4, $admin, PDO::PARAM_STR);
        $stm->bindValue(5, $id, PDO::PARAM_INT);
        

        $stm->execute();




        $stm->closeCursor();

        if (isset($_POST['password'])) {
            if ($stm = $pdo->prepare('UPDATE users set  password = ? WHERE id = ?')) {
                $hashed = SHA1($_POST['password']);
                // $stm->bind_param('si', $hashed, $_GET['id']);
                $stm->bindValue(1, $hashed, PDO::PARAM_INT);
                $stm->bindValue(2, $id, PDO::PARAM_INT);
                $stm->execute();

                $stm->closeCursor();

            } else {
                echo 'Could not prepare password update statement!';
            }
        }

        set_message("A user with id: " . $_GET['id'] . " has been updated");
        header('Location: users.php');
        die();

    } else {
        echo 'Could not prepare user update statement statement!';
    }





}


if (isset($_GET['id'])) {
    $deleteId = $_GET['id'];

    if ($stm = $pdo->prepare('SELECT * from users WHERE id = ?')) {
        // $stm->bind_param('i', $_GET['id']);
        $stm->bindValue(1, $deleteId, PDO::PARAM_INT);
        $stm->execute();

        // $result = $stm->get_result();
        // $user = $result->fetch_assoc();
        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if ($user) {


            ?>

<style>
/* Adjusting the width as per your requirement */
#active {
    width: calc(50% - 1rem); /* Adjusted width for Active select */
}

#admin {
    width: calc(50% - 1rem); /* Adjusted width for Role select */
}


    /* Dark theme styles */
body {
    background-color: #1b1e23;
    color: #fff;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    padding-top: 2rem;
    width: 80%; /* Adjusted container width */
    margin: 0 auto; /* Center align the container */
}

.form-outline {
    position: relative;
    margin-bottom: 1.5rem;
}

.form-outline input,
.form-outline select {
    width: calc(100% - 2rem); /* Adjusted width for input/select */
    padding: 0.5rem 1rem; /* Reduced padding */
    background-color: #2d323a;
    border: 1px solid #3c424b;
    color: #fff;
    border-radius: 0.25rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-outline input:focus,
.form-outline select:focus {
    outline: none;
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.form-outline label {
    position: absolute;
    top: 0.5rem;
    left: 1rem;
    color: #8c8c8c; /* Adjusted label color */
    pointer-events: none;
    transition: top 0.2s ease, left 0.2s ease, color 0.2s ease;
}

.form-outline input:focus ~ label,
.form-outline input:not(:placeholder-shown) ~ label,
.form-outline select:focus ~ label,
.form-outline select:not(:placeholder-shown) ~ label {
    top: -0.5rem;
    left: 1rem;
    color: #ffc107;
    font-size: 1rem;
    background-color: transparent;
    padding: 0 0.25rem;
}

.form-outline select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 0.75rem 1rem;
    cursor: pointer;
    border-radius: 0.25rem;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-block {
    display: block;
    width: 100%;
}

.row.justify-content-center {
    margin: 0;
}

.col-md-6 {
    padding: 0 15px;
}



/* Importing Font Awesome for icons */
 .body{}
.goback-container {
    text-align: left;
    left : 1 rem;
    padding: 0.75rem 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;

}

.goback-link {
     

    text-decoration: none;
    color: #007bff; /* Link color */
    padding: 10px 20px;
    border: 1px solid #007bff; /* Border color */
    border-radius: 4px;
    background-color: #fff;
    display: inline-block;
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

.goback-link:hover {
    background-color: #007bff; /* Background color on hover */
    color: #fff; /* Text color on hover */
}

 
</style>
 
<div   class="goback-container">
    <a  class=" goback-link" href=" users.php">
   
  Go Back 
  </a>  
</div> 
            <div class="container mt-5">
                <div class="row.justify-content-center">
                    <div class="col-md-6">
                        <h1 class="display-1">Edit User</h1>

                        <form method="post">
                            <!-- Username input -->
                            <div class="form-outline mb-4">
                                <input type="text" id="username" name="username" class="form-control active"
                                    value="<?php echo $user['username'] ?>" />
                                <label class="form-label" for="username">Username</label>
                            </div>
                            <!-- Email input -->
                            <div class="form-outline mb-4">
                                <input type="email" id="email" name="email" class="form-control active"
                                    value="<?php echo $user['email'] ?>" />
                                <label class="form-label" for="email">Email address</label>
                            </div>

                            <!-- Password input -->
                            <div class="form-outline mb-4">
                                <input type="password" id="password" name="password" class="form-control" />
                                <label class="form-label" for="password">Password</label>
                            </div>

                            <!-- Active select -->
                            <div class="form-outline mb-4">
                                <select name="active" class="form-select" id="active">
                                    <option <?php echo ($user['active']) ? "selected" : ""; ?> value="1">Active</option>
                                    <option <?php echo ($user['active']) ? "" : "selected"; ?> value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="form-outline mb-4">
                    <select name="role" class="form-select" id="admin" >
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>

                            <!-- Submit button -->
                            <button type="submit" class="btn btn-primary btn-block">Update user</button>
                        </form>



                    </div>

                </div>
            </div>


            <?php
        }
        $stm->closeCursor();
     

    } else {
        echo 'Could not prepare statement!';
    }

} else {
    echo "No user selected";
    die();
}

include('includes/footer.php');
?>