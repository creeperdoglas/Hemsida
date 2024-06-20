<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Build Your PC</title>
  <link rel="stylesheet" href="{{ url_for('static', filename='build_pc.css') }}">
</head>

<body>
  <h1>Build Your PC</h1>
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

  <script src="{{ url_for('static', filename='build_pc.js') }}"></script>
</body>

</html>