<?php
require_once("Pizza.php");
require_once("ManejadorArchivos.php");

class PizzaCarga
{
	public static function altaPizza($tipo, $sabor, $cantidad, $precio, $foto)
	{
		if(Pizza::validarTipo($tipo) != 1)
		{
			echo "<br>Tipo de Pizza incorrecto. Ingresó $tipo pero debe ser MOLDE o PIEDRA";
		}
		else if(Pizza::validarSabor($sabor) != 1)
		{
			echo "<br>Sabor de Pizza incorrecto. Ingresó $sabor pero debe ser MUZZA, o JAMON, o ESPECIAL";
		}
		else
		{
			$arrayPizzas = Pizza::leerPizzas("archivos/Pizza.txt");
			$id = Pizza::siguienteId($arrayPizzas);
			$pizza = new Pizza($id, $tipo, $sabor, $cantidad, $precio);
			PizzaCarga::guardarAlta($pizza, $foto);
			echo "<br>Se dio de alta la Pizza $id - $tipo - $sabor - $cantidad - $precio<br>";
		}
	}

	public static function guardarAlta($pizza, $foto)
	{
		$arrayPizzas = Pizza::leerPizzas("archivos/Pizza.txt");
		$hayStock = false;
		
		$archivo = fopen("archivos/Pizza.txt", "w");

		foreach ($arrayPizzas as $unaPizza)
		{
			if($pizza->esIgual($unaPizza)) //Hay en stock, reemplazo precio y acumulo stock.
			{
				$hayStock = true;
				$unaPizza->setPrecio($pizza->getPrecio());
				$unaPizza->setCantidad($unaPizza->getCantidad() + $pizza->getCantidad());
			}

			$linea = json_encode($unaPizza->toArray());
			fputs($archivo, $linea . "\n");
		}

		if(!$hayStock) //No hay en stock la pizza, agrego una nueva línea.
		{
			$linea = json_encode($pizza->toArray());
			fputs($archivo, $linea . "\n");

			if ($foto != null)
			{
                ManejadorArchivos::cargarImagenPorNombre($foto, ($pizza->getTipo() . "_" . $pizza->getSabor()), "./ImagenesDePizzas/");
			}
		}

		fclose($archivo);
	}

	/*public static function ventaPizza($tipo, $sabor)
	{
		$pizzaValida = PizzaCarga::obtenerPizza($tipo, $sabor);

		if (is_null($pizzaValida))
		{
			echo "<br>El helado ingresado no existe<br>";
		}
		else
		{
			$pizzaValida->guardarVenta();
			echo "<br>Bienvenido " . $pizzaValida->getAlias() . "<br>";
		}
	}

	public function guardarVenta()
	{
		$arrayAlta = $this->toArray();
		$arrayVenta = array();
		$arrayVenta["tipo"] = $arrayAlta["tipo"];
		$arrayVenta["stock"] = $arrayAlta["stock"];
		$arrayVenta["precio"] = date("d/m/Y H:i:s");

		$linea = implode(",", $arrayVenta);
		$archivo = fopen("archivos/log.csv", "a");
		fputs($archivo, $linea . "\n");
		fclose($archivo);
	}

	public static function obtenerPizza($tipo, $sabor)
	{
		$pizzaValida = null;

		foreach (Pizza::leerPizzas("archivos/Pizza.txt") as $pizza)
		{
			if ($pizza->getTipo() === $tipo && $pizza->getSabor() === $sabor)
			{
				$pizzaValida = $pizza;
				break;
			}
		}

		return $pizzaValida;
	}*/
}
?>