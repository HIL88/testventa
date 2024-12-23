<?php

/*if($_SESSION["perfil"] == "Especial"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}*/

$xml = ControladorVentas::ctrDescargarXML();

if($xml){

  rename($_GET["xml"].".xml", "xml/".$_GET["xml"].".xml");

  echo '<a class="btn btn-block btn-success abrirXML" archivo="xml/'.$_GET["xml"].'.xml" href="ventas">Se ha creado correctamente el archivo XML <span class="fa fa-times pull-right"></span></a>';

}

?>
<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar ventas
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar ventas</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <a href="crear-venta">

          <button class="btn btn-primary">
            
            Agregar venta

          </button>

        </a>

         <button type="button" class="btn btn-default pull-right" id="daterange-btn">
           
            <span>
              <i class="fa fa-calendar"></i> 

              <?php

                if(isset($_GET["fechaInicial"])){

                  echo $_GET["fechaInicial"]." - ".$_GET["fechaFinal"];
                
                }else{
                 
                  echo 'Rango de fecha';

                }

              ?>
            </span>

            <i class="fa fa-caret-down"></i>

         </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Código</th>
          <!-- <th>Cliente</th> -->
           <th>Descripción</th>
           <th>Vendedor</th>
           <th>Forma de pago</th>
           <th>Venta</th> 
           <th>Ganancia</th>
           <th>Fecha</th>
           
            <th>Acciones</th> 

         </tr> 

        </thead>

        <tbody>

        <?php

          if(isset($_GET["fechaInicial"])){

            $fechaInicial = $_GET["fechaInicial"];
            $fechaFinal = $_GET["fechaFinal"];

          }else{

            $fechaInicial = null;
            $fechaFinal = null;

          }

          $respuesta = ControladorVentas::ctrRangoFechasVentas($fechaInicial, $fechaFinal);

          foreach ($respuesta as $key => $value) {
           
           echo '<tr>

                    <td>'.($key+1).'</td>

                    <td>'.$value["codigo"].'</td>';

                    /*$itemCliente = "id";
                    $valorCliente = $value["id_cliente"];

                    $respuestaCliente = ControladorClientes::ctrMostrarClientes($itemCliente, $valorCliente);

                    echo '<td>'.$respuestaCliente["nombre"].'</td>';*/
                  
                    ////////////////////////////
                    //DESCRIPCION DEL PRODUCTO//
                    ////////////////////////////
                  
                    $item = "id";
                    $valor = $value["productos"];
                    $orden = "id";

                    // Decodificar el JSON
                    $productos = json_decode($valor, true);
                    
                    if (is_array($productos)) {
                      // Inicializamos una variable para almacenar las descripciones concatenadas
                      $descripcion_completa = '';
                  
                      // Recorremos el arreglo de productos
                      foreach ($productos as $index => $producto) {
                          // Acceder al campo "descripcion" de cada producto
                          $descripcion = $producto['descripcion'];
                          
                  
                          // Concatenar la descripción y agregar una coma si no es el último elemento
                          if ($index > 0) {
                              $descripcion_completa .= ', '; // Agregar coma si no es el primer producto
                          }
                              $descripcion_completa .= $descripcion;
                      }
                  
                      // Mostrar las descripciones concatenadas en una sola línea
                      echo '<td>'. $descripcion_completa .'</td>';
                    }else {
                      echo "Error al decodificar el JSON.";
                      }
 
                  $itemUsuario = "id";
                  $valorUsuario = $value["id_vendedor"];
                  
                  $respuestaUsuario = ControladorUsuarios::ctrMostrarUsuarios($itemUsuario, $valorUsuario);
                  // var_dump($respuestaUsuario);
                  echo '<td>'.$respuestaUsuario["nombre"].'</td>

                  <td>'.$value["metodo_pago"].'</td>
              
                  <!-- <td>Q '.number_format($value["neto"],2).'</td> -->
                
                  <td>Q '.number_format($value["total"],2).'</td>';
                    
                  ////////////////////////////
                  //CALCULO PARA LA GANANCIA//
                  ///////////////////////////

                  $item = "id";
                  $valor = $value["productos"]; // Aquí tienes el JSON
                  $orden = "id";
                  
                  // Decodificar el JSON
                  $productos = json_decode($valor, true);
                  
                  if (is_array($productos)) {
                      // Inicializamos variables para almacenar las sumas
                      $total_precio_compra = 0;
                      $total_venta = 0;

                      // Recorremos el arreglo de productos
                      foreach ($productos as $producto) {
                          // Multiplicar precio de compra por cantidad y sumarlo
                          $total_precio_compra += $producto['precio_compra'] * $producto['cantidad'];

                          // Multiplicar total de venta por cantidad y sumarlo
                          $total_venta += $producto['total'];
                      }

                      // Calcular la diferencia (ganancia)
                      $ganancia = $total_venta - $total_precio_compra;

                      echo '<td>Q '.number_format($ganancia,2)  . '</td>';
                  } else {
                      echo "Error al decodificar el JSON.";
                  }

                echo '
                  <td>'.$value["fecha"].'</td>
                       
                  <td>

                    <!-- <div class="btn-group">

                      <a class="btn btn-success" href="index.php?ruta=ventas&xml='.$value["codigo"].'">xml</a>
                        
                      <button class="btn btn-info btnImprimirFactura" codigoVenta="'.$value["codigo"].'">

                        <i class="fa fa-print"></i>

                      </button> -->'; 
               
                      if($_SESSION["perfil"] == "Administrador"|| $_SESSION["perfil"] == "Especial"){

                     // echo '<button class="btn btn-warning btnEditarVenta" idVenta="'.$value["id"].'"><i class="fa fa-pencil"></i></button>

                       echo '<button class="btn btn-danger btnEliminarVenta" idVenta="'.$value["id"].'"><i class="fa fa-times"></i></button>';
                  
                    }
               
                    echo '</div>  

                  </td>

                </tr>';
            }

        ?>
               
        </tbody>

       </table>

       <?php

      $eliminarVenta = new ControladorVentas();
      $eliminarVenta -> ctrEliminarVenta();

      ?>
   
      </div>

    </div>

  </section>

</div>




