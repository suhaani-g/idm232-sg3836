<?php
// Database connection variables
$servername = "localhost"; 
$username = "sg3836";         
$password = "fy0b2TnFyh1zcpIo";             
$database = "sg3836_db"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if ($query === '') {
    // Default query for popular recipes
    $sql = "SELECT id, recipe_name, description FROM recipes WHERE popular = 1";
    $result = $conn->query($sql);
} else {
    // Prepare the query safely
    $query = "%" . $conn->real_escape_string($query) . "%";
    $sql = "SELECT * 
            FROM recipes 
            WHERE recipe_name LIKE ? 
               OR recipe_with LIKE ? 
               OR cuisine LIKE ? 
               OR categories LIKE ? 
               OR description LIKE ? 
               OR ingredients LIKE ? 
               OR steps LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $query, $query, $query, $query, $query, $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows==0){
        header("Location: no-results.html");
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Cookbook</title>
    <link rel="stylesheet" href="styles.css">
</head>
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="allrecipes.php">All Recipes</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="help.html">Help</a></li>
    </ul>
</nav>
<body>


   
    
    <header class="hero-section">
        <div class="hero-content">
            <h1>Discover Delicious Recipes</h1>
            <p>Discover dishes in our collection of recipes.</p>
            <div class="search-bar">
                <form action="index.php" method="get"> 
                    <input type="text" placeholder="Search recipes..." aria-label="Search" name="query">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>
        <div class="hero-img">
            <img src="baking.png" alt="Baking Image"
        </div>
    </header>

    <main>
        <section class="recipe-cards">  <!-- there are 57 cards-->

        <?php if (!empty($result)):
             foreach ($result as $row):
                //$imageName=htmlspecialchars($row['recipe_name'] );
                $imageName=$row['recipe_name'];
                
                $imageName=str_replace(" ","",$imageName);
                $imageName=str_replace("&","",$imageName);
                $imageName=str_replace("-","",$imageName);
                
                $imagePath="cookbook_images/" . $imageName."/";
               
                $imageName=$row['id']."-".$imageName;
                //$imagePath=str_replace("&amp;","",$imagePath);
                //$imageName=str_replace("&amp;","",$imageName);

                $imageName=$imagePath . $imageName;

                echo "<div class=\"recipe-card\">";
                echo "<a href=\"recipe.php?id=" . htmlspecialchars($row['id']) . "\">";
                
                     echo "<img src=\"" . $imageName . "-hero.webp\"" . " alt=\"Recipe Image\">";
                     echo "<h2>";
                        echo htmlspecialchars($row['recipe_name']);
                    echo "</h2>";
                    echo "<p>";
                        echo htmlspecialchars(substr($row['description'],0,70)) . ".....";
                    echo "</p>";
                echo "</a>";
                echo "</div>\n";
            
            endforeach;
          endif;
         ?>   
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Online Cookbook. All rights reserved.</p>
    </footer>
                   
</body>
</html>
