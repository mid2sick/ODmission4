<?php
    $dirList = $_SESSION['dirList'];
    foreach($dirList as $dir) { ?>
        <!--
        <li ><a href="javascript:void(0)" class="dir" id=""><?php echo $dir?></a></li>
        -->
        <form action="index.php" method="post" class="linkForm">
            <button type="submit" name="listDocs" value="<?php echo $dir?>" class="btn-link"><?php echo $dir?></button>
        </form>

    <?php
    }
    unset($_SESSION['dirList']);
?>