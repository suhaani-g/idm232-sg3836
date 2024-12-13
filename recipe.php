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
   
    $id = $_GET['id'];
$where="where id=".$id;
$sql = "SELECT * FROM recipes ".$where;
$result = $conn->query($sql);


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Detail</title>
    <link rel="stylesheet" href="recipe.css">
    <link rel="stylesheet" href="nav.css">
</head>
<body>
    
<nav>
    <button class="hamburger-menu" aria-label="Toggle navigation" onclick="toggleMenu()">☰</button>
    <ul id="nav-menu">
        <li><a href="index.php">Home</a></li>
        <li><a href="allrecipes.php">All Recipes</a></li>
        <li><a href="about.html">About</a></li>
        <li><a href="help.html">Help</a></li>
    </ul>
    <button id="close-menu" class="close-menu" aria-label="Close navigation" onclick="toggleMenu()">✖</button>
</nav>

<script>
    function toggleMenu() {
        const navMenu = document.getElementById('nav-menu');
        const closeMenu = document.getElementById('close-menu');

        navMenu.classList.toggle('show');
        closeMenu.style.display = navMenu.classList.contains('show') ? 'block' : 'none';
    }

    document.querySelectorAll('#nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('nav-menu').classList.remove('show');
            document.getElementById('close-menu').style.display = 'none';
        });
    });
</script>


    <main class="recipe-detail">
    <?php foreach ($result as $row):
       $imageName=$row['recipe_name'];
       $imageName=str_replace(" ","",$imageName);
       $imageName=str_replace("&","",$imageName);
       $imageName=str_replace("-","",$imageName);           
       $imagePath="cookbook_images/" . $imageName."/";       
       $imageName=$row['id']."-".$imageName;      
       $imageName=$imagePath . $imageName;
    
        echo "<section class=\"recipe-hero\">";
        echo   "<div class=\"recipe-image\">";
        echo "<img src=\"" . $imageName . "-hero.webp\"" . " alt=\"Recipe Image\">";
        
         echo   "</div>";
         echo   "<div class=\"recipe-info\">";
          echo   "<h1>".$row['recipe_name']."</h1>";
          echo     "<p>".$row['recipe_with']."</p>";
           echo     "<div class=\"recipe-meta\">";

           echo      " <p><strong>Cook Time : </strong>".$row['cook_time']."</p>";
           echo      "<p><strong>Servings : </strong>".$row['servings']."</p>";
           echo       "<p><strong>Cuisine :     </strong>".$row['cuisine']."</p>";
           echo      "<p><strong>Meal Type : </strong>".$row['categories']."</p>";

            echo    "</div>";
             echo   "<p class=\"recipe-description\">";
            echo $row["description"];
            echo    "</p>";
            echo "</div>";
        echo "</section>";

        echo "<section class=\"ingredients-section\">";
            echo "<h2>Ingredients</h2>";
            echo "<div class=\"ingredients-image\">";
            echo "<img src=\"" . $imageName . "-ingredients.webp\"" . " alt=\"Recipe Image\">";
        
            echo "</div>";
            $ingredients = explode("\n", $row['ingredients']);

            echo "<ul>";
            foreach ($ingredients as $ingredient) {
                echo "<li>".$ingredient."</li>";
            } 
            echo "</ul>";

        echo "</section>";
        $steps = explode("*", $row['steps']);
        for ($i = 1; $i < count($steps)+1; $i++)
        {
        echo "<section class=\"steps-section\">";
        echo "<h2>Steps ".$i."</h2>";
         echo   "<div class=\"step\">";
         echo "<img src=\"" . $imageName . "-step".$i .".webp\"" . " alt=\"Recipe Image\">";
        
           echo    " <p>". $steps[$i-1]."</p>";
           echo "</div>";

      echo  "</section>";
        }
    endforeach;
    ?>
    </main>

    <footer>
        <p>&copy; 2024 Online Cookbook. All rights reserved.</p>
    </footer>
</body>
</html>
