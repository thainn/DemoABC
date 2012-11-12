<?php
class ExportController extends AppController {
    /**
    *
    * Export index
    *
    */
    
    var $uses = array('Recruit');
    function beforeFilter() {
                parent::beforeFilter();
                $this->Auth->allow(array('*'));
        }
    function index() {
          if($this->request->isPost())
          {
              $data = $this->request->data;
             // var_dump($data);exit;
              if($data['exportCSV'])
              {
                  $this->_csv();
              }else if($data['exportPDF'])
              {
                  $this->_pdf();
              }
          }
    }
    /**
    *
    * Dynamically generates a .csv file by looping through the results of a sql query.
    *
    */
    function _csv()
    {
            Configure::write('debug', 0);
            ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large

            //create a file
            $filename = "export_".date("Y.m.d").".csv";
            $csv_file = fopen('php://output', 'w');
            header("content-type:application/csv;charset=UTF-8");
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            $results = $this->Recruit->find('all', array());
            $this->set('data',$results);
            // The column headings of your .csv file
            $header_row = array("Id", "Title", "Content", "Created");
            fputcsv($csv_file,$header_row,',','"');
            // Each iteration of this while loop will be a row in your .csv file where each field corresponds to the heading of the column
            foreach($results as $result)
            {
                    // Array indexes correspond to the field names in your db table(s)
                    $row = array(
                            $result['Recruit']['id'],
                            $result['Recruit']['title'],
                            $result['Recruit']['content'],
                            $result['Recruit']['created']
                    );
                    fputcsv($csv_file,$row,',','"');
            }
            fclose($csv_file);
            exit;
    }
    function _pdf()
    {
        Configure::write('debug', 0);
        App::import('Vendor','xtcpdf');  
        $tcpdf = new XTCPDF(); 
        $textfont = 'freesans'; // looks better, finer, and more condensed than 'dejavusans' 

        $tcpdf->SetAuthor("KBS Homes & Properties at http://kbs-properties.com"); 
        $tcpdf->SetAutoPageBreak( false ); 
        $tcpdf->setHeaderFont(array($textfont,'',40)); 
        $tcpdf->xheadercolor = array(150,0,0); 
        $tcpdf->xheadertext = 'KBS Homes & Properties'; 
        $tcpdf->xfootertext = 'Copyright Â© %d KBS Homes & Properties. All rights reserved.'; 

        // add a page (required with recent versions of tcpdf) 
        $tcpdf->AddPage(); 

        // Now you position and print your page content 
        // example:  
        $tcpdf->SetTextColor(0, 0, 0); 
        $tcpdf->SetFont($textfont,'B',20); 
       // $tcpdf->Cell(0,14, "Hello World", 0,1,'L'); 
        // ... 
        // etc. 
        // see the TCPDF examples  
        $tcpdf->writeHTML($html, true, false, true, false, ''); 
        echo $tcpdf->Output('filename.pdf', 'D'); 
        exit;
    }
}
?>