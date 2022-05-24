<!-- upload CSV to a local directory -->
<?php
    if(isset($_POST['submitCSV'])) {
        upload();
    }
    function upload() {
        // [IMPORTANT]: remember to change the below directory!!!
        $target_dir = "/home/nomearod/ODuploadCSV/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if file already exists
        // Need to change files' name in the future work
        if (file_exists($target_file)) {
            $_SESSION['uploadSuccess'] .= "File already exists.<br>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000) {
            $_SESSION['uploadSuccess'] .= "Your file is too large.<br>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "csv") {
            $_SESSION['uploadSuccess'] .=  "Only .csv files are allowed.<br>";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo '<script type="text/javascript">',
                'console.log("Fail to upload file.")',
                '</script>'
            ;
            $_SESSION['uploadSuccess'] .= "Your file was not uploaded.<br>";
        // if everything is ok, try to upload file
        } else {
            // echo "Trying to upload file...<br>";
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $_SESSION['uploadSuccess'] .= "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.<br>";
            } else {
                $_SESSION['uploadSuccess'] .= "There was an error uploading your file.<br>";
            }
        }
    }
?>
