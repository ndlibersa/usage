<?php

$pageTitle='Home';

include 'includes/header.php';
require 'includes/db.php';


?>

<table class="headerTable">
<tr><td>
<div class="headerText" style='margin-bottom:9px;'>Recent Imports <span id='span_feedback'></span></div>

<div id='div_recentImports'>
<img src = "images/circle.gif">Loading...
</div>

</td></tr>
</table>


<script type="text/javascript" src="js/index.js"></script>

<?php include 'includes/footer.php'; ?>