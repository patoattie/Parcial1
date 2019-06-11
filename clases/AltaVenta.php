<?php
require_once("Pizza.php");
require_once("ManejadorArchivos.php");

class AltaVenta
{
	public static function altaPizza($tipo, $sabor, $cantidad, $precio, $mail, $foto)
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
			if ($foto != null)
			{
				$usuario = substr($mail, 0, strpos($mail, "@"));
                ManejadorArchivos::cargarImagenPorNombre($foto, (($tipo . "_" . $sabor . "_" . $usuario . "_" .  date("YmdHis"))), "./ImagenesDeLaVenta/");
			}
			
			$id = Pizza::traerId(Pizza::leerPizzas("archivos/Pizza.txt"), $tipo, $sabor);

			if($id == 0)
			{
				echo "<br>La pizza $tipo - $sabor no existe en stock<br>";
			}
			else
			{
				$pizza = new Pizza($id, $tipo, $sabor, $cantidad, $precio);
				$venta = AltaVenta::guardarVenta($pizza, $mail);
				if($venta == -1)
				{
					echo "<br>Se registró la venta de la Pizza $tipo - $sabor - $cantidad - $precio para el cliente $mail<br>";
				}
				else
				{
					echo "<br>El stock disponible ($venta) no es suficiente para registrar la venta $tipo - $sabor - $cantidad - $precio<br>";
				}
			}
		}
	}

	public static function guardarVenta($pizza, $mail)
	{
		$retorno = -2;
		$arrayPizzas = Pizza::leerPizzas("archivos/Pizza.txt");

		if(Pizza::existePizza($arrayPizzas, $pizza->getTipo(), $pizza->getSabor()))
		{
			$retorno = -1;
			$stockRemanente = Pizza::hayStockRemanente($arrayPizzas, $pizza->getTipo(), $pizza->getSabor(), $pizza->getCantidad());

			if($stockRemanente >= 0)
			{
				$archivo = fopen("archivos/Venta.txt", "a");
				$arrayVenta = $pizza->toArray();
				$arrayVenta["email"] = $mail;
				$linea = json_encode($arrayVenta);
				fputs($archivo, $linea . "\n");
				fclose($archivo);

				$archivo = fopen("archivos/Pizza.txt", "w");
				foreach ($arrayPizzas as $unaPizza)
				{
					if($unaPizza->esIgual($pizza))
					{
						//Guardo en el stock actual ($unaPizza) el remanente luego de la venta
						$unaPizza->setCantidad($stockRemanente);
						//$unaPizza->setCantidad($unaPizza->getCantidad() - $pizza->getCantidad());
					}
					$linea = json_encode($unaPizza->toArray());
					fputs($archivo, $linea . "\n");
				}	
				fclose($archivo);
			}
			else
			{
				//Devuelvo el stock del producto si no alcanza para la venta
				$retorno = $pizza->getCantidad() + $stockRemanente;
			}
		}

		return $retorno;
	}

	/*public static function ventaPizza($tipo, $sabor)
	{
		$heladoValido = AltaVenta::obtenerPizza($tipo, $sabor);

		if (is_null($heladoValido))
		{
			echo "<br>El helado ingresado no existe<br>";
		}
		else
		{
			$heladoValido->guardarVenta();
			echo "<br>Bienvenido " . $heladoValido->getAlias() . "<br>";
		}
	}

	public static function obtenerPizza($tipo, $sabor)
	{
		$heladoValido = null;

		foreach (Pizza::leerPizzas("archivos/Pizza.txt") as $helado)
		{
			if ($helado->getTipo() === $tipo && $helado->getSabor() === $sabor)
			{
				$heladoValido = $helado;
				break;
			}
		}

		return $heladoValido;
	}*/
}
?>