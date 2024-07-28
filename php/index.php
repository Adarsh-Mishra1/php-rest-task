<?php
require 'db.php';
$action = $_GET['action'] ?? null;
$inputData = json_decode(file_get_contents('php://input'), true);

if ($action === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Action not specified']);
    exit;
}

$authToken = 'token24810';
$restrictedActions = ['create', 'update', 'delete', 'rate'];

if (in_array($action, $restrictedActions)) {
    $headers = apache_request_headers();

    if (!isset($headers['Authorization']) || $headers['Authorization'] !== "Bearer $authToken") {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized access']);
        exit;
    }
}

switch ($action) {
    case 'create':
        createRecipe($inputData);
        break;
    case 'update':
        $id = $_GET['id'] ?? null;
        if ($id === null) {
            http_response_code(400);
            echo json_encode(['error' => 'No recipe ID specified']);
            exit;
        }
        updateRecipe($id, $inputData);
        break;
    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id === null) {
            http_response_code(400);
            echo json_encode(['error' => 'No recipe ID specified']);
            exit;
        }
        deleteRecipe($id);
        break;
    case 'rate':
        $id = $_GET['id'] ?? null;
        if ($id === null || !isset($inputData['rating'])) {
            http_response_code(400);
            echo json_encode(['error' => 'No recipe ID or rating provided']);
            exit;
        }
        rateRecipe($id, $inputData['rating']);
        break;
    case 'list':
        listRecipes();
        break;
    case 'get':
        $id = $_GET['id'] ?? null;
        if ($id === null) {
            http_response_code(400);
            echo json_encode(['error' => 'No recipe ID specified']);
            exit;
        }
        getRecipe($id);
        break;
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}

$connection->close();

function createRecipe($data) {
    global $connection;
    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'No input data provided']);
        return;
    }

    $name = $data['name'] ?? '';
    $prepTime = $data['prep_time'] ?? 0;
    $difficulty = $data['difficulty'] ?? 0;
    $vegetarian = $data['vegetarian'] ?? 0;

    $query = "INSERT INTO recipes (name, prep_time, difficulty, vegetarian) VALUES ('$name', $prepTime, $difficulty, $vegetarian)";
    if ($connection->query($query) === TRUE) {
        http_response_code(201);
        echo json_encode(['id' => $connection->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => $connection->error]);
    }
}

function updateRecipe($id, $data) {
    global $connection;
    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'No input data provided']);
        return;
    }

    $name = $data['name'] ?? '';
    $prepTime = $data['prep_time'] ?? 0;
    $difficulty = $data['difficulty'] ?? 0;
    $vegetarian = $data['vegetarian'] ?? 0;

    $query = "UPDATE recipes SET name = '$name', prep_time = $prepTime, difficulty = $difficulty, vegetarian = $vegetarian WHERE id = $id";
    if ($connection->query($query) === TRUE) {
        if ($connection->affected_rows > 0) {
            http_response_code(200);
            echo json_encode(['status' => 'Recipe updated successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Recipe not found']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['error' => $connection->error]);
    }
}

function deleteRecipe($id) {
    global $connection;
    $query = "DELETE FROM recipes WHERE id = $id";
    if ($connection->query($query) === TRUE) {
        if ($connection->affected_rows > 0) {
            http_response_code(200);
            echo json_encode(['status' => 'Recipe deleted successfully']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Recipe not found']);
        }
    } else {
        http_response_code(500);
        echo json_encode(['error' => $connection->error]);
    }
}

function rateRecipe($id, $rating) {
    global $connection;
    $query = "INSERT INTO ratings (recipe_id, rating) VALUES ($id, $rating)";
    if ($connection->query($query) === TRUE) {
        http_response_code(201);
        echo json_encode(['status' => 'Recipe rated successfully.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Recipe not found']);
    }
}

function listRecipes() {
    global $connection;
    $query = "SELECT * FROM recipes";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        $recipes = [];
        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row;
        }
        http_response_code(200);
        echo json_encode($recipes);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'No recipes found']);
    }
}

function getRecipe($id) {
    global $connection;
    $query = "SELECT * FROM recipes WHERE id = $id";
    $result = $connection->query($query);

    if ($result->num_rows > 0) {
        $recipe = $result->fetch_assoc();
        http_response_code(200);
        echo json_encode($recipe);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Recipe not found']);
    }
}
?>