<?php
	date_default_timezone_set("America/Argentina/Buenos_Aires");

	$metodo = $_SERVER["REQUEST_METHOD"];

	switch ($metodo)
	{
		case "GET":
			switch ($_GET["accion"])
			{
				case "carga":
					require_once("clases/PizzaCarga.php");

					PizzaCarga::altaPizza($_GET["tipo"], $_GET["sabor"], $_GET["cantidad"], $_GET["precio"]);
					break;
			}
			break;
		case "POST":
			switch ($_POST["accion"])
			{
				case "carga":
					require_once("clases/PizzaCarga.php");

					PizzaCarga::altaPizza($_POST["tipo"], $_POST["sabor"], $_POST["cantidad"], $_POST["precio"], $_FILES["foto"]);
					break;
				case "consulta":
					require_once("clases/PizzaConsultar.php");

					PizzaConsultar::consultaPizza($_POST["tipo"], $_POST["sabor"]);
					break;
				case "venta":
					require_once("clases/AltaVenta.php");

					AltaVenta::altaPizza($_POST["tipo"], $_POST["sabor"], $_POST["cantidad"], $_POST["precio"], $_POST["email"], $_FILES["foto"]);
					break;
				default:
					# code...
					break;
			}
			break;
		case "PUT":
			parse_str(file_get_contents("php://input"), $_PUT);

			switch ($_PUT["accion"])
			{
				case "carga":
					require_once("clases/PizzaCargaPlus.php");

					PizzaCargaPlus::altaPizza($_PUT["tipo"], $_PUT["sabor"], $_PUT["cantidad"], $_PUT["precio"]);
					break;
			}
			break;
		default:
			echo "Se invoco al metodo HTTP: $metodo";
			break;
	}
?>