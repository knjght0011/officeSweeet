<?php
//set headers to NOT cache a page
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename="cal.ics"');
?>
{{ $icalData }}