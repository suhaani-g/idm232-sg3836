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
    if ($result->num_rows == 0) {
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
    <link rel="stylesheet" href="css/styles.css">
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

    <header class="hero-section">
        <div class="hero-content">
            <h1>Discover Delicious Recipes</h1>
            <p>Discover dishes in our collection of recipes.</p>
            <div class="search-bar">
                <form action="no-results.html" method="get"> 
                    <input type="text" placeholder="Search recipes..." aria-label="Search" name="query">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>
        <div class="hero-img">
            <img src="baking.png" alt="Baking Image">
        </div>
    </header>

    <main>
        <section class="popular-dishes">
            <h1>Popular Dishes</h1>
        </section>
        
        <section class="recipe-cards">
            <?php if (!empty($result)):
                foreach ($result as $row):
                    $imageName = str_replace([" ", "&", "-"], "", $row['recipe_name']);
                    $imagePath = "cookbook_images/" . $imageName . "/";
                    $imageName = $imagePath . $row['id'] . "-" . $imageName;

                    echo "<div class=\"recipe-card\">";
                    echo "<a href=\"recipe.php?id=" . htmlspecialchars($row['id']) . "\">";
                    echo "<img src=\"" . $imageName . "-hero.webp\" alt=\"Recipe Image\">";
                    echo "<h2>" . htmlspecialchars($row['recipe_name']) . "</h2>";
                    echo "<p>" . htmlspecialchars(substr($row['description'], 0, 70)) . ".....</p>";
                    echo "</a>";
                    echo "</div>\n";
                endforeach;
            endif; ?>   
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Online Cookbook. All rights reserved.</p>
    </footer>

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
</body>
</html>
