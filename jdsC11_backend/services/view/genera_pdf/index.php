<?php 
require_once('../../../jds/php/fpdf181/fpdf.php');
require_once '../../../php/helpers.php';  
spl_autoload_register(function ($nombre_clase) {
    $nomClass =  '../../../'. str_replace('\\','/',$nombre_clase ).'.php' ;
    require_once $nomClass;
 });
new Core\Config();  
header("Content-type:application/json; charset=utf-8");
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: * ");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(array('status' => false));
    exit;
}
$json = file_get_contents('php://input');
   $_POST = json_decode($json, true); 
foreach ($_POST as $clave => $valor){
    $$clave = $valor;
}
/*VARIABLES DE ENTRADA*/
$porcenteje_sacado = 10;
$PUNTAJE_GLOBAL = 300;
$FECHA_APLICACION = '11 DE AGOSTO DE
2019';
$FECHA_RESULTADOS = '19 DE OCTUBRE DE
2019';
$NUMERO_DE_REGISTOS = 'AC201944131983';
$NOMBRE_APELLIDO = 'José de Jesús Domínguez P';
$CURSO = 'ONCE A';
$COLEGIO = 'COLEGIO DIOCESANO SAN JOSÉ';
/*VARIABRES DE ENTRADA*/

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
 /*REPORTE DE RESULTADOS
INDIVIDUAL
•SABER 11°•
RESULTADOS GLOBALES*/


$pdf->Image('images/logo_pruebas.PNG',10,18,55);
$pdf->ln();
$pdf->Cell(110,70,'',0  );


$pdf->SetFont('Arial','B',18);
$pdf->Multicell(90,8,'REPORTE DE RESULTADOS INDIVIDUAL',0,'C',0  );  
$pdf->Cell(110,70,'',0   );
$pdf->SetFont('Arial','',12);
$pdf->Multicell(90,8,arreglaTXTpdf('                   •SABER 11°•                     RESULTADOS GLOBALES'),0,'C',0  );  
/*bloque izquierdo datos del estudiante.*/
$pdf->Cell(110,60,'',1   );

/*resultado de la prueba*/
/*cuadro puntaje global*/
$pdf->Cell(5,10,'',0   );

$pdf->SetTextColor(249,249,249);
$pdf->Cell(75,8,'Puntaje Global',1 ,1 ,'C' ,TRUE );

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(115,10,'',0   );
$pdf->Multicell(50,5,'De 500 puntos posibles, su
puntaje global es',0,'L' ); 
$pdf->SetY(50);
$pdf->Cell(115,10,'',0   );
$pdf->SetFont('Arial','B',28);
$pdf->Cell(75,16,$PUNTAJE_GLOBAL,1 ,1 ,'R' );

/*fin cuadro puntaje global*/
/*separador*/
$pdf->Cell(0,2,'',0,1  );
/*sepadador*/
/*cuadro ¿EN QUÉ PERCENTIL SE ENCUENTRA?*/

/*cuadro titulo ¿EN QUÉ PERCENTIL SE ENCUENTRA?*/
$pdf->Cell(110,70,'',0  );
$pdf->Cell(5,10,'',0   );
$pdf->SetFont('Arial','',10);
$pdf->SetTextColor(249,249,249);
$pdf->Cell(75,8,arreglaTXTpdf('¿EN QUÉ PERCENTIL SE ENCUENTRA?'),1 ,1 ,'C' ,  TRUE );

$pdf->SetTextColor(0,0,0);
 
/*fin cuadro titulo ¿EN QUÉ PERCENTIL SE ENCUENTRA?*/
$pdf->Cell(110,70,'',0  );
$pdf->Cell(5,8,'',0   );
$pdf->SetFont('Arial','',8);
$str = arreglaTXTpdf('Respecto a los evaluados del país, usted está aquí.');
$pdf->Cell(75,8,$str,'LR' ,2 ,'C'  );
$pdf->Cell(75,15,'','LRB' ,2 ,'C'  );
$pdf->Image('images/apuntador.PNG',126,78);
$pdf->Image('images/linea_recta_mediciones.PNG',127.3,85,71);
$ceroY = 89.5;
$cerox = 129.6;
define('PIXELES_MAXIMOS' , 61);

$equivalencia = 0;
// 100 - 61   
// 10  -  x

// x = 10 * 61 ; 
$equivalencia = (PIXELES_MAXIMOS * $porcenteje_sacado ) / 100;
$cerox += $equivalencia ; 


/*el valor maximo equivalente al 100% es 61 es decir que la regla numerica
dibujada se mueve entre 0 y 61 pixeles*/
$pdf->Image('images/apuntador.PNG',$cerox,$ceroY);

$pdf->ln();

/*cuadro ¿EN QUÉ PERCENTIL SE ENCUENTRA?*/
/*fin resultado de la prueba*/

/*datos del estudiante  */

$pdf->SetFont('Courier','',8  );
$pdf->SetY(42);
$pdf->Cell(110,8,arreglaTXTpdf('Fecha de aplicación del examen:'),0,2  );
$pdf->Cell(110,8,arreglaTXTpdf('Fecha de publicación de resultados:'),0,2  );
$pdf->Cell(110,8,arreglaTXTpdf('Número de registro:'),0,2  );
$pdf->Cell(110,8,arreglaTXTpdf('Apellidos y nombres:'),0,2  );
$pdf->Cell(110,8,arreglaTXTpdf('Curso:'),0,2  );
$pdf->Cell(110,8,arreglaTXTpdf('Institución:'),0,2  ); 
$pdf->SetY(42);
$pdf->SetX(65);
$pdf->Cell(50,8,arreglaTXTpdf($FECHA_APLICACION),'B',2  );

$pdf->SetX(70);
$pdf->Cell(45,8,arreglaTXTpdf($FECHA_RESULTADOS),'B',2  );
$pdf->SetX(45);
$pdf->Cell(70,8,arreglaTXTpdf($NUMERO_DE_REGISTOS),'B',2  );
$pdf->Cell(70,8,arreglaTXTpdf($NOMBRE_APELLIDO ),'B',2  );
$pdf->SetX(23);
$pdf->Cell(92,8,arreglaTXTpdf($CURSO),'B',2  );
$pdf->SetX(32);
$pdf->Cell(83,8,arreglaTXTpdf($COLEGIO),'B',2  );


$pdf->ln(); 
$pdf->SetFont('Courier','',20  );
$pdf->Cell(0,20,arreglaTXTpdf('RESULTADOS POR PRUEBA'),0,2 ,'C' );
$pdf->Cell(40,100,'',1   ); 
$pdf->Cell(60,100,'',1   );
$pdf->Cell(90,100,'',1   );

$pdf->Sety(115);
//$pdf->SetTextColor(249,249,249);
$pdf->SetFillColor(195,191,191) ;
$pdf->SetFont('Courier','',11  );
$pdf->Cell(40,10,'PRUEBA','RLT',0,'C',true ); 
$pdf->Cell(60,10,'PUNTAJE POR PRUEBA','RLT',0,'C',true  );
$pdf->Cell(90.2,10, arreglaTXTpdf('¿EN QUÉ PERCENTIL SE ENCUENTRA?') ,'RLT',0,'C' ,true  );
/*datos de prueba*/
$arrayMaterias[0]['materia'] = 'Lectura Crítica';
$arrayMaterias[0]['puntaje'] = '64';
$arrayMaterias[0]['porcentaje'] = '87';
$arrayMaterias[1]['materia'] = 'Matemáticas';
$arrayMaterias[1]['puntaje'] = '40';
$arrayMaterias[1]['porcentaje'] = '20';
$arrayMaterias[2]['materia'] = 'Sociales Y Ciudadanas';
$arrayMaterias[2]['puntaje'] = '71';
$arrayMaterias[2]['porcentaje'] = '98';
$arrayMaterias[3]['materia'] = 'Ciencias Naturales';
$arrayMaterias[3]['puntaje'] = '56';
$arrayMaterias[3]['porcentaje'] = '76';
$arrayMaterias[4]['materia'] = 'Inglés';
$arrayMaterias[4]['puntaje'] = '69';
$arrayMaterias[4]['porcentaje'] = '94';
/*datos de prueba*/
$pdf->SetFont('Arial','',8);
$pdf->ln();
$pdf->Cell(40,10,''); 
$pdf->Cell(60,10,arreglaTXTpdf('De 100 puntos posibles, su puntaje es'),0,0,'C',false  );

$str = arreglaTXTpdf('Respecto a los evaluados del país, usted está aquí.');
$eje_x =  $pdf->GetX();
$pdf->Cell(75,8,$str,0 ,0 ,'C'  );
$eje_y =  $pdf->GetY();

$pdf->Image('images/apuntador.PNG',$eje_x+1,$eje_y+2);








$pdf->SetFont('Courier','B',9  );
$eje_y =  $pdf->GetY();
FOREACH($arrayMaterias as $clave => $info){
    $pdf->ln(16);
    $ejey =  $pdf->GetY();
$pdf->MultiCell(40,6,arreglaTXTpdf($info['materia']),0,'L',false ); 
$pdf->SetY($ejey-2);
$pdf->Cell(40,10,''); 
$pdf->Cell(60,10,$info['puntaje'],0,0,'C',false  );
$eje_x =  $pdf->GetX();
//$pdf->Cell(90.2,10, arreglaTXTpdf('¿EN QUÉ PERCENTIL SE ENCUENTRA?') ,'RLT',0,'C' ,true  );


$pdf->Image('images/linea_recta_mediciones.PNG',$eje_x+4,$ejey-2,71);
$ceroY = $ejey+2.4;
$cerox = $eje_x+4;
//define('PIXELES_MAXIMOS' , 61);

$equivalencia = 0;
// 100 - 61   
// 10  -  x

// x = 10 * 61 ; 
$equivalencia = (PIXELES_MAXIMOS * $info['porcentaje'] ) / 100;
$cerox += $equivalencia ; 


/*el valor maximo equivalente al 100% es 61 es decir que la regla numerica
dibujada se mueve entre 0 y 61 pixeles*/
$pdf->Image('images/apuntador.PNG',$cerox,$ceroY);
 
 
$pdf->Sety($ceroY);
$pdf->Setx($cerox+2);
$pdf->Cell(8,8,$info['porcentaje'],0,0,'C',false  );
$pdf->Sety($ejey);
$pdf->Setx($eje_x);
}

$pdf->Output();

