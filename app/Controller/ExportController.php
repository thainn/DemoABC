<?php
class ExportController extends AppController {
    /**
    *
    * Export index
    *
    */
    var $uses = array('Recruit');
    var $from_date = '';
    var $to_date = '';
    var $usrID = '';
    function beforeFilter() {
                parent::beforeFilter();
                $this->Auth->allow(array('*'));
    }
    function index() {
        $this->loadModel('user');
        $data['all'] = 'Tất cả';
        $data[] = $this->user->find('list', array('fields' => array('id','username')));
        $this->set('states',$data);
          if($this->request->isPost())
          {
              $data = $this->request->data;
              $this->usrID = $data['User'];
              $this->from_date  = $data['from_date'];
              $this->to_date    = $data['to_date'];
              if(!empty($data['exportCSV']))
              {
                  $this->_csv();
              }else if(!empty($data['exportPDF']))
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
        $conditions = array();
        if($usrID = intval($this->usrID))
        {
            $conditions[] = array('user_id'=>$usrID);
        }
        $results = $this->Recruit->find("all",array('conditions' => $conditions));
        $this->set('data',$results);
        // The column headings of your .csv file
        $header_row = array("Id", "User", "Title", "Content", "Created", "Status");
        fputcsv($csv_file,$header_row,',','"');
        // Each iteration of this while loop will be a row in your .csv file where each field corresponds to the heading of the column
        foreach($results as $result)
        {
                // Array indexes correspond to the field names in your db table(s)
                $row = array(
                        $result['Recruit']['id'],
                        $result['User']['username'],
                        $result['Recruit']['title'],
                        $result['Recruit']['content'],
                        date('d/m/Y',  strtotime($result['Recruit']['created'])),
                        $result['Recruit']['status']
                );
                fputcsv($csv_file,$row,',','"');
        }
        fclose($csv_file);
        exit;
    }
    function _pdf()
    {
        Configure::write('debug', 0);
        if($usrID = intval($this->usrID))
        {
            $data = $this->Recruit->find("all",array('conditions' => array('user_id' => $usrID)));
        }else
        {
            $data = $this->Recruit->find("all");
        }
        App::import('Vendor','xtcpdf');  
        $tcpdf = new XTCPDF(); 
        $textfont = 'dejavusans'; // looks better, finer, and more condensed than 'dejavusans' 
        $tcpdf->AddPage('L', 'A4'); 
        $tcpdf->SetFont($textfont,'',10); 
        
        $html   ='<center><h1 align="center">Recruit report</h1></center>';
        $html  .='Ngày xuất : '.date('d/m/Y H:i').'<br/>';
        $html  .='Số lượng record : '.count($data).'<br/>';
        $html  .='Người xuất : admin<br/><br/>';
        $html  .='<table border="1" cellpadding="5" cellspacing="0" width="100%">';
        $html  .= '<tr style="color:#fff;bgcolor:#999"><td  bgcolor="#999" width="5%">ID</td><td  bgcolor="#999" width="13%">User</td><td  bgcolor="#999" width="27%">Title</td><td  bgcolor="#999" width="30%">Content</td><td bgcolor="#999" width="15%">Create</td><td bgcolor="#999" width="10%">Status</td></tr>';
       
        foreach ($data as $vdata)
        {
            $html  .= '<tr><td>'.$vdata['Recruit']['id'].'</td><td>'.$vdata['User']['username'].'</td><td>'.$vdata['Recruit']['title'].'</td><td>'.$vdata['Recruit']['content'].'</td><td>'.date('d/m/Y',  strtotime($vdata['Recruit']['created'])).'</td><td>'.$vdata['Recruit']['status'].'</td></tr>';
        }
        
        $html  .= '</table>';
        
       $tcpdf->writeHTML($html, true, false, true, false, ''); 
        echo $tcpdf->Output('Recruit.pdf', 'D'); 
        exit;
    }
}
?>