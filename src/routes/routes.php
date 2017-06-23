<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// Get all locations
$app->get('/api/locations', function(Request $request, Response $response) {
	
	$sql = "SELECT * FROM locations";

	try {
		
		// Get DB object
		$db = new db();

		// Connect
		$db = $db->connect();

		$stmt = $db->query($sql);

		$locations = $stmt->fetchAll(PDO::FETCH_OBJ);

		$db = null;

		echo json_encode($locations);
	
	} catch(PDOException $e) {

		echo '{"error": { "text": '.$e->getMessage().'}';

	}

});

// Get single location
$app->get('/api/locations/{id}', function(Request $request, Response $response) {
	
	$id = $request->getAttribute('id');

	$sql = "SELECT * FROM locations WHERE id = $id";

	try {
		
		// Get DB object
		$db = new db();

		// Connect
		$db = $db->connect();

		$stmt = $db->query($sql);

		$location = $stmt->fetchAll(PDO::FETCH_OBJ);

		$db = null;

		echo json_encode($location);
	
	} catch(PDOException $e) {

		echo '{"error": { "text": '.$e->getMessage().'}';

	}

});


// Add location
$app->post('/api/locations/add', function(Request $request, Response $response) {
	
	$name = $request->getParam('name');
	$distance_1 = $request->getParam('distance_1');
	$distance_2 = $request->getParam('distance_2');
	$distance_3 = $request->getParam('distance_3');

	$sql = "INSERT INTO locations (time, name, distance_1, distance_2, distance_3) 
			VALUES (now(), :name, :distance_1, :distance_2, :distance_3)";

	try {
		
		// Get DB object
		$db = new db();

		// Connect
		$db = $db->connect();

		$stmt = $db->prepare($sql);

		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':distance_1', $distance_1);
		$stmt->bindParam(':distance_2', $distance_2);
		$stmt->bindParam(':distance_3', $distance_3);

		$stmt->execute();

		echo '{"notice": {"text": "Location Added"}}';

	} catch(PDOException $e) {

		echo '{"error": { "text": '.$e->getMessage().'}';

	}

});

// Update location
$app->put('/api/locations/update/{id}', function(Request $request, Response $response) {
	
	$id = $request->getAttribute('id');
	$name = $request->getParam('name');
	$distance_1 = $request->getParam('distance_1');
	$distance_2 = $request->getParam('distance_2');
	$distance_3 = $request->getParam('distance_3');

	$sql = "UPDATE locations SET
			name = :name,
			distance_1 = :distance_1,
			distance_2 = :distance_2,
			distance_3 = :distance_3
			WHERE id = $id";

	try {
		
		// Get DB object
		$db = new db();

		// Connect
		$db = $db->connect();

		$stmt = $db->prepare($sql);

		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':distance_1', $distance_1);
		$stmt->bindParam(':distance_2', $distance_2);
		$stmt->bindParam(':distance_3', $distance_3);

		$stmt->execute();

		echo '{"notice": {"text": "Location Updated"}}';

	} catch(PDOException $e) {

		echo '{"error": { "text": '.$e->getMessage().'}';

	}

});

// Delete location
$app->delete('/api/locations/delete/{id}', function(Request $request, Response $response) {
	
	$id = $request->getAttribute('id');

	$sql = "DELETE FROM locations WHERE id = $id";

	try {
		
		// Get DB object
		$db = new db();

		// Connect
		$db = $db->connect();

		$stmt = $db->prepare($sql);

		$stmt->execute();

		$db = null;

		echo '{"notice": {"text": "Location Deleted"}}';

	} catch(PDOException $e) {

		echo '{"error": { "text": '.$e->getMessage().'}';

	}

});