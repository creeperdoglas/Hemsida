<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pc_designer";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['component_type'])) {
  $component_type = $_GET['component_type'];
  $sql = "SELECT * FROM $component_type WHERE 1=1";

  // Apply filters if present
  if (isset($_GET['brand']) && !empty($_GET['brand'])) {
    $brand = $conn->real_escape_string($_GET['brand']);
    $sql .= " AND brand='$brand'";
  }
  if (isset($_GET['socket']) && !empty($_GET['socket']) && ($component_type == 'cpu' || $component_type == 'motherboard')) {
    $socket = $conn->real_escape_string($_GET['socket']);
    $sql .= " AND socket_type='$socket'";
  }
  if (isset($_GET['power']) && !empty($_GET['power']) && $component_type == 'psu') {
    $power = $conn->real_escape_string($_GET['power']);
    $sql .= " AND wattage='$power'";
  }
  if (isset($_GET['tower']) && !empty($_GET['tower']) && $component_type == 'chassis') {
    $tower = $conn->real_escape_string($_GET['tower']);
    $sql .= " AND form_factor='$tower'";
  }

  $result = $conn->query($sql);

  $components = array();
  while ($row = $result->fetch_assoc()) {
    $components[] = $row;
  }

  echo json_encode($components);
  exit();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Build Your PC</title>
  <link rel="stylesheet" href="build_pc.css">
</head>

<body>
  <h1>Build Your PC</h1>
  <form id="filters-form">
    <label for="brand-filter">Brand</label>
    <select id="brand-filter" name="brand">
      <option value="">All Brands</option>
      <!-- Add specific brands as needed -->
      <option value="Intel">Intel</option>
      <option value="AMD">AMD</option>
      <option value="NVIDIA">NVIDIA</option>
      <option value="ASUS">ASUS</option>
      <option value="MSI">MSI</option>
    </select>

    <label for="socket-filter">Socket</label>
    <select id="socket-filter" name="socket">
      <option value="">All Sockets</option>
      <option value="AM4">AM4</option>
      <option value="LGA1200">LGA1200</option>
    </select>

    <label for="power-filter">Power</label>
    <select id="power-filter" name="power">
      <option value="">All Power Ratings</option>
      <option value="500W">500W</option>
      <option value="750W">750W</option>
    </select>

    <label for="tower-filter">Tower</label>
    <select id="tower-filter" name="tower">
      <option value="">All Towers</option>
      <option value="Mid Tower">Mid Tower</option>
      <option value="Full Tower">Full Tower</option>
    </select>

    <button type="button" id="apply-filters">Apply Filters</button>
  </form>

  <form id="build-pc-form">
    <label for="motherboard">Motherboard</label>
    <select id="motherboard" name="motherboard"></select>

    <label for="cpu">CPU</label>
    <select id="cpu" name="cpu"></select>

    <label for="gpu">GPU</label>
    <select id="gpu" name="gpu"></select>

    <label for="psu">PSU</label>
    <select id="psu" name="psu"></select>

    <label for="chassis">Chassis</label>
    <select id="chassis" name="chassis"></select>

    <label for="fans">Fans</label>
    <select id="fans" name="fans"></select>

    <label for="storage">Storage</label>
    <select id="storage" name="storage"></select>

    <label for="ram">RAM</label>
    <select id="ram" name="ram"></select>

    <label for="cpu_cooler">CPU Cooler</label>
    <select id="cpu_cooler" name="cpu_cooler"></select>

    <button type="submit">Save Build</button>
  </form>

  <div id="product-info" class="product-info"></div>

  <script src="build_pc.js"></script>
</body>

</html>