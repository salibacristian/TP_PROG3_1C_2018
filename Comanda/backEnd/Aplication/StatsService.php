<?php
require_once './Model/User.php';
require_once './Model/Stats.php';
require_once './Aplication/SessionService.php';
require_once './Excel/PHPExcel.php';

class StatsService 
{
    public function GetOrderEmployeesBySector($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::OrderEmployeesBySector($fromDate, $toDate);    
        $response = $response->withJson($report, 200);  
        return $response;
      }

      public function GetOrdersBySector($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::OrdersBySector($fromDate, $toDate);    
        $response = $response->withJson($report, 200);  
        return $response;
      }

      public function GetUnitsSales($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::UnitsSales($fromDate, $toDate);  
        $rv= new stdclass(); 
        $rv->top =  array_shift(array_values($report));
        $rv->bottom =  array_pop(array_values($report));
        $response = $response->withJson($rv, 200);  
        return $response;
      }

      
      public function GetDelayedOrders($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::DelayedOrders($fromDate, $toDate);  
        $response = $response->withJson($report, 200);  
        return $response;
      }

      public function GetCanceledOrders($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::CanceledOrders($fromDate, $toDate);  
        $response = $response->withJson($report, 200);  
        return $response;
      }
      
      public function GetTableUsage($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::TableUsage($fromDate, $toDate);  
        $rv= new stdclass(); 
        $rv->top =  array_shift(array_values($report));
        $rv->bottom =  array_pop(array_values($report));
        $response = $response->withJson($rv, 200);  
        return $response;
      }
      public function GetTableTotalAmount($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::TableTotalAmount($fromDate, $toDate);  
        $response = $response->withJson($report, 200);  
        return $response;
      }
      
      public function Getbilling($request, $response, $args) {
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $report = Stats::Billing($fromDate, $toDate);  
        $response = $response->withJson($report, 200);  
        return $response;
      }

      //TODO HACER GENERICA
      public function Download($request, $response, $args) {
         
        $params = $request->getParams(); 
        $fromDate = $params['fromDate'];
        $toDate = $params['toDate'];
        $data = Stats::OrderEmployeesBySectorForExcel($fromDate, $toDate);
        $fieldsLength = count($data);
        // var_dump($data);die();
        $excel = new PHPExcel(); 
        // header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        // header("Content-Disposition: attachment; filename=\"results.xlsx\"");
        //Usamos el worsheet por defecto 
        $sheet = $excel->getActiveSheet(); 
        $letters =  array('A','B','C','D','E','F','G','H','I','J','K','L','M','N');
        $sheet->setCellValue('A1','SECTOR' ); 
        $sheet->setCellValue('B1','USUARIO' ); 
        $sheet->setCellValue('C1','OPERACIONES' ); 
        //Damos formato o estilo a nuestra celda 
        $sheet->getStyle('A1')->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
        $sheet->getStyle('B1')->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
        $sheet->getStyle('C1')->getAlignment()->setVertical('center')->setHorizontal('center'); 
       
        foreach ($data as $key => $row) {  
            // if($key == 0)//evito header
            //     continue;
            $rowNumber = $key + 1;
            //  var_dump($row);die();
           for ($i=0; $i < $fieldsLength; $i++) {         
                $filed = $row[$i];
                $colLetter = $letters[$i];
    
                // var_dump($filed);die();
                $sheet->setCellValue($colLetter.$rowNumber, $filed); 
                //Damos formato o estilo a nuestra celda 
                // $sheet->getStyle($colLetter.$rowNumber)->getFont()->setName('Tahoma')->setBold(true)->setSize(8); 
                // $sheet->getStyle($colLetter.$rowNumber)->getBorders()->applyFromArray(array('allBorders' => 'thin')); 
                // $sheet->getStyle($colLetter.$rowNumber)->getAlignment()->setVertical('center')->setHorizontal('center'); 
               
            }
        }
        //exportamos nuestro documento 
        $writer = new PHPExcel_Writer_Excel5($excel); 
        $writer->save('results.xls'); 
        return "http://localhost:8080/TP_PROG3_1C_2018/Comanda/backEnd/results.xls";  
         
      }
      

}
?>