<?php
echo "<div class='parent'>";
$start_year = 2017;
if ($start_year == date("Y")) {
        echo "&copy;".$start_year." - Gustavo Lessa (<a href = https://GitHub.com/gustavolessadublin>GitHub</a>)";
} else {
    echo "&copy;".$start_year."-".date("Y")." - Gustavo Lessa (<a href = https://GitHub.com/gustavolessadublin>GitHub</a>)";
}
echo "</div>";
?>
