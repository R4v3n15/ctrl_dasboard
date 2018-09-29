<?php
//require(Config::get('PATH_LIBS').'Classes/cpdf.php');
include(Config::get('PATH_LIBS').'Classes/fpdf.php');
        $logo = Config::get('URL').'public/assets/img/logo.png';
        $pdf = new FPDF('P','mm','Letter');
        $pdf->AddPage();
        $pdf->SetMargins(5,5,5);

        $pdf->Image($logo,15,3,-270);

        $pdf->SetFont('Arial', '', 13);
        $pdf->Text(90,8,"NA'ATIK S.C.");
        $pdf->SetFont('Arial', 'i', 9);
        $pdf->Text(68,12,"NA'ATIK TE ABRE LAS PUERTAS AL MUNDO");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Text(50,16,"Calle 57 entre 78 y 80, Col. Francisco May - Felipe Carrillo Puerto, Quintana Roo");
        $pdf->SetFont('Arial', '', 8);
        $pdf->Text(47,20,utf8_decode("Teléfonos: Esc. 2 67 1410 Cel Directora. 983-700-7248, Lic. Catherine Gray, Directora"));
        $pdf->Line(5,22,210,22);   

        $pdf->SetFont('Arial', 'b', 10);
        $pdf->Text(90,26,'C O N V E N I O');

        $pdf->SetFont('Arial', '', 8.8);
        $pdf->Text(15,34,utf8_decode('CONVENIO   DE  INSCRIPCIÓN   A  CURSO   DE  INGLÉS  QUE  CELEBRAN   POR  UNA  PARTE  "NA'."'".'ATIK"  Y   EL   ALUMNO'));

        $pdf->SetFont('Arial', 'i', 9);
        $pdf->Text(15,38,utf8_decode("XIMENA SHIRET MARTINEZ PEREZ"));
        $pdf->Line(15,39,78,39);
        $pdf->SetFont('Arial', '', 8.8);
        $pdf->Text(82,38,utf8_decode("POR  CONSIGUIENTE  AMBAS PARTES  ACUERDAN SOMETERSE AL TENOR"));

        $pdf->SetFont('Arial', '', 8.8);
        $pdf->Text(15,42,utf8_decode("DE  LAS  SIGUIENTES:"));

        $pdf->SetFont('Arial', 'b', 10);
        $pdf->Text(90,48,utf8_decode("C L A U S U L A S"));

        // = = = = = = = JEDAN = = = = = = = = //
        $b = 48 + 6;
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Text(15,$b,utf8_decode("PRIMERA:"));
        $pdf->SetFont('Arial', '', 8.7);
        $pdf->Text(33,$b,utf8_decode("LA  INSCRIPCIÓN  SERÁ  PARA  ASISTIR  A  LAS  CLASES  DE "));
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(127,$b,utf8_decode("ADOLESCENTES INICIAL SABADO"));
        $pdf->Line(186,$b+1,125,$b+1);
        $pdf->SetFont('Arial', '', 8.7);
        $pdf->Text(188,$b,utf8_decode(" CON"));

        $b += 4.5;
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(15,$b,utf8_decode("INICIO EL DÍA"));
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(40,$b,utf8_decode("MIERCOLES 27 DE SEPTIEMBRE DEL AÑO 2017"));
        $pdf->Line(39,$b+1,114,$b+1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(116,$b,utf8_decode("EN  HORARIO  DE"));
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(146,$b,utf8_decode(" 11:00 - 14:00 HRS."));
        $pdf->Line(145,$b+1,177,$b+1);
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(179,$b,utf8_decode(" LOS   DÍAS"));

        $b += 4.5;
        $pdf->SetFont('Arial', '', 8.8);
        $pdf->Text(15,$b,utf8_decode("CON  UNA  DURACION  DE "));
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(56,$b,utf8_decode(" 10"));
        $pdf->Line(55,$b+1,62,$b+1);
        $pdf->SetFont('Arial', '', 8.8);
        $pdf->Text(64,$b,utf8_decode(" SEMANAS,  LAS  CLASES  SE  IMPARTIRÁN  EN  LAS INSTALACIONES  DE  NA'ATIK"));

        // = = = = = = = DVA = = = = = = = = //
        $b += 6;
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Text(15,$b,utf8_decode("SEGUNDA:"));
        $pdf->SetFont('Arial', '', 8.6);
        $pdf->Text(34,$b,utf8_decode("EL INSTITUTO  SE  COMPROMETE  A  PROPORCIONAR  AL  ALUMNO  LAS  CONDICIONES  NECESARIAS  PARA"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.1);
        $pdf->Text(15,$b,utf8_decode("FAVORECER EL ADECUADO DESARROLLO DEL PROCESO ENSEÑANZA-APRENDIZAJE, DESTINANDO PARA ELLO  INSTALACIONES"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Text(15,$b,utf8_decode("Y PERSONAL A FIN A LOS PROPÓSITOS DE CADA CURSO."));

        // = = = = = = = TRY = = = = = = = = //
        $b += 6;
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Text(15,$b,utf8_decode("TERCERA:"));
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Text(34,$b,utf8_decode("SERÁ COMPROMISO  DEL INSTITUTO  EMITIR  PERIODICAMENTE LAS CALIFICACIONES DE LOS MÓDULOS QUE"));
        
        $b += 4;
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Text(15,$b,utf8_decode("EL  ALUMNO  VAYA  CURSANDO;  ASÍ COMO  MANTENER INFORMADO  AL TUTOR DE LA CONDUCTA,  SITUACIÓN."));

        // = = = = = = = CETIRI = = = = = = = = //
        $b += 6;
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Text(15,$b,utf8_decode("CUARTA:"));
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Text(32,$b,utf8_decode("EL  INSTITUTO  SE  RESERVA  EL  DERECHO  DE  ADMISIÓN,  QUEDANDO  RESTRINGIDO  EL  USO  DE  EQUIPO Y"));
        
        $b += 4;
        $pdf->SetFont('Arial', '', 8.4);
        $pdf->Text(15,$b,utf8_decode("ACCESO A LAS INSTALACIONES  A TODA PERSONA QUE NO  ACREDITE SU IDENTIDAD CON  CREDENCIAL DE  ESTE PLANTEL"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Text(15,$b,utf8_decode("O CUANDO EL ALUMNO TENGA COMPROMISOS ECONÓMICOS PENDIENTES POR CUBRIR."));
        
        // = = = = = = = PET = = = = = = = = //
        $b += 6;
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Text(15,$b,utf8_decode("QUINTA:"));
        $pdf->SetFont('Arial', '', 8.6);
        $pdf->Text(30,$b,utf8_decode("EL ALUMNO SE  COMPROMETE A RESPETAR Y HACER RESPETAR  EL REGLAMENTO INTERNO DEL INSTITUTO, Y"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.6);
        $pdf->Text(15,$b,utf8_decode("POR  CONSIGUIENTE  ESTAR DE LAS FUSIONES DE GRUPO  Y POSIBLES  CAMBIOS DE HORARIO CUANDO EL NÚMERO DE"));
        
        $b += 4;
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Text(15,$b,utf8_decode("ALUMNOS SEA REDUCIDO (MENOS DE SEIS ALUMNOS)."));

        // = = = = = = = SHEST = = = = = = = = //
        $b += 6;
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Text(15,$b,utf8_decode("SEXTA:"));
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Text(29,$b,utf8_decode("EL INSTITUTO RESPETARÁ LOS DÍAS DE SUSPENSIÓN DE LABORES MARCADOS EN EL CALENDARIO ESCOLAR DE"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Text(15,$b,utf8_decode("LA SEP."));
        
        // = = = = = = = SEDAM = = = = = = = = //
        $b += 6;
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Text(15,$b,utf8_decode("SÉPTIMA:"));
        $pdf->SetFont('Arial', '', 8.7);
        $pdf->Text(32,$b,utf8_decode("SERÁ  RESPONSABILIDAD  DEL  ALUMNO  EFECTUAR  OPORTUNAMENTE  LA TOTALIDAD  DEL  PAGO  DE  SUS"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.8);
        $pdf->Text(15,$b,utf8_decode("COLEGIATURAS   AUNQUE  ÉL  NO   HAYA  ASISTIDO  A  LAS  CLASES  O  QUE LAS CÁTEDRAS  SEAN   AFECTADAS   POR"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.6);
        $pdf->Text(15,$b,utf8_decode("EVENTUALIDADES  AJENAS AL  INSTITUTO, COMO EL  CASO DE  HURACANES, INTERRUPCIONES  DE  ENERGÍA ELÉCTRICA"));
        
        $b += 4;
        $pdf->SetFont('Arial', '', 8.6);
        $pdf->Text(15,$b,utf8_decode("U OTRO QUE PUDIERA PRESENTARSE. ACLARÁNDOSE QUE PARA SUBSANAR TAL SITUACIÓN, EL INSTITUTO PLANIFICARÁ"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.7);
        $pdf->Text(15,$b,utf8_decode("LA RECUPERACIÓN DE LAS CLASES BAJO NOTIFICACIÓN PREVIA AL TUTOR."));

        // = = = = = = = OSAM = = = = = = = = //
        $b += 6;
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Text(15,$b,utf8_decode("OCTAVA:"));
        $pdf->SetFont('Arial', '', 8.8);
        $pdf->Text(32,$b,utf8_decode("SE ESTABLECE  QUE  LOS IMPORTES  PACTADOS  PARA  LAS  COLEGIATURAS  EN  ESTE  CONVENIO SEGÚN"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.8);
        $pdf->Text(15,$b,utf8_decode("EL  PROGRAMA  EDUCATIVO  SON  DE: $ 344.00  CADA  4  SEMANAS. SI  SE  PAGA AL CONTADO  CADA 29 AL 06 DE CADA"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Text(15,$b,utf8_decode("MES ADELANTANDO  LA  COLEGIATURA SERA:  300.00 , DE LO CONTRARIO SERA EN 2 PAGOS DE $        . CONSIDERANDO EN"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Text(15,$b,utf8_decode("EL CASO DE DOS O MAS DE LA MISMA FAMILIA, SERA DE $         POR LOS        ALUMNOS.  INSCRIPCIÓN  $200.00."));

        $b += 3;
        $pdf->SetFont('Arial', '', 7.5);
        $pdf->Text(15,$b,utf8_decode("(La inscripción se pagará una sola vez siempre y cuando el alumno sea regular o deje de asistir al instituto para luego regresar en un rango menor a 6 meses)."));

        // = = = = = = = DEVET = = = = = = = = //
        $b += 6;
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Text(15,$b,utf8_decode("NOVENA:"));
        $pdf->SetFont('Arial', '', 8.8);
        $pdf->Text(32,$b,utf8_decode("EL  INSTITUTO   ÚNICAMENTE   PROPORCIONARÁ  COPIAS   ADICIONALES  AL  TEXTO  Y  EQUIPOS  BÁSICOS"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.7);
        $pdf->Text(15,$b,utf8_decode("CORRESPONDIENTES  EL  ALUMNON  ES  RESPONSABLE POR  SU  TEXTO  O  COPIAS DE  LO  MISMO  QUE  USARA  PARA"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.6);
        $pdf->Text(15,$b,utf8_decode("LLEVAR A CABO SU CURSO"));

        // = = = = = = = DESET = = = = = = = = //
        $b += 6;
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Text(15,$b,utf8_decode("DÉCIMA:"));
        $pdf->SetFont('Arial', '', 8.7);
        $pdf->Text(30,$b,utf8_decode("PARA EL ÓPTIMO  APROVECHAMIENTO  DE LAS CLASES DE INGLÉS  SERÁ COMPROMISO DEL  ALUMNO TRAER"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.7);
        $pdf->Text(15,$b,utf8_decode("A LA  PERSONA QUE  SERVIRÁ DE  MODELO  PARA LA  PRÁCTICA  DE LA CLASE EN CUESTIÓN,  EN CASO  CONTRARIO, EL"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.7);
        $pdf->Text(15,$b,utf8_decode("INSTITUTO  NO  SE  HACE  RESPONSABLE DE LA  ADECUADA  CAPACITACIÓN DEL ALUMNO NO  OBTENIENDO  CON  ELLO"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Text(15,$b,utf8_decode("EL PUNTAJE RESPECTIVO DE CALIFICACIÓN."));

        // = = = = = = = JEDANEST = = = = = = = = //
        $b += 6;
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Text(15,$b,utf8_decode("DÉCIMA PRIMERA:"));
        $pdf->SetFont('Arial', '', 8.6);
        $pdf->Text(45,$b,utf8_decode("EL ALUMNO  ESTARÁ CONSCIENTE EN  CUBRIR LOS GASTOS QUE SE  DERIVEN DE PRÁCTICAS FUERA"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Text(15,$b,utf8_decode("DE LA INSTITUCIÓN, COMO ACTIVIDAD PLANIFICADA PREVIAMENTE BAJO ACUERDO DE GRUPO."));

        // = = = = = = = DVANEST = = = = = = = = //
        $b += 6;
        $pdf->SetFont('Arial', 'b', 9);
        $pdf->Text(15,$b,utf8_decode("DÉCIMA SEGUNDA:"));
        $pdf->SetFont('Arial', '', 8.6);
        $pdf->Text(47,$b,utf8_decode("PARA QUE EL  INSTITUTO PUEDA  ENTREGAR  DOCUMENTACIÓN  OFICIAL AL EGRESADO(A) ÉSTE NO"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.7);
        $pdf->Text(15,$b,utf8_decode("DEBERÁ TENER ADEUDO ACADÉMICO NI ECONÓMICO CON LA INSTITUCIÓN."));

        $b += 6;
        $pdf->SetFont('Arial', '', 8.7);
        $pdf->Text(15,$b,utf8_decode("DESPÚES  DE  HABER   DADO  LECTURA  A  ESTE  DOCUMENTO  Y  DECLARAR  ESTAR DE ACUERDO EN  PROCEDER A LA"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.7);
        $pdf->Text(15,$b,utf8_decode("INSCRIPCIÓN DEL SOLICITANTE, ÉSTE SE COMPROMETE A CUMPLIR Y HACER   CUMPLIR LO DESCRITO EN EL CONVENIO"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.7);
        $pdf->Text(15,$b,utf8_decode("Y EL  PADRE O TUTOR  ACEPTA  RESPONSABILIZARSE DE   LA BUENA  CONDUCTA Y DEL OPORTUNO  CUMPLIMIENTO DE"));

        $b += 4;
        $pdf->SetFont('Arial', '', 8.7);
        $pdf->Text(15,$b,utf8_decode("LAS  OBLIGACIONES  QUE  SE  CONTRAEN  COMO  ALUMNO  DE ESTE  INSTITUTO. Y CON LA  FINALIDAD  DE DAR  DEBIDA"));
        
        $b += 4;
        $pdf->SetFont('Arial', '', 8.5);
        $pdf->Text(15,$b,utf8_decode("CONSTANCIA Y SEGURIDAD A AMBAS PARTES SE FIRMA LA PRESENTE EN LA FECHA"));
        $pdf->SetFont('Arial', '', 9);
        $pdf->Text(143,$b,utf8_decode("25-08-2017."));
        $pdf->Line(180,$b,142,$b);

        $page = rand(1,25);
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10,240);     
        $pdf->MultiCell(200,4, 'Wedding Comments ');
        $pdf->Output('convenio_'.$page.'.pdf','I');
    

?>