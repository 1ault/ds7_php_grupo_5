<?php

class Procesos{

    // Tiempo completo
    public function calcularSalario($colaborador)
    {
        return $colaborador->salarioBase;
    }

    // Por hora: tarifa * horas trabajadas
    public function calcularSalarioHora($colaborador)
    {
        return $colaborador->tarifa * $colaborador->horas;
    }

    // Por comisión: salario base + comisión
    public function calcularComision($colaborador)
    {
        return $colaborador->salarioBase + $colaborador->comision;
    }
}