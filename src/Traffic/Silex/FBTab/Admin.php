<?php
namespace Traffic\Silex\FBTab;

class Admin
{
  
  public function outputCSV($data, $filename='export', $heading_array = null)
  {
    
    $filename = $filename.'-'.date('Y-m-d-H-i-s').'.csv';
    header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=".$filename);
    header("Pragma: no-cache");
    header("Expires: 0");

    
    if($heading_array)
    {
      echo implode(',', $heading_array)."\r\n";
    }
    else
    {
      $formatted_headings = $this->extractHeadings($data);
      echo implode(',', $formatted_headings);
      echo "\r\n";
    }
    foreach($data as $row)
    {
      $fields=array();
      foreach($row as $field)
      {
          $field = preg_replace('/\s\s+/', ' ', $field);  //remove excess whitespace
          $field = preg_replace('/\n/', ' ', $field);     //remove excess whitespace
          $fields[] = html_entity_decode(str_replace('"', '""', $field));
      }
      $csv_row = implode(',' , $fields);
      echo $csv_row."\r\n";

      
    }
    
  }
  
  public function extractHeadings($data)
  {
     if(count($data)){
       
       $headings = array_keys($data[0]);
       $formatted_headings = array();
       foreach($headings as $heading)
       {
         $formatted_headings[] = ucwords(str_replace('_', ' ', $heading));
       }

       return $formatted_headings;
     }
    
  }
    
    
    
}