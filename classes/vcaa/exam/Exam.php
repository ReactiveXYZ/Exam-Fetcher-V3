<?php
namespace VCAA\exam;
/**
* VCAA Exam instance used to populate
*/
class Exam
{
	protected $name;

	protected $year;

	protected $url;

	protected array $options;
	
	public function __construct($name,$year,$url,array $options = null)
	{

		$this->name = $name;

        $this->year = $year;

        $this->url = $url;

        $this->options = $options;
	}
	
    /**
     * Gets the value of name.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
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
}