
<?php 

    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";
    $pfad = "c:\\freecell";
    $command = 'schtasks /create /sc T�GLICH /TN gamblen /TR ' . $pfad ;
    
    $esc_command = escapeshellcmd($command);
    
    $output = shell_exec($command);  
?>
