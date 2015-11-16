<?php 

namespace VCAA\db;

/**
* SQL Connection Request
*/
class DatabaseRequest
{
	
	//@todo : set db config here

	private $username = "root",$password = "root",$db_name = "vcaa_exam_fetcher",$host_name = "localhost",$table_name;
    
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
        
		// Establish Connection
	    $this->connection = new \mysqli($this->host_name,$this->username,$this->password,$this->db_name);

	    if (mysqli_connect_error()) {

	    	die("Connection Failed");
	    
	    }
       
		$this->table_name = $table_name;
	}

    /**
     * Enter maintainence mode
     *         
     * @return boolean 
     * 
     */
    public function enter_maintanence()
    {
        
        $tbname = $this->table_name;

        $sql = "UPDATE $tbname SET value = '1' WHERE options = 'maintanence' ";

        if ($this->connection->query($sql) === true) {
            
            return true;

        }

        return false;
        
    }

    /**
     * Exit maintanence mode
     * */
    public function exit_maintanence()
    {
        $tbname = $this->table_name;

        $sql = "UPDATE $tbname SET value = '0' WHERE options = 'maintanence' ";

        if ($this->connection->query($sql) === true) {
            
            return true;

        }

        return false;
    }

    /**
     * Check if the site is in maintanence mode
     *     
     * @return boolean Determine if the site is in maintanence
     * 
     */
    public function check_maintanence()
    {

        $tbname = $this->table_name;

        $sql = "SELECT value FROM $tbname WHERE options = 'maintanence'";

        $result = $this->connection->query($sql);

        $value = $result->fetch_assoc()['value'];

        if ((string)$value == "0") {
            
            return false;

        }

        return true;

    }

    
    /**
     * Add a new post to the database
     **/
    public function add_post($html)
    {	
    	
    	$tbname = $this->table_name;

    	$sql = "INSERT INTO $tbname (content) VALUES ('$html')";

    	if ($this->connection->query($sql) === true) {

    		return true;

    	}

    	return false;

    }

    /**
     * Get latest post 
     **/
    public function get_latest_post($current_id)
    {	
    	if ($this->has_new_post($current_id)) {

    	    $tbname = $this->table_name;

    		$sql = "SELECT content FROM $tbname ORDER BY id DESC LIMIT 1";

    		$result = $this->connection->query($sql);
            
            $fetched_content = $result->fetch_assoc()['content'];

    		return $fetched_content;

    	}

        return 0;
    }

    /**
     * Check if the current read post is the lastest one
     * */
    private function has_new_post($current_id)
    {
        $tbname = $this->table_name;

    	$sql = "SELECT max(id) AS 'maxid' FROM $tbname LIMIT 1";

    	$max_id = $this->connection->query($sql)->fetch_assoc()['maxid'];

    	if ($max_id) {
    		
    		if ($max_id > $current_id) {

                setcookie('latest_read',$max_id,time() + (86400 * 365), "/");

    			return true;

    		}

    	}
    	
    	return false;

    }


    /**
     * Get the processed download link
     *
     * @param String $row_name [row name referring to the subject]
     * 
     * @return URL for the document downloaded
     * 
     **/
	public function get_processed_document($subject_name)
	{  

        $tbname = $this->table_name;

		$sql = "SELECT filename FROM $tbname WHERE subject_name = $subject_name LIMIT 1";

        $result = $this->connection->query($sql)->fetch_assoc()['filename'];

        if ($result) {
            
            return $result;

        }

        return 0;
	}
    

    /**
     * Gets the value of connection.
     *
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }
}