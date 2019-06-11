<?php
require_once("Pizza.php");

class PizzaConsultar
{
    public static function consultaPizza($tipo, $sabor)
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
            $otroTipo = "";
            $otroSabor1 = "";
            $otroSabor2 = "";
    
            if(strtolower($tipo) == "molde")
            {
                $otroTipo = "piedra";
            }
            else
            {
                $otroTipo = "molde";
            }

            if(strtolower($sabor) == "muzza")
            {
                $otroSabor1 = "jamon";
                $otroSabor2 = "especial";
            }
            else if(strtolower($sabor) == "jamon")
            {
                $otroSabor1 = "muzza";
                $otroSabor2 = "especial";
            }
            else
            {
                $otroSabor1 = "muzza";
                $otroSabor2 = "jamon";
            }
    
            if(!Pizza::existePizza($arrayPizzas, $tipo, $sabor))
            {
                if(!Pizza::existePizza($arrayPizzas, $otroTipo, $sabor))
                {
                    if(Pizza::existePizza($arrayPizzas, $tipo, $otroSabor1))
                    {
                        echo "<br>NO HAY $tipo - $sabor PERO SI HAY $tipo - $otroSabor1<br>";
                    }
                    else if(Pizza::existePizza($arrayPizzas, $tipo, $otroSabor2))
                    {
                        echo "<br>NO HAY $tipo - $sabor PERO SI HAY $tipo - $otroSabor2<br>";
                    }
                    else
                    {
                        echo "<br>NO HAY $tipo - $sabor<br>";
                    }
                }
                else
                {
                    echo "<br>NO HAY $tipo - $sabor PERO SI HAY $otroTipo - $sabor<br>";
                }
            }
            else
            {
                echo "<br>SI HAY $tipo - $sabor<br>";
            }
        }
    }
}
?>