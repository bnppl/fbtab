<?php
namespace Traffic\Silex\FBTab;

use Symfony\Component\Form\FormFactory;

class Quiz {
    
    /**
     *
     * @var PDO
     */
    protected $pdo;
    
    /**
     *
     * @var array 
     */
    protected $userFields = array();
    
    /**
     *
     * @var Symfony\Component\Form\FormFactory 
     */
    protected $form_factory;
    
    
    public function __construct($pdo, FormFactory $form_factory, $user_fields = null) 
    {
        
      $this->pdo = $pdo;
      $this->form_factory = $form_factory;
      if($user_fields)
      {
        
        $this->userFields = $user_fields;
        
      }
      
    }
    
    public function getEntryForm($defaults = array())
    {
        $form = $this->form_factory
          ->createBuilder('form', $defaults, array('csrf_protection' => false,'required' => true));

          
        foreach($this->userFields as $fieldname => $field) 
        {
        $form->add($fieldname, $field['form_type'], array('attr' =>$field['attr']));
        }
        
        $question = $this->getQuestionWithAnswers();

        $form->add('answer_id', 'choice', array(
            'choices' => $question['answers'],
            'expanded' =>true,
            'label' => ' ',
            'required' => true,
        ));

        $form = $form->getForm() ;
        return $form;
    }
    
    
    
    public function checkDBSetup()
    {
      $sql = 'SHOW TABLES';
      $stmt = $this->pdo->query($sql);
      $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      if(count($result) == 0)
      {
        $sql = $this->getTableDefinitionSQL();
        $this->pdo->query($sql);
      }
    }
    
    public function getForm(){
        $userFields = $this->getUserFields();
    
        $form = $app['form.factory']
            ->createBuilder('form', $defaults, array('csrf_protection' => false,));


        foreach($userFields as $field) 
        {
        $form->add($field['fieldname'], $field['type'], $field['attributes']);
        }
        $form = $form->getForm() ;
    }
    
    public function getUserFields(){
      return $this->userFields;
    }
    
    public function setUserFields($user_fields)
    {
        $this->userFields = $user_fields;
    }
    
    public function getQuestionWithAnswers()
    {
      $sql = 'SELECT * 
              FROM quiz_question q 
              WHERE is_active = 1 
              AND display_until > NOW() 
              ORDER BY display_until ASC
              LIMIT 1'
      ;
      $stmt = $this->pdo->query($sql);
      
      $question = $stmt->fetch(\PDO::FETCH_ASSOC);
      
      $sql = 'SELECT * FROM quiz_question_answer a WHERE a.quiz_question_id = :question_id';
      $stmt = $this->pdo->prepare($sql);
      $stmt->bindParam(':question_id', $question['id']);
      $stmt->execute();


      while($answer = $stmt->fetch(\PDO::FETCH_ASSOC))
      {
        $question['answers'][$answer['id']] = $answer['answer'];
      }
      
      
      return $question;
    }
    
    public function isAnswerCorrect($answer_id)
    {
      
      $sql = 'SELECT * FROM quiz_question_answer a WHERE a.id = :id';
      $answer_stmt = $this->pdo->prepare($sql);
      $answer_stmt->bindParam(':id', $answer_id);
      $answer_stmt->execute();
      $answer_row = $answer_stmt->fetch();
      
      if($answer_row['is_correct'] == 1){
        return true;
      }
      else{
        return false;
      }
      
    }
    
    
    public function save($form_values)
    {
      $query_parts = array();
      foreach($form_values as $key=>$value)
      {
        $query_parts[] = $key.' = :'.$key;
      }
      
      
      $sql = 'INSERT INTO entry SET '.implode(",\n", $query_parts);
              
      ;
      

      $stmt = $this->pdo->prepare($sql);
      
      $result = $stmt->execute($form_values);      

      return $result;      
    }
    
    public function getAllEntrants()
    {
      
      $fields = array();
      foreach($this->userFields  as $fieldname => $field){
        if(isset($field['csv']) && $field['csv']){
          $fields[] = $fieldname;
          
        }
      }
      
       $sql = 'SELECT '.implode(',',$fields).', q.question_title, a.answer FROM entry e 
          INNER JOIN quiz_question_answer a on e.answer_id = a.id 
          INNER JOIN quiz_question q on a.quiz_question_id = q.id';
      
      $stmt = $this->pdo->query($sql);
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    protected function getTableDefinitionSQL(){
      $sql = 'CREATE TABLE `quiz_question` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `question_title` text,
        `is_active` int(11) DEFAULT NULL,
        `display_until` datetime DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;';
      
      $sql .= 'CREATE TABLE `quiz_question_answer` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `quiz_question_id` int(11) DEFAULT NULL,
        `answer` varchar(255) DEFAULT NULL,
        `is_correct` tinyint(4) DEFAULT "0",
        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;';
      
      $sql .= 'CREATE TABLE `entry` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(200) DEFAULT NULL,
          `email` varchar(200) DEFAULT NULL,
          `agree_terms` int(11) DEFAULT NULL,
          `answer_id` int(11) DEFAULT NULL,
          `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=latin1;';
      return $sql;
    }
    
    
  }
