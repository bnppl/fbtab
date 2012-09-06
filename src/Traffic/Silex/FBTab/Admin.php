<?php
namespace Traffic;

class Admin
{
    
  protected $query;
  
  protected $filters = array();
  
  protected $results_per_page ;
  
  /**
   *
   * @var \PDO
   */
  protected $pdo;
  
  public function __construct(\PDO $pdo)
  {
      $this->pdo = $pdo;
  }
  
  
  public function outputCSV($filename='export', $heading_array = null)
  {
    
    $filename = $filename.'-'.date('Y-m-d-H-i-s').'.csv';
    header("Content-type: application/csv");
    header("Content-Disposition: attachment; filename=".$filename);
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = $this->getResults();
    
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
  
  public function setQuery($sql)
  {
      $this->query = $sql;
  }
  
  public function addFilter($condition)
  {
      $this->filters[] = $condition;      
  }
  
  public function setResultsPerPage($results_per_page)
  {
      $this->results_per_page = $results_per_page;
  }
  
  public function getResults($page = false)
  {
      $query = $this->buildQuery();  
      
      
      if($page){
          $offset = ($this->results_per_page * $page) - $this->results_per_page;
          $limit = $offset.', '.$this->results_per_page;
          $query .= ' LIMIT '.$limit;
      }
      
      $stmt = $this->pdo->query($query);
      
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
  
  
  public function getTotalCount(){
      $query = $this->buildQuery();
      $stmt = $this->pdo->query($query);
      $count = $stmt->rowCount();

      return $count;
  }
  
  public function getNumPages()
  {
      $num_pages = ceil($this->getTotalCount() / $this->results_per_page);
      return $num_pages;
  }
  
  protected function buildQuery()
  {
      if(count($this->filters))
      {
          $condition = 'WHERE '.implode(' AND ', $this->filters );
          return $this->query.' '.$condition;
      }
      
      return $this->query;
  }
  
 


  public function extractHeadings($data)
  {
     if(count($data))
     {
       
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