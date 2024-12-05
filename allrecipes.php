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

// Read filters from URL
$cooktime = isset($_GET['cooktime']) ? $_GET['cooktime'] : 'any';
$mealtype = isset($_GET['mealtype']) ? $_GET['mealtype'] : 'any';
$cuisine = isset($_GET['cuisine']) ? $_GET['cuisine'] : 'any';

// Make WHERE query based on filters
if ($cooktime === 'under-30') {
    $where = "WHERE (cook_time BETWEEN 0 AND 30)";
} elseif ($cooktime === '30-60') {
    $where = "WHERE (cook_time > 30 AND cook_time <= 60)";
} elseif ($cooktime === 'over-60') {
    $where = "WHERE (cook_time > 60)";
}

if ($mealtype !== 'any') {
    $where .= ($where ? " AND " : "WHERE ") . "categories='" . $conn->real_escape_string($mealtype) . "'";
}

if ($cuisine !== 'any') {
    $where .= ($where ? " AND " : "WHERE ") . "cuisine='" . $conn->real_escape_string($cuisine) . "'";
}

// SQL query to fetch data
$sql = "SELECT id, recipe_name, description FROM recipes $where";
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
                    <option value="any" <?php echo $cooktime === 'any' ? 'selected' : ''; ?>>Any</option>
                    <option value="under-30" <?php echo $cooktime === 'under-30' ? 'selected' : ''; ?>>Under 30 minutes</option>
                    <option value="30-60" <?php echo $cooktime === '30-60' ? 'selected' : ''; ?>>30-60 minutes</option>
                    <option value="over-60" <?php echo $cooktime === 'over-60' ? 'selected' : ''; ?>>Over 60 minutes</option>
                </select>
            </div>
            <div class="filter-option">
                <h3>Meal Type</h3>
                <select id="mealtype" onchange="FilteredRecipes()">
                    <option value="any" <?php echo $mealtype === 'any' ? 'selected' : ''; ?>>Any</option>
                    <option value="breakfast" <?php echo $mealtype === 'breakfast' ? 'selected' : ''; ?>>Breakfast</option>
                    <option value="lunch" <?php echo $mealtype === 'lunch' ? 'selected' : ''; ?>>Lunch</option>
                    <option value="dinner" <?php echo $mealtype === 'dinner' ? 'selected' : ''; ?>>Dinner</option>
                    <option value="dessert" <?php echo $mealtype === 'dessert' ? 'selected' : ''; ?>>Dessert</option>
                </select>
            </div>
            <div class="filter-option">
                <h3>Cuisine</h3>
                <select id="cuisine" onchange="FilteredRecipes()">
                    <option value="any" <?php echo $cuisine === 'any' ? 'selected' : ''; ?>>Any</option>
                    <option value="asian" <?php echo $cuisine === 'asian' ? 'selected' : ''; ?>>Asian</option>
                    <option value="italian" <?php echo $cuisine === 'italian' ? 'selected' : ''; ?>>Italian</option>
                    <option value="mexican" <?php echo $cuisine === 'mexican' ? 'selected' : ''; ?>>Mexican</option>
                    <option value="american" <?php echo $cuisine === 'american' ? 'selected' : ''; ?>>American</option>
                    <option value="mediterranean" <?php echo $cuisine === 'mediterranean' ? 'selected' : ''; ?>>Mediterranean</option>
                </select>
            </div>
        </aside>

        <!-- Recipe Cards Section -->
        <section class="recipe-cards-section">  
            <h1>Browse All Recipes</h1>
            <div class="recipe-cards-grid">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php foreach ($result as $row): ?>
                        <?php
                        $imageName = str_replace([' ', '&', '-'], '', $row['recipe_name']);
                        $imagePath = "cookbook_images/" . $imageName . "/";
                        $imageFileName = $row['id'] . "-" . $imageName . "-hero.webp";
                        $fullImagePath = $imagePath . $imageFileName;
                        ?>
                        <div class="recipe-card">
                            <a href="recipe.php?id=<?php echo htmlspecialchars($row['id']); ?>">
                                <img src="<?php echo htmlspecialchars($fullImagePath); ?>" alt="Recipe Image">
                                <h2><?php echo htmlspecialchars($row['recipe_name']); ?></h2>
                                <p><?php echo htmlspecialchars(substr($row['description'], 0, 70)) . "....."; ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No recipes found matching your filters.</p>
                <?php endif; ?>
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
            window.location.href = `allrecipes.php?cooktime=${encodeURIComponent(cooktime)}&mealtype=${encodeURIComponent(mealtype)}&cuisine=${encodeURIComponent(cuisine)}`;
        }
    </script>
</body>
</html>
