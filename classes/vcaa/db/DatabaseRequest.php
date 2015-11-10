<?php 

namespace VCAA\db;

/**
* SQL Connection Request
*/
class DatabaseRequest
{
	
	//@todo : set db config here

	private $username = "",$password = "",$db_name = "",$table_name;
    
    protected $connection;

	/**
	 * Constructor of Connection Class
	 * 
	 * @param String $table_name [Table To Load]
	 * 
	 * @return void  
	 * 
	 **/

	public function __construct($table_name)
	{
        
		//Establish Connection
	    $this->connection = new mysqli($this->db_name,$this->username,$this->password);

	    if ($this->connection->connect_error) {

	    	die("Connection Failed");
	    
	    }
       
		$this->table_name = $table_name;
	}
    
    /**
     * retrieve pages to cut
     * 
     * @param String $name [name of subject]
     * 
     * @return numbers of page to cut
     * 
     **/

	public function retrieve_pages_to_cut($name)
	{

	    return get_page_numbers_to_cut($name);   	
	
	}

    /**
     * Get number of pages to cut from the database
     *
     * @param String $row_name [row name referring to the subject]
     * 
     * @return numbers of page to cut
     * 
     **/
	private function get_page_numbers_to_cut($row_name)
	{
		$query = "SELECT * FROM $table_name WHERE 'subject_name' = $row_name";

		$results = $this->connection->query();

		if ($results->num_of_rows > 0) {
			
			while ($row = $result->fetch_assoc()){
                
                return $row['page_number_to_cut']; 

			}

		}
		return 0;
	}
    

}