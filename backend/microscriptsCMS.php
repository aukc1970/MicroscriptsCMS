<?php
require_once("options.php");
$txtFieldNames = [];
$fileFieldNames = [];
if(file_exists(__DIR__."/viewSources.txt")){
    $sources = file_get_contents(__DIR__."/viewSources.txt");
    $sources = preg_replace( "/\r|\n/", "", $sources );
    $pages = explode("|||", $sources);
    foreach($pages as $page){
        $elementsOnPage = explode(";", $page);
        foreach($elementsOnPage as $el){
            $elementContent = explode(",", $el);
            if(in_array("ml_txt", $elementContent) || in_array("txt", $elementContent)){
                array_push($txtFieldNames, $elementContent[1]);
            }elseif(in_array("img", $elementContent)){
                array_push($fileFieldNames, $elementContent[1]);
            }
        }
    }
}else{
    echo "Sources not found";
    exit;
};
$savesFileDirName = __DIR__.'/'.$savesFileDirName;
if(isset($_POST['Name'])){login($correctUN,$correctPW);};
if(isset($_POST['password'])){save();};
if(!isset($_POST['password']) && !isset($_POST['Name'])){return $savedData = loadFile(0);};

function login($correctUN,$correctPW){
    global $loginFallback;
    $name = $_POST['Name'];
    $password = $_POST['Password'];
    if($name == $correctUN && $password == $correctPW){
        backend();
    }else{
        echo '<script>window.location = "'. $loginFallback .'";</script>';
    };
}

function save(){
    global $txtFieldNames , $fileFieldNames , $savesFileDirName , $allowedFileTypes , $fileUploadDir , $uploadedFilesDir , $maxFileSizeInMB , $loginFallback;
    $txtFieldNames = explode(',', $txtFieldNames);
    $fileFieldNames = explode(',', $fileFieldNames);
    $allowedFileTypes = explode(',', $allowedFileTypes);

    $data = loadFile(0);
    foreach ($txtFieldNames as $field) {
        ${$field} = nl2br(htmlspecialchars($_POST["".$field.""]));
        if(isset($field)){ /* Check if field is already set */
            $data[$field] = ${"". $field .""};  /* Update set field */
        }else{
            $data += [$field => ${"". $field .""}]; /* Create new field */
        }
    }

    foreach($fileFieldNames as $fileField){
        if(isset($_FILES[$fileField])){
            $file = $_FILES[$fileField];
            $fileExtention = explode('.', $file["name"]);
            $fileExtention = strtolower(end($fileExtention));
            if($file["error"] != 4){   /* Check if anything was uploaded */
                if($file["error"] === 0){   /* Check if upload was successful */
                    if(in_array($fileExtention, $allowedFileTypes)){    /* Check if uploaded file is valid file type */
                        if($file["size"] < ($maxFileSizeInMB * 100000)){    /* Check if uploaded file isn´t to big */
                            $fileDest = $fileUploadDir.$fileField.'.'.$fileExtention;
                            $fileRequestDest = $uploadedFilesDir.$fileField.'.'.$fileExtention;
                            move_uploaded_file($file["tmp_name"], $fileDest);   /* Actual Upload */
                            if(isset($fileField)){ /* Check if fileField is already set */
                                $data[$fileField] = $fileRequestDest;  /* Update set fileField */
                            }else{
                                $data += [$fileField => $fileRequestDest]; /* Create new fileField */
                            }
                        }else{
                            echo "Your file is to big! A maximum of ". $maxFileSizeInMB ."MB are supported!";
                        }
                    }else{
                        echo "File type not supported! Allowed file types are: ". implode(", ",$allowedFileTypes) ." !";
                    }
                }else{
                    echo "An error occurred during Upload!";
                }
            }
        }
    }

    $data = json_encode($data);
    $file = fopen($savesFileDirName,"w");
    fwrite($file, $data);
    fclose($file);
    echo '<script>window.location = "../'. $loginFallback .'";</script>';
    die();
}

function loadFile($removeBr){
    global $savesFileDirName;
    if(file_exists($savesFileDirName)){
        $savedData = file_get_contents($savesFileDirName);
        if($removeBr == 1){
            $savedData = str_replace("<br \/>","", $savedData);
        }
        $savedData = json_decode($savedData,true);
        return $savedData;
    }else{
        echo "File not found";
        exit;
    };
}

function backend(){
    global $loginFallback;
    $savedData = loadFile(1);
    require_once("backendView.php");
}