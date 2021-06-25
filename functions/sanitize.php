<?php

function escape($string) {
	// ent_quotes: escape singe and double quotes and character encoding
	return htmlentities($string, ENT_QUOTES, 'UTF-8');	
}
