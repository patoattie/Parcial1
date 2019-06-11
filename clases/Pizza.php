<?php

class Pizza
{
	private $id;
	private $tipo;
	private $sabor;
	private $cantidad;
	private $precio;

	public function __construct($id, $tipo, $sabor, $cantidad, $precio)
	{
		$this->id = $id;
		$this->tipo = $tipo;
		$this->sabor = $sabor;
		$this->cantidad = $cantidad;
		$this->precio = $precio;
	}

	public static function leerPizzas($archivoTxt)
	{
		$archivo = fopen($archivoTxt, "r");
		$arrayPizzas = array();
		$arrayDatos = array();
		$linea = "";

		while (!feof($archivo))
		{
			$linea = fgets($archivo);

			if ((string)$linea != "") //Evito las lineas vacias
			{
				$arrayDatos = json_decode($linea, true); //El segundo parametro en true para que trate la salida como array.
				$pizza = new Pizza($arrayDatos["id"], $arrayDatos["tipo"], $arrayDatos["sabor"], $arrayDatos["cantidad"], $arrayDatos["precio"]);
				array_push($arrayPizzas, $pizza);
			}
		}

		fclose($archivo);
		return $arrayPizzas;
	}

	public static function siguienteId($arrayPizzas)
	{
		$proximoId = 0;
		if (isset($arrayPizzas))
		{
			foreach ($arrayPizzas as $pizza)
			{
				if ($pizza->id > $proximoId)
				{
					$proximoId = $pizza->id;
				}
			}
		}

		return $proximoId + 1;
	}

	public function toArray()
	{
		$retorno = array();

		$retorno["id"] = $this->id;
		$retorno["tipo"] = trim($this->tipo);
		$retorno["sabor"] = trim($this->sabor);
		$retorno["cantidad"] = $this->cantidad;
		$retorno["precio"] = $this->precio;

		return $retorno;
	}

	public function esIgual($pizza)
	{
		if(strtoupper($this->tipo) == strtoupper($pizza->tipo) && strtoupper($this->sabor) == strtoupper($pizza->sabor))
		{
			$esIgual = true;
		}
		else
		{
			$esIgual = false;
		}

		return $esIgual;
	}

	public function getTipo()
	{
		return $this->tipo;
	}

	public static function validarTipo($tipo)
	{
		$retorno = 0;

		if(strtolower($tipo) == "molde" || strtolower($tipo) == "piedra")
		{
			$retorno = 1;
		}

		return $retorno;
	}

	public static function validarSabor($sabor)
	{
		$retorno = 0;

		if(strtolower($sabor) == "muzza" || strtolower($sabor) == "jamon" || strtolower($sabor) == "especial")
		{
			$retorno = 1;
		}

		return $retorno;
	}

	public static function hayStockRemanente($arrayPizzas, $tipo, $sabor, $cantidad)
	{
		$hayStock = -1;

		foreach ($arrayPizzas as $pizza)
		{
			if(strtoupper($pizza->tipo) == strtoupper($tipo) && strtoupper($pizza->sabor) == strtoupper($sabor))
			{
				$hayStock = $pizza->cantidad - $cantidad;
				break;
			}
		}

		return $hayStock;
	}

	public static function existePizza($arrayPizzas, $tipo, $sabor)
	{
		$existe = false;

		foreach ($arrayPizzas as $pizza)
		{
			if(strtoupper($pizza->tipo) == strtoupper($tipo) && strtoupper($pizza->sabor) == strtoupper($sabor))
			{
				$existe = true;
				break;
			}
		}

		return $existe;
	}

	public static function traerId($arrayPizzas, $tipo, $sabor)
	{
		$id = 0;

		foreach ($arrayPizzas as $pizza)
		{
			if(strtoupper($pizza->tipo) == strtoupper($tipo) && strtoupper($pizza->sabor) == strtoupper($sabor))
			{
				$id = $pizza->id;
				break;
			}
		}

		return $id;
	}

	public function setTipo($tipo)
	{
		if(Pizza::validarTipo($tipo) == 1)
		{
			$this->tipo = strtoupper($tipo);
		}
	}

	public function getSabor()
	{
		return $this->sabor;
	}

	public function setSabor($sabor)
	{
		if(Pizza::validarSabor($sabor) == 1)
		{
			$this->sabor = $sabor;
		}
	}

	public function getCantidad()
	{
		return $this->cantidad;
	}

	public function setCantidad($cantidad)
	{
		$this->cantidad = $cantidad;
	}

	public function getPrecio()
	{
		return $this->precio;
	}

	public function setPrecio($precio)
	{
		$this->precio = $precio;
	}
}

?>