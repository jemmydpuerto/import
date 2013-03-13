<?php
$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }


  mysql_select_db('share_reward',$con);

  $target_path = "files/";

$target_path = $target_path . basename($_FILES['file']['name']); 

if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
  $row = 0;
$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
if(in_array($_FILES['file']['type'],$mimes)){
 if (($handle = fopen("files/" . $_FILES['file']['name'], "r")) !== FALSE) {
    
    $rowValueArr = Array();

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $rowValue = '';
        
        for ($c=0; $c < $num; $c++) {

           $data[$c] = trim($data[$c]);
           $rowValue .=  '"' .$data[$c] . '",';

        }

        $sData = substr($rowValue, 0, (strlen($rowValue) - 1));
        
        array_push($rowValueArr, $sData);

        $row++;
    }

    $numArr =  count($rowValueArr);
     
    $cId;

    for($i = 0; $i < $numArr; $i++) {
      $cDRow = Array();
      $currentCId = Array();

      $rowValueArr[$i] = str_replace('"', '', $rowValueArr[$i]);


      $spl = preg_split("/,\s*/", $rowValueArr[$i]);


      $qry = mysql_query('select ID, coID, brID, dateTime from customer_tb where brID = ' . $spl[0] .' and fName = "Past" and lName = "Year"');

      $rowCount = mysql_num_rows($qry);

      if($rowCount > 0) {
        while($rowQry = mysql_fetch_array($qry)) {
          $cDRow[] = $rowQry['ID'] . ',' . $rowQry['coID'] . ',' . $rowQry['brID'] . ',"' . $rowQry['dateTime'] . '"';
          $currentCId[] = $rowQry['ID'];
        }

        for($j = 0; $j < count($cDRow); $j++) {

          $insertD = mysql_query('insert into customerd_tb(customerID,coID,brID,dateTime)values(' .$cDRow[$j] .')');

          if(!$insertD) {
            die('Error sql' . mysql_error());
          }

          //      itemID          amount          customerid               serial           branchID         transDate        qty
          $cols = $spl[2] . ',' . $spl[3] . ',' . $currentCId[$j] . ',"' . $spl[4] . '",' . $spl[0] . ',"' . $spl[5] . '",' . $spl[6];

          $insertT = mysql_query('insert into transactions_tb(itemID,amount,customerdID,serialNo,brID,transDate,qty)values(' . $cols .')');

          if(!$insertT) {
            die('Error sql' . mysql_error());
          }
        }



      }
    }

    echo 'Data successfully added.';

    fclose($handle);
    mysql_close($con);
}
} else {
  echo "Sorry, file type not allowed";
}


} else{
    echo "There was an error uploading the file, please try again!";
}
