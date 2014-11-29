<?php

// Source Link
$website = 'http://testing.moacreative.com/job_interview/php/index.html';

siteToJSON($website);


function siteToJSON($site){
	// Loads DOM
	$dom = new DOMDocument();
	$dom->loadHTMLFile($site);
	$elements = $dom->documentElement;

	// Convert to Array and encodes as JSON Object, printed to page.
	header("Content-Type: text/plain");
	
	$json = json_encode(HTMLToArray($elements))

	echo $json;

	// Saves JSON object to local file
	file_put_contents('website.json', $json);
}

function HTMLToArray($element){
	
	$obj = array();


	// Loops through the elements attributes and adds them within the attributes array.
	if($element->attributes){
		foreach ($element->attributes as $attribute) {
			if($element->getAttribute('id')){
				$obj[$element->tagName.'#'.$element->getAttribute('id')]['attributes'][$attribute->name] = $attribute->value;
			}else{
				$obj[$element->tagName]['attributes'][$attribute->name] = $attribute->value;
			}
			
		}
	}
		
	// If the element has children, runs the recursive function to repeat the array making process,
	// else simply adds in the elements value.
	foreach($element->childNodes as $subElement){

		if($subElement->childNodes){
			if($subElement->nodeType == XML_TEXT_NODE){
				$obj[$subElement->tagName]['value'] = $subElement->nodeValue;
			}else{				
				$obj[$element->tagName]['child_nodes'][] = HTMLToArray($subElement);	
			}
		}else{
			if($subElement->nodeType == XML_TEXT_NODE){
				$obj[$element->tagName]['value'] = $subElement->nodeValue;
			}else{
				$obj[$element->tagName] = $subElement->nodeValue;
			}
		}			  
	}

	// returns the resulting array
	return $obj;
}





