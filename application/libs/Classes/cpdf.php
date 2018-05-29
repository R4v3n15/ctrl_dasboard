<?php
include(Config::get('PATH_LIBS')."Classes/fpdf.php");

class CPDF extends FPDF
{
  var $ancho_linea;
  var $LetraSize;
  var $margen;
  var $espacio_linea;

  function Label($ancho, $alto, $texto, $borde, $salto, $alineacion, $relleno, $link)
  {
    $this->Celda($ancho, $alto, $texto, $borde, $salto, $alineacion, $relleno, $link, 'B');
  }

  function Texto($ancho, $alto, $texto, $borde, $salto, $alineacion, $relleno, $link)
  {
    $this->Celda($ancho, $alto, $texto, $borde, $salto, $alineacion, $relleno, $link, '');
  }

  function Celda($ancho, $alto, $texto, $borde, $salto, $alineacion, $relleno, $link, $estilo)
  {
    $this->SetFont("Arial", $estilo, $this->LetraSize);
    if ($borde == "")
        $b = 0;
    else if ($borde == "A")
        $b = 1;
    else
        $b = $borde;

    $this->Cell($ancho, $alto, $texto, $b, 0, $alineacion, $relleno, $link);

    if ($salto == 'Y')
    {
      $this->SetY($this->GetY() + $this->ancho_linea + $this->espacio_linea);
      $this->SetX($this->margen);
    }
    else
    {
      $this->SetX($this->GetX() + $ancho);
    }
  }

  function AddX($add)
  {
    $this->SetX($this->GetX() + $add);
  }
}
?>