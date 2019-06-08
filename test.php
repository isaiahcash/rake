<?php
require_once('includes/config.php');
start_page();


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.edmunds.com/appraisal/');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, "/var/www/html/isaiahcash/rake/cookies.txt");
curl_setopt($ch, CURLOPT_COOKIEFILE, "/var/www/html/isaiahcash/rake/cookies.txt");
curl_setopt($ch, CURLOPT_COOKIESESSION, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");
curl_setopt($ch, CURLOPT_REFERER, "https://www.edmunds.com/");
//$page = curl_exec($ch) or die(curl_error($ch));
//echo $page;


// Create a DOM object
$dom = new simple_html_dom();
// Load HTML from a string
$dom->load(curl_exec($ch)) or die(curl_error($ch));

$year_div = $dom->find('select[name=year]', 0);
if($year_div) $years = clean($year_div->innertext);

print $years;



curl_close($ch);

script_includes();
end_page();

?>