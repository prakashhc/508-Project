<html>
<head>
<title>HR database - D3.js example</title>
<?php require_once('header.php'); ?>
<!-- My JS libraries -->
<script src="https://d3js.org/d3.v5.min.js"></script> <!-- OLD v5 version of D3, use v7 newest version -->
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>
<script src="js/d3-example.js"></script>
<link rel="stylesheet" href="css/d3-example.css">

</head>

<?php require_once('connection.php'); ?>

<body>

<div class="container-fluid mt-3 mb-3">
	<svg id="container"></svg>
</div>

</body>
</html>