<?php
    // getIDs("/home/nomearod/ODuploadCSV/NDAP.csv", "NDAP");
    echo "start\n";
    // crawlMetadata("AHTWH", "00501103411");
    function getIDs($filename, $fileSource) {
        $pythonPath = 'C:\\Users\\Administrator\\AppData\\Local\\Programs\\Python\\Python310\\python.exe';
        $getIDPath = 'C:\\WebRoot\\OD\\ODmission4\\ODtest\\mission2\\getID.py';
        $cmd = "$pythonPath $getIDPath $filename $fileSource";
        $command = escapeshellcmd($cmd);
        $output = shell_exec($command);
        return $output;
    }

    function crawlMetadata($fileSource, $id) {
        $pythonPath = 'C:\\Users\\Administrator\\AppData\\Local\\Programs\\Python\\Python310\\python.exe';
        $crawlMetadataPath = 'C:\\WebRoot\\OD\\ODmission4\\ODtest\\mission2\\crawlMetadata.py';
        $cmd = "$pythonPath $crawlMetadataPath $id $fileSource";
        $command = escapeshellcmd($cmd);
        $output = shell_exec($command);
        return $output;
    }
?>