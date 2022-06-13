<?php
    // getIDs("/home/nomearod/ODuploadCSV/NDAP.csv", "NDAP");
    function getIDs($filename, $fileSource) {
        // fake a response
        // $jsonTmp = '{"0": "0014220037003", "1":"003-09-01EA-66-2-2-01-00942"}';
        // return $jsonTmp;

        // the original code
        echo "file name: ".$filename.", source: ".$fileSource."\n";
        $command = escapeshellcmd('/usr/bin/python3 mission2/getID.py '.$filename.' '.$fileSource);
        $output = shell_exec($command);
        
        echo "file type: ".gettype($output)."\n";
        echo $output;
    }

    function crawlMetadata($fileSource, $id) {
        echo "in crawler.php: crawlMetadata\n";
        $command = escapeshellcmd('/usr/bin/python3 mission2/crawlMetadata.py '.$id.' '.$fileSource);
        $output = shell_exec($command);
        echo $output;
    }
?>