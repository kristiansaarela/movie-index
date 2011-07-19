    <table id="encodes" cellpadding="0" cellspacing="0">
        <?php
        
        $tcount = 1; $i = 0; /* Encoded by " . $m[$i]['encoder'] . "<br />*/
        foreach($m as $as => $do)
        {
            if($tcount == 1) echo "<tr>";
            
            echo "<td " . addbg($m[$i]['type']) . ">
                    <a href=\"" . $m[$i]['topic'] . "\" target=\"_blank\">" . $m[$i]['title'] . "</a> " . $m[$i]['year'] . "
                    <p>
                        <a href=\"" . $m[$i]['imdb'] . "\" target=\"_blank\">IMDb</a> | <a href=\"" . $m[$i]['cover'] . "\" onmouseover=\"\" title=\"header=[]body=[<img src='" . $m[$i]['cover'] . "' alt='cover' height='300px'/>]\">Cover</a><br />
                        In " . type($m[$i]['type']) . "";
                        if(isset($_SESSION['name'])) echo "<a href=\"edit.php?id=" . $m[$i]['id'] . "\"><img src=\"" . $p . "img/edit.png\" alt=\"Edit\" width=\"20px\" align=\"right\"></a>";
            echo "  </p>
                  </td>";
            
            if($tcount == 5)
            {
                echo "</tr>\n";
                $tcount = 0;
            }
            $tcount++; $i++;
        }
        
        ?>
    </table>