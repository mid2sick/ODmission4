<?php
    // getIDs("/home/nomearod/ODuploadCSV/NDAP.csv", "NDAP");
    function getIDs($filename, $fileSource) {
        // fake a response
        // $jsonTmp = '{"0": "0014220037003", "1":"003-09-01EA-66-2-2-01-00942"}';
        // return $jsonTmp;

        // the original code
        $pythonPath = 'C:\\Users\\Administrator\\AppData\\Local\\Programs\\Python\\Python310\\python.exe';
        $getIDPath = 'C:\\WebRoot\\OD\\ODmission4\\ODtest\\mission2\\getID.py';
        echo "in crawler.php() file name: ".$filename.", source: ".$fileSource."\n";
        $cmd = "$pythonPath $getIDPath $filename $fileSource";
        # $command = "C:\\Users\\Administrator\\AppData\\Local\\Programs\\Python\\Python310 mission2\\getID.py";
        echo $cmd;
        $command = escapeshellcmd($cmd);
        $output = shell_exec($command);
        
        echo "file type: ".gettype($output)."\n";
        echo $output;
        return $output;
    }

    function crawlMetadata($fileSource, $id) {
        $pythonPath = 'C:\\Users\\Administrator\\AppData\\Local\\Programs\\Python\\Python310\\python.exe';
        $getIDPath = 'C:\\WebRoot\\OD\\ODmission4\\ODtest\\mission2\\crawlMetadata.py';
        echo "in crawler.php: crawlMetadata\n";
        $command = escapeshellcmd($pythonPath.' '.$crawlMetadataPath.' '.$id.' '.$fileSource);
        $output = shell_exec($command);
        return $output;
    }
?>