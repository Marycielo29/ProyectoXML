<?php

$conexion = new mysqli("localhost", "root", "root", "sigi");
if ($conexion->connect_errno) {
    die("Fallo al conectar a MySQL: (" . $conexion->connect_errno . ") " . $conexion->connect_error);
}

$xml = simplexml_load_file('ies_db.xml') or die('ERROR: NO SE CARGO EL XML. ESCRIBE CORRECTAMENTE EL NOMBRE DEL ARCHIVO');

// RECORRER PROGRAMAS DE ESTUDIO
foreach ($xml as $i_pe => $pe) {

    echo 'Codigo: ' . $pe->codigo . '<br>';
    echo 'Tipo: ' . $pe->tipo . '<br>';
    echo 'Nombre: ' . $pe->nombre . '<br>';

    // INSERTAR PROGRAMA
    $consulta = "INSERT INTO sigi_programa_estudios (codigo, tipo, nombre) VALUES ('{$pe->codigo}', '{$pe->tipo}', '{$pe->nombre}')";
    $conexion->query($consulta);
    $id_pe = $conexion->insert_id;

    // PLANES DE ESTUDIO
    foreach ($pe->planes_estudio[0] as $i_ple => $plan) {

        echo '--' . $plan->nombre . '<br>';
        echo '--' . $plan->resolucion . '<br>';
        echo '--' . $plan->fecha_registro . '<br>';

        $c_planes = "INSERT INTO sigi_planes_estudio (id_programa_estudios, nombre, resolucion, fecha_registro, perfil_egresado) VALUES ('$id_pe', '{$plan->nombre}', '{$plan->resolucion}', '{$plan->fecha_registro}', '')";
        $conexion->query($c_planes);
        $id_plan = $conexion->insert_id;

        // MÓDULOS FORMATIVOS
        foreach ($plan->modulos_formativos[0] as $i_mod => $modulo) {

            echo '----' . $modulo->descripcion . '<br>';
            echo '----' . $modulo->nro_modulo . '<br>';

            $c_modulo = "INSERT INTO sigi_modulo_formativo (descripcion, nro_modulo, id_plan_estudio) VALUES ('{$modulo->descripcion}', '{$modulo->nro_modulo}', '$id_plan')";
            $conexion->query($c_modulo);
            $id_modulo = $conexion->insert_id;

            // PERIODOS (SEMESTRE)
            foreach ($modulo->periodos[0] as $i_per => $periodo) {

                echo '------' . $periodo->descripcion . '<br>';

                $c_semestre = "INSERT INTO sigi_semestre
                                (descripcion, id_modulo_formativo)
                               VALUES
                                ('{$periodo->descripcion}', '$id_modulo')";
                $conexion->query($c_semestre);
                $id_semestre = $conexion->insert_id;

                // UNIDADES DIDÁCTICAS
                foreach ($periodo->unidades_didacticas[0] as $i_uds => $unidades) {

                    echo '--------' . $unidades->nombre . '<br>';
                    echo '--------' . $unidades->creditos_teorico . '<br>';
                    echo '--------' . $unidades->creditos_practico . '<br>';
                    echo '--------' . $unidades->tipo . '<br>';
                    echo '--------' . $unidades->horas_semanal . '<br>';
                    echo '--------' . $unidades->horas_semestral . '<br>';

                    $c_ud = "INSERT INTO sigi_unidad_didactica (nombre, id_semestre, creditos_teorico, creditos_practico, tipo, orden) VALUES ('{$unidades->nombre}', '$id_semestre', '{$unidades->creditos_teorico}', '{$unidades->creditos_practico}', '{$unidades->tipo}', 0)";
                    $conexion->query($c_ud);
                }
            }
        }
    }

   
}


?>
