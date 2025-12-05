<?php

$conexion = new mysqli("localhost", "root", "root", "sigi_huanta");
if ($conexion->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $conexion->connect_errno . ") " . $conexion->connect_error;
}

$xml = new DOMDocument('1.0', 'UTF-8');
$xml->formatOutput = true;

$et1 = $xml->createElement('programas_estudio');
$xml->appendChild($et1);

//CONSULTAS
$consulta = "SELECT * FROM sigi_programa_estudios";
$resultado = $conexion->query($consulta);
while($pe = mysqli_fetch_assoc($resultado)) { 
    echo $pe ['nombre']."<br>";
    //generar las etiquetas de programas
    $num_pe = $xml->createElement('pe_'.$pe['id']); //  numeracion de `programas de estudio
    $codigo_pe = $xml->createElement('codigo', $pe['codigo']); // crear
    $tipo_pe = $xml->createElement('tipo', $pe['tipo']);
    $nombre_pe = $xml->createElement('nombre', $pe['nombre']);
    $num_pe->appendChild($codigo_pe);
    $num_pe->appendChild($tipo_pe);
    $num_pe->appendChild($nombre_pe);
    //planes de estudios
    $et_plan = $xml->createElement('planes_estudio');
    $consulta_plan = "SELECT * FROM sigi_planes_estudio WHERE id_programa_estudios=".$pe['id'];
    $resultado_plan = $conexion->query($consulta_plan);
    while ($plan = mysqli_fetch_assoc($resultado_plan)) {
         echo $plan ['nombre']."<br>";
         //generar las etiquetas de planes
         $num_pe = $xml->createElement('resutalado_plan_'.$plan['id']);
         $programa_plan = $xml->createElement('id_programa_estudios', $plan['id_programa_estudios']);
         $num_pe->appendChild($programa_plan);
         $nombre_plan = $xml->createElement('nombre', $plan['nombre']);
         $num_pe->appendChild($nombre_plan);
         $resolucion_plan = $xml->createElement('resolucion', $plan['resolucion']);
         $num_pe->appendChild($resolucion_plan);
         $fecha_plan = $xml->createElement('fecha_resgitro', $plan['fecha_registro']);
         $num_pe->appendChild($fecha_plan);
         
         //modulos
         $et_modulos = $xml->createElement('modulos');
         $consulta_modulos = "SELECT * FROM sigi_modulo_formativo WHERE id_plan_estudio=".$plan['id'];
         $resultado_modulos = $conexion->query($consulta_modulos);
         while ($modulo = mysqli_fetch_assoc($resultado_modulos)) {
            echo $modulo ['descripcion']."<br>";
            //generar las etiquetas de planes
            $num_pe = $xml->createElement('resultado_modulos'.$modulo['id']);
            $descripcion_modulo = $xml->createElement('descripcion', $modulo['descripcion']);
            $num_pe->appendChild($descripcion_modulo);
            $numero = $xml->createElement('nro_modulo', $modulo['nro_modulo']);
            $num_pe->appendChild($numero);
            $plan = $xml->createElement('id_plan_estudio', $modulo['id_plan_estudio']);
            $num_pe->appendChild($plan);

            //semestre
            $et_semestres = $xml->createElement('modulos');
            $consulta_semestres = "SELECT * FROM sigi_semestre WHERE id_modulo_formativo=".$pe['id'];
            $resultado_semestres = $conexion->query($consulta_semestres);
            while ($semestre = mysqli_fetch_assoc($resultado_semestres)) {
                echo $semestre ['descripcion']."<br>";
                $num_pe = $xml->createElement('resultado_semestres'.$semestre['id']);
                $descripcion_semestre = $xml->createElement('descripcion', $semestre['descripcion']);
                $num_pe->appendChild($descripcion_semestre);
                $modulo_formativo = $xml->createElement('id_modulo_formativo', $semestre['id_modulo_formativo']);
                $num_pe->appendChild($modulo_formativo);

                //unidades didacticas
                $et_unidades = $xml->createElement('modulos');
                $consulta_unidades = "SELECT * FROM sigi_unidad_didactica WHERE id_semestre=".$pe['id'];
                $resultado_unidades = $conexion->query($consulta_unidades);
                while ($unidades = mysqli_fetch_assoc($resultado_unidades)) {
                    echo $unidades ['nombre']."<br>";
                    $num_pe = $xml->createElement('resultado_unidades'.$unidades['id']);
                }
            }
         }
    }

    $num_pe->appendChild($et_plan);
    $et1->appendChild($num_pe);

}

$archivo = "ies_db.xml";
$xml->save($archivo);
?>