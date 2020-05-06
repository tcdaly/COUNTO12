<?php
/*
________________________________________________________________________________________
*   Create JSON files to enable access to gallery of Road to 2012 photos
*   
*   (c) 2013 Thomas Daly
________________________________________________________________________________________
*/

	$_SERVER['SERVER_NAME'] = 'bramber';
	require 'site/config.php';

	$filegrp = new FileGroup();
	$filegrp->scan(MASTER_IMAGE_PATH, '/.*\.jpg$/');

	$hours = array();
	$minutes = array();
	$seconds = array();
	

	$i = 0;
	foreach ($filegrp->listFiles() as $file)
	{

		$results = array();
	
		// read timecode (2-digit number shown in photo) from filename
		preg_match('/\d\d\D\D(\d\d)$/', $file->filename, $results);
	
		$timecode = (int)$results[1];

		//echo $file->filename . "\t" . $timecode . "\n";

		// if filename contains the letter 'h', will need to go in hours array
		if (preg_match('/.+h.+/', $file->filename, $results))
		{
			$hours[$timecode][] = $file->filename;
		}

		// if filename contains the letter 'm', will need to go in minutes array
		if (preg_match('/.+m.+/', $file->filename, $results))
        {
                $minutes[$timecode][] = $file->filename;
        }

		// if filename contains the letter 's', will need to go in seconds array
        if (preg_match('/.+s.+/', $file->filename, $results))
        {
                $seconds[$timecode][] = $file->filename;
        }

		$i++;

	}
/*
	echo "Hours:\n";
	print_r($hours);

	echo "Minutes:\n";
	print_r($minutes);

	echo "Seconds:\n";
	print_r($seconds);

	echo "Total processed: $i\n";
*/	


	// capture the HTML output of a form display script as a string, so it can be sent as an email instead of to the browser
	ob_start();
	json_obj($hours);
// the code captured in the buffer is now copied to variable $form_html
	file_put_contents('site/www/json/hours.json', ob_get_contents());
// Clean (erase) the output buffer and turn off output buffering 	
	ob_end_clean();


	ob_start();
	json_obj($minutes);
	file_put_contents('site/www/json/minutes.json', ob_get_contents());
	ob_end_clean();
	
	
	ob_start();
	json_obj($seconds);
	file_put_contents('site/www/json/seconds.json', ob_get_contents());
	ob_end_clean();	


	function json_obj($a)
	{
		reset($a);
		$n = 0;
		echo "{\n";
		while (list($key, $this_array) = each($a))
		{
			echo "\"$key\":[";
			for ($i=0; $i<count($this_array); $i++)
			{
				$value = $this_array[$i];
				echo "\"$value\"";
				if ($i < (count($this_array) - 1))
					echo ', ';
			}
			echo "]";
			if ($n < count($a) - 1)
			{
				echo ",";
			}
			echo "\n";
		    $n++;
		}
		echo "}";	
	}

?>

