<?php
include('../components/db_connect.php');
include '../components/popup.php';

session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: ../login.php");
    exit();
}

$query = "SELECT * FROM pets"; 
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    displayPopup("Pet deleted successfully.");
} elseif (isset($_GET['error']) && $_GET['error'] == 1) {
    displayPopup("Something went wrong. Please try again.", 'error');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pet Management</title>
    <link rel="stylesheet" href="../styles.css" />
    <link rel="stylesheet" href="../general.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>

<body>
    <?php include '../components/sidebar.php' ?>
    <section class="pet-management-section" style="margin-left: 85px">
        <div class="breadcrumbs">
            <div class="left">
                <p>Admin > <span>PET MANAGEMENT</span></p>
            </div>

            <div class="right">
                <a href="../logout.php"><span class="material-symbols-outlined"> logout </span>Logout</a>
            </div>
        </div>

        <div class="management-container">
            <div class="management-top-panel">
                <div class="header">
                    <h1>Pet Management</h1>
                    <p class="subtitle">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </p>
                </div>
                <div class="top-buttons">
                    <select name="sort" id="" class="sort-input">
                        <option value="sort" disabled selected>Sort</option>
                        <option value="alphabetical">Alphabetical</option>
                        <option value="newest">Newest</option>
                    </select>
                    <a href="../components/add-pet.php" class="add-btn"><span
                            class="material-symbols-outlined">add</span>Add Pet</a>
                </div>
            </div>

            <div class="search-input">
                <input type="text" name="" placeholder="Search something..." id="" /><span
                    class="material-symbols-outlined">search</span>
            </div>

            <div class="management-bottom-panel">
                <table class="general-table">
                    <tr>
                        <th>ID</th>
                        <th>IMAGE</th>
                        <th>SPECIES</th>
                        <th>BREED</th>
                        <th>AGE</th>
                        <th>GENDER</th>
                        <th>COLOR</th>
                        <th>VACCINATION STATUS</th>
                        <th>NEUTERED STATUS</th>
                        <th>MEDICAL CONDITION</th>
                        <th>ADOPTION STATUS</th>
                        <th>SHELTER</th>
                        <th>INTAKE DATE</th>
                        <th></th>
                    </tr>

                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        $imageData = base64_encode($row['pet_img']); // Assuming 'image' is the BLOB column
                        $imageSrc = 'data:image/jpeg;base64,' . $imageData; // You can change the MIME type if necessary

                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td><img src='" . $imageSrc . "' alt='Pet Image' width='50' height='50'></td>";
                        echo "<td>" . $row['species'] . "</td>";
                        echo "<td>" . $row['breed'] . "</td>";
                        echo "<td>" . $row['age'] . "</td>";
                        echo "<td>" . $row['gender'] . "</td>";
                        echo "<td>" . $row['color'] . "</td>";
                        echo "<td>" . $row['vaccination_status'] . "</td>";
                        echo "<td>" . $row['neutered_status'] . "</td>";
                        echo "<td>" . $row['medical_condition'] . "</td>";
                        echo "<td>" . $row['adoption_status'] . "</td>";
                        echo "<td>" . $row['shelter'] . "</td>";
                        echo "<td>" . $row['intake_date'] . "</td>";
                        echo "<td class='options-btn'>
                                <span class='material-symbols-outlined'>edit</span>
                                <div class='pop-up'>
                                    <a href='../components/edit-user.php?id={$row['id']}'><span class='material-symbols-outlined'>edit</span>Edit</a>
                                    <form action='../components/delete-pet.php' method='POST' onsubmit='return confirm(\"Are you sure you want to delete this entry?\");'>
                                        <input type='hidden' name='user_id' value='{$row['id']}'>
                                        <button type='submit' class='delete-btn'>
                                            <span class='material-symbols-outlined'>delete</span>Delete
                                        </button>
                                    </form>
                                </div>
                              </td>";
                        echo "</tr>";
                    }
                    ?>

                </table>
            </div>
        </div>
    </section>
    <script>
    function closePopup() {
        const popup = document.querySelector(".popup-overlay");
        if (popup) {
            popup.classList.remove("show");
        }
    }

    window.addEventListener("load", function() {
        const popup = document.querySelector(".popup-overlay");
        if (popup) {
            setTimeout(() => {
                closePopup();
            }, 3000);
        }
    });
    </script>
</body>

</html>

<?php
mysqli_close($conn);
?>