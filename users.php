<?php

include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');
secure();

include('includes/header.php');

if (isset($_GET['delete'])) {
    // Delete user if delete parameter is set
    $deleteId = $_GET['delete'];
    $stm = $pdo->prepare('DELETE FROM users WHERE id = ?');

    if ($stm) {
        $stm->bindValue(1, $deleteId, PDO::PARAM_INT);
        $stm->execute();

        set_message("User with ID {$deleteId} has been deleted");
        header('Location: users.php');
        exit();
    } else {
        echo 'Could not prepare delete statement!';
    }
}

if ($stm = $pdo->prepare('SELECT * FROM users')){
    $stm->execute();


    $result = $stm->fetchAll(PDO::FETCH_ASSOC);


    
    if ($result){
  


?>

<style>
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
}

.table {
    margin: 0 auto;
    width: 80%;
    border-collapse: collapse;
    background-color: #2d323a;
    color: #fff;
}

.table th,
.table td {
    border: 1px solid #3c424b;
    padding: 8px;
    text-align: left;
}

.table th {
    background-color: #1b1e23;
}

.table-striped tbody tr:nth-of-type(even) {
    background-color: #282d39;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: #353b44;
}


.table-hover tbody tr:hover {
    background-color: #282d67;
}

a {
    color: #ffc107;
    text-decoration: none;
}

a:hover {
    color: #ffca28;
}

.rounded-button {
    border-radius: 4px;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
    text-decoration: none;
}

.rounded-button:hover {
    background-color: #0056b3; /* Darker shade for hover */
}

.rounded-button2 {
    border-radius: 4px;
    padding: 10px 20px;
    background-color: #dc3545;
    color: #fff;
    border: none;
    cursor: pointer;
    text-decoration: none;
}

.rounded-button2:hover {
    background-color: #dc5670; /* Darker shade for hover */
}

.btn-edit {
    background-color: #007bff;
}

.btn-delete {
    background-color: #dc3545;
}

.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 123, 255, 0.5);
    z-index: 1000;
}

.modal-content {
    background-color: #fff;
    width: 300px;
    margin: 15% auto;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
}

.modal-content p {
    margin-bottom: 15px;
}

.modal-content button {
    margin-right: 10px;
    cursor: pointer;
    border: none;
    padding: 5px 10px;
    text-decoration: none;
    color: #fff;
    border-radius: 3px;
}

.modal-content button:hover {
    opacity: 0.8;
}

.modal-content .btn-delete {
    background-color: #dc3545;
}

.modal-content .btn-cancel {
    background-color: #6c757d;
}


</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
        <h1 class="display-1" style =" margin:  auto;
        width: 80%; margin-bottom: 20px">Users management</h1>
<div > 
        <button class= "rounded-button" style="margin: 0 auto;  
        margin-left: 130px; ; color: #fff; border: none; padding: 5px 10px; cursor: pointer;" onclick="location.href='users_add.php'"> Add new user</button>
         </div>
        <table class="table table-striped table-hover" style="margin-top: 30px;">
         <thead><tr>
            <th>Id</th>
            <th>Username</th>
            <th>Email</th>
            <th>Status</th>
            <th>Role</th>
            <th>Edit | Delete</th>

         </tr></thead>

         <?php foreach ($result as $record) { ?>
                            <tbody><tr>
                                <td><?php echo htmlspecialchars($record['id']); ?> </td>
                                <td><?php echo htmlspecialchars($record['username']); ?> </td>
                                <td><?php echo htmlspecialchars($record['email']); ?> </td>
                                <td><?php echo htmlspecialchars($record['active']); ?> </td>
                                <td><?php echo htmlspecialchars($record['role']); ?> </td>
                                <td>
                                <button class= " rounded-button" style=" color: #fff; border: none; padding: 5px 10px; cursor: pointer; margin-right: 5px;" onclick="location.href='users_edit.php?id=<?php echo htmlspecialchars($record['id']); ?>'">Edit</button>
<button class= " rounded-button2" style="color: #fff; border: none; padding: 5px 10px; cursor: pointer; text-decoration: none;" onclick="showDeleteModal('<?php echo htmlspecialchars($record['id']); ?>')">Delete</button>

<!-- Modal structure (hidden by default) -->
<div id="deleteModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="background-color: #fff; width: 300px; margin: 15% auto; padding: 20px; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0,0,0,0.5);">
        <p style="color: #000;">Are you sure you want to delete this user?</p>
        <div style="text-align: center;">
            <button class= " rounded-button" style="background-color: #dc3545; color: #fff; border: none; padding: 5px 10px; cursor: pointer; text-decoration: none; margin-right: 10px;" onclick="deleteUser('<?php echo htmlspecialchars($record['id']); ?>')">Delete</button>
            <button class= " rounded-button2 "style="background-color: #6c757d; color: #fff; border: none; padding: 5px 10px; cursor: pointer; text-decoration: none;" onclick="hideDeleteModal()">Cancel</button>
        </div>
    </div>
</div>


<script>
    function showDeleteModal(userId) {
        var modal = document.getElementById('deleteModal');
        modal.style.display = 'block';
        // Store the user ID in a data attribute for later use
        modal.setAttribute('data-user-id', userId);
    }

    function hideDeleteModal() {
        var modal = document.getElementById('deleteModal');
        modal.style.display = 'none';
        // Clear the stored user ID
        modal.removeAttribute('data-user-id');
    }

    function deleteUser(userId) {
        // Construct the delete URL using the stored user ID
        var deleteUrl = 'users.php?delete=' + encodeURIComponent(userId);
        // Redirect to delete URL
        location.href = deleteUrl;
    }
</script>
</td></tr></tbody>
                        <?php } ?>

                    </table>

                   

                </div>
            </div>
        </div>


<?php
   } else 
   {
    echo 'No users found';
   }

    
   $stm->closeCursor();

} else {
   echo 'Could not prepare statement!';
}
include('includes/footer.php');
?>