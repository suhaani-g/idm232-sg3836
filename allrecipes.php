<?php
// Database connection variables
$servername = "localhost"; 
$username = "sg3836";        
$password = "fy0b2TnFyh1zcpIo";             
$database = "sg3836_db"; 
$where="";
// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
    //read filters from URL
    $cooktime = $_GET['cooktime'];
    $mealtype = $_GET['mealtype'];
    $cuisine = $_GET['cuisine'];
// make where query based on filters
    if ($cooktime=='under-30') {
        $where  ="where (cook_time BETWEEN 0 AND 30)";
    }
    else if ($cooktime=='30-60'){
        $where="where (cook_time>30 AND cook_time<=60)";
    }
    else if ($cooktime=="over-60"){
        $where="where (cook_time>60)";
    }
    
    if ($mealtype=='breakfast'){
       if ($where==''){
        $where="where categories='Breakfast'";}
        else{
            $where=$where." AND categories='Breakfast'";
        }
    }
    if ($mealtype=='lunch'){
        if ($where==''){
            $where="where categories='Lunch'";
        }
        else{
            $where=$where." AND categories='Lunch'";
        }
    }
    if ($mealtype=='dinner'){
        if ($where==''){
            $where="where categories='Dinner'";}
        else{
            $where=$where." AND categories='Dinner'";
        }
    }
    if ($mealtype=='dessert'){
        if ($where==''){
            $where="where categories='Dessert'";}
        else{
            $where=$where." AND categories='Dessert'";
        }
    }

    if ($cuisine=='asian'){
        if ($where==''){
         $where="where cuisine='Asian'";}
         else{
             $where=$where." AND cuisine='Asian'";
         }
     }
     if ($cuisine=='american'){
         if ($where==''){
             $where="where cuisine='American'";
         }
         else{
             $where=$where." AND cuisine='American'";
         }
     }
     if ($cuisine=='italian'){
         if ($where==''){
             $where="where cuisine='Italian'";}
         else{
             $where=$where." AND cuisine='Italian'";
         }
     }
     if ($cuisine=='mexican'){
         if ($where==''){
             $where="where cuisine='Mexican'";}
         else{
             $where=$where." AND cuisine='Mexican'";
         }
     } 
     if ($cuisine=='mediterranean'){
        if ($where==''){
            $where="where cuisine='Mediterranean'";}
        else{
            $where=$where." AND cuisine='Mediterranean'";
        }
    } 

// SQL query to fetch first_name and last_name
$sql = "SELECT id,recipe_name,description FROM recipes ".$where;
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse All Recipes</title>
    <link rel="stylesheet" href="allrecipes.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="allrecipes.php">All Recipes</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="help.html">Help</a></li>
        </ul>
    </nav>

    <!-- Main Container -->
    <main class="browse-recipes-container">
        <!-- Sidebar for Filter Options -->
        <aside class="filter-sidebar">
            <h2>Filter Results</h2>
            <div class="filter-option">
                <h3>Cooking Time</h3>
                <select id="cooktime" onchange="FilteredRecipes()">
                    
                    <option value="any"<?php echo $cooktime === 'any' ? 'selected' : ''; ?>>Any</option>
                    <option value="under-30"<?php echo $cooktime === 'under-30' ? 'selected' : ''; ?>>Under 30 minutes</option>
                    <option value="30-60" <?php echo $cooktime === '30-60' ? 'selected' : ''; ?>>30-60 minutes</option>
                    <option value="over-60"<?php echo $cooktime === 'over-60' ? 'selected' : ''; ?>>Over 60 minutes</option>
                </select>
            </div>
            <div class="filter-option">
                <h3>Meal Type</h3>
                <select id="mealtype" onchange="FilteredRecipes()">
                    <option value="any"<?php echo $mealtype === 'any' ? 'selected' : ''; ?>>Any</option>
                    <option value="breakfast"<?php echo $mealtype === 'breakfast' ? 'selected' : ''; ?>>Breakfast</option>
                    <option value="lunch"<?php echo $mealtype === 'lunch' ? 'selected' : ''; ?>>Lunch</option>
                    <option value="dinner"<?php echo $mealtype === 'dinner' ? 'selected' : ''; ?>>Dinner</option>
                    <option value="dessert"<?php echo $mealtype === 'dessert' ? 'selected' : ''; ?>>Dessert</option>
                </select>
            </div>
            <div class="filter-option">
                <h3>Cuisine</h3>
                <select id="cuisine" onchange="FilteredRecipes()">
                    <option value="any"<?php echo $cuisine === 'any' ? 'selected' : ''; ?>>Any</option>
                    <option value="asian"<?php echo $cuisine === 'asian' ? 'selected' : ''; ?>>Asian</option>
                    <option value="italian"<?php echo $cuisine === 'italian' ? 'selected' : ''; ?>>Italian</option>
                    <option value="mexican"<?php echo $cuisine === 'mexican' ? 'selected' : ''; ?>>Mexican</option>
                    <option value="american"<?php echo $cuisine === 'american' ? 'selected' : ''; ?>>American</option>
                    <option value="mediterranean"<?php echo $cuisine === 'mediterranean' ? 'selected' : ''; ?>>Mediterranean</option>
                </select>
            </div>
        </aside>
       

        <!-- Recipe Cards Section -->
<section class="recipe-cards-section">  
        <h1>Browse All Recipes</h1>
       
<div class="recipe-cards-grid">
    <?php if (!empty($result)):
        foreach ($result as $row):
            $imageName=$row['recipe_name'];
            $imageName=str_replace(" ","",$imageName);
            $imageName=str_replace("&","",$imageName);
            $imageName=str_replace("-","",$imageName);           
            $imagePath="cookbook_images/" . $imageName."/";       
            $imageName=$row['id']."-".$imageName;      
            $imageName=$imagePath . $imageName;

            echo "<div class=\"recipe-card\">";
                echo "<a href=\"recipe.html\id=";
                echo $row['id']. "\">\"";
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
</div>   
</section>
        
</main>

    <footer>
        <p>&copy; 2024 Online Cookbook. All rights reserved.</p>
    </footer>
    <script>
        function FilteredRecipes() {
            const cooktime = document.getElementById("cooktime").value;
            const mealtype = document.getElementById("mealtype").value;
            const cuisine = document.getElementById("cuisine").value;
            window.location.href = `allrecipes.php?cooktime=${encodeURIComponent(cooktime)}& mealtype=${encodeURIComponent(mealtype)}&cuisine=${encodeURIComponent(cuisine)}`;
        
        }
    </script>
</body>
</html>

