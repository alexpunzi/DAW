<?php

function obtenerPaises(): array 
{
    try{
        $mysqli = conectar_DB(); 
        $sql = "SELECT IdPais, NomPais FROM Paises ORDER BY NomPais ASC"; 
        $resultado = $mysqli->query($sql); 

        $paises = []; 
        while($fila = $resultado->fetch_assoc()){
            $paises[] = [
                'id'    => (int)$fila['IdPais'], 
                'nombre'=> $fila['NomPais'],
            ];
        }
        $resultado->free(); 
        return $paises; 
    }catch(Throwable $e){
        error_log('Error en obtenerPaises: ' . $e->getMessage()); 
        return []; 
    }finally{
        if (isset($mysqli) && $mysqli instanceof mysqli) {
            $mysqli->close();
        }
    }
}