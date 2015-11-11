<?php
namespace VCAA\exam;

/**
* VCAA Exam instance used to populate
*/

class Exam implements \JsonSerializable

{
    protected $subject_name;

	protected $title;

	protected $year;

	protected $url;

	protected $options;
	
	public function __construct($subject_name,$title,$year,$url, $options = null)
	{

		$this->subject_name = $subject_name;

        $this->title = $title;

        $this->year = $year;

        $this->url = $url;

        if ($options != null) {

            $this->options = $options;
            
        }
        
	}
	
    public function jsonSerialize()
    {
        $information = array(

            'subject_name' => $this->subject_name,

            'title' => $this->title,

            'year' => $this->year,

            'url' => $this->url

        );

        return $information;
    }

    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getSubjectName()
    {
        return $this->subject_name;
    }

    /**
     * Gets the value of year.
     *
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Gets the value of url.
     *
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Gets the value of options.
     *
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    

    /**
     * Gets the value of title.
     *
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }


}