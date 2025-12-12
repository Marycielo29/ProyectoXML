<?php



$xml = simplexml_load_file('ies_db.xml') or die ('ERROR: NO SE CARGO EL XML. ESCRIBE CORRECTAMENTE EL NOMBRE DEL ARCHIVO');



//ACCEDER A LOS ELEMENTOS
//echo $xml->pe_1->nombre."<br>"; 
//echo $xml->pe_2->nombre;

foreach ($xml as $i_pe => $pe) {
    //imprimir los nombres de PE
    echo 'Codigo: '.$pe->codigo.'<br>';
    echo 'Tipo: '.$pe->tipo.'<br>';
    echo 'Nombre: '.$pe->nombre.'<br>';

    //planes estudio
    foreach ($pe->planes_estudio[0] as $i_ple => $plan) {
        echo '--'.$plan->nombre.'<br>';
        echo '--'.$plan->resolucion.'<br>';
        echo '--'.$plan->fecha_registro.'<br>';
    
        //modulos
        foreach ($plan->modulos_formativos[0] as $i_mod => $modulo) {
            echo '----'.$modulo->descripcion.'<br>';
         // echo '----'.$modulo->nro_modulo.'<br>';

            //periodos
            foreach ($modulo->periodos[0] as $i_per => $periodo) {
                echo '------'.$periodo->descripcion.'<br>';

                //unidades didacticas
                foreach ($periodo->unidades_didacticas[0] as $i_uds => $unidades) {
                    echo '--------'.$unidades->nombre.'<br>';
                    echo '--------'.$unidades->creditos_teorico.'<br>';
                    echo '--------'.$unidades->creditos_practico.'<br>';
                    echo '--------'.$unidades->tipo.'<br>';
                    echo '--------'.$unidades->horas_semanal.'<br>';
                    echo '--------'.$unidades->horas_semestral.'<br>';
                }
             }
        
         }
    }
}