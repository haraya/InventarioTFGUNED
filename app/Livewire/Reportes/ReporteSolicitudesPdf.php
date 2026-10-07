<?php

namespace App\Livewire\Reportes;

use Livewire\Component;
use TCPDF;
use App\Models\User;
use App\Models\Solicitud;

class ReporteSolicitudesPdf extends Component
{
    public $btn_reporte=false;

   

    public function exportar()
    {
        $solicitudes = Solicitud::with(['user', 'equipo.laboratorio', 'equipo.responsable'])->get();
        $contador_solicitudes = $solicitudes->count();

        if ($contador_solicitudes == 0) {
            session()->flash('error', 'No hay solicitudes para generar el reporte');
        } else {
            $pdf = new TCPDF();
            
            // Configuración del documento
            $pdf->SetCreator('Sistema de Inventario');
            $pdf->SetAuthor('Sistema de Inventario');
            $pdf->SetTitle('Reporte de Solicitudes de Equipos');
            
            // Configuración de márgenes
            $pdf->SetMargins(15, 15, 15);
            
            // Configuración de fuente
            $pdf->SetFont('helvetica', '', 10);
            
            // Agregar primera página
            $pdf->AddPage();
            
            // Título principal
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, 'REPORTE DE SOLICITUDES DE EQUIPOS', 0, 1, 'C');
            $pdf->Ln(5);
            
            // Subtítulo con fecha
            $pdf->SetFont('helvetica', 'I', 12);
            $pdf->Cell(0, 10, 'Generado el: ' . now()->format('d-m-Y'), 0, 1, 'C');
            $pdf->Ln(10);
            
            // Contador de solicitudes
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 10, 'Total de solicitudes: ' . $contador_solicitudes, 0, 1, 'L');
            $pdf->Ln(5);
            
            // Restaurar fuente para el contenido
            $pdf->SetFont('helvetica', '', 10);
            
            // Contador para la numeración
            $contador = 1;
            
            foreach ($solicitudes as $solicitud) {
                // Verificar si necesitamos una nueva página
                if ($pdf->GetY() > 250) {
                    $pdf->AddPage();
                }
                
                // Encabezado de la solicitud
                $pdf->SetFillColor(240, 240, 240);
                $pdf->SetFont('helvetica', 'B', 12);
                $pdf->Cell(0, 10, 'Solicitud #' . $solicitud->id, 0, 1, 'L', true);
                
                // Restaurar fuente para los detalles
                $pdf->SetFont('helvetica', '', 10);
                
                // Información del equipo
                $pdf->Ln(2);
                $pdf->SetFont('helvetica', 'B', 11);
                $pdf->Cell(0, 7, 'INFORMACIÓN DEL EQUIPO SOLICITADO', 0, 1);
                $pdf->SetFont('helvetica', '', 10);
                
                $pdf->Cell(40, 7, 'Número de activo:', 0, 0);
                $pdf->Cell(0, 7, $solicitud->equipo->numero_activo, 0, 1);
                
                $pdf->Cell(40, 7, 'Nombre:', 0, 0);
                $pdf->Cell(0, 7, $solicitud->equipo->nombre, 0, 1);
                
                $pdf->Cell(40, 7, 'Marca:', 0, 0);
                $pdf->Cell(0, 7, $solicitud->equipo->marca, 0, 1);
                
                $pdf->Cell(40, 7, 'Modelo:', 0, 0);
                $pdf->Cell(0, 7, $solicitud->equipo->modelo, 0, 1);
                
                $pdf->Cell(40, 7, 'Responsable:', 0, 0);
                $pdf->Cell(0, 7, optional($solicitud->equipo->responsable)->name ?? 'No asignado', 0, 1);
                
                $pdf->Cell(40, 7, 'Laboratorio:', 0, 0);
                $pdf->Cell(0, 7, optional($solicitud->equipo->laboratorio)->nombre ?? 'No asignado', 0, 1);
                
                // Información de la solicitud
                $pdf->Ln(2);
                $pdf->SetFont('helvetica', 'B', 11);
                $pdf->Cell(0, 7, 'INFORMACIÓN DE LA SOLICITUD', 0, 1);
                $pdf->SetFont('helvetica', '', 10);
                
                $pdf->Cell(40, 7, 'Solicitante:', 0, 0);
                $pdf->Cell(0, 7, optional($solicitud->user)->name, 0, 1);
                
                $pdf->Cell(40, 7, 'Fecha solicitud:', 0, 0);
                $pdf->Cell(0, 7, $solicitud->fecha_solicitud, 0, 1);
                
                $pdf->Cell(40, 7, 'Fecha devolución:', 0, 0);
                $pdf->Cell(0, 7, $solicitud->fecha_aproximada_devolucion, 0, 1);
                
                $pdf->Cell(40, 7, 'Devolución real:', 0, 0);
                $pdf->Cell(0, 7, $solicitud->fecha_exacta_devolucion ?? 'Pendiente', 0, 1);
                
                $pdf->Cell(40, 7, 'Estado:', 0, 0);
                $pdf->Cell(0, 7, $solicitud->estado_solicitud, 0, 1);
                
                $pdf->Cell(40, 7, 'Fecha creación:', 0, 0);
                $pdf->Cell(0, 7, $solicitud->created_at->format('d-m-Y'), 0, 1);
                
                // Línea separadora
                $pdf->Ln(5);
                $pdf->Line($pdf->GetX(), $pdf->GetY(), $pdf->GetX() + 180, $pdf->GetY());
                $pdf->Ln(5);
                
                $contador++;
            }
            
            // Pie de página con numeración
            $pdf->SetY(-15);
            $pdf->SetFont('helvetica', 'I', 8);
            $pdf->Cell(0, 10, 'Página ' . $pdf->getAliasNumPage() . ' de ' . $pdf->getAliasNbPages(), 0, 0, 'C');
            
            // Generamos y descargamos el PDF
            return response()->streamDownload(function() use ($pdf) {
                $pdf->Output('reporte-solicitudes-equipos-' . now()->format('Y-m-d').'.pdf', 'I');
            }, 'reporte-solicitudes-equipos-' . now()->format('Y-m-d').'.pdf');
        }
    }

    public function render()
    {
        return view('livewire.reportes.reporte-solicitudes-pdf');
    }
}

