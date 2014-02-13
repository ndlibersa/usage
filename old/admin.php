<?php

$pageTitle = 'Administration';
include 'includes/header.php';
require 'includes/db.php';

?>

<table class="headerTable">
<tr><td>
<span class="headerText">Email addresses for logs</span>&nbsp;&nbsp;<span id='span_EmailAddress_response'></span>
<br /><span id='span_newEmailAddress' class='adminAddInput'><a href='ajax_forms.php?action=getAddressForm&height=122&width=238&modal=true' class='thickbox'>add new email address</a></span>
<br /><br />
<div id='div_emailAddresses'>
<img src = "images/circle.gif">Loading...
</div>
</td></tr>
</table>

<br />
<br />



<table class="headerTable">
<tr><td>
<span class="headerText">Outlier Parameters</span>&nbsp;&nbsp;<span id='span_Outlier_response'></span>
<br /><br />
<div id='div_outliers'>
<img src = "images/circle.gif">Loading...
</div>
</td></tr>
</table>

<br />
<br />

<script type="text/javascript" src="js/admin.js"></script>

<?php include 'includes/footer.php'; ?>