<form action="backend/microscriptsCMS.php" method="post" enctype="multipart/form-data">
<nav>
    <a href=<?php echo '"'.$loginFallback.'"'; ?>>⬅ Home</a> 
    <h1>Edit website contents</h1>
</nav>
<?php

function getData(){
    if(file_exists("viewSources.txt")){
        $data = file_get_contents("viewSources.txt");
        $data = preg_replace( "/\r|\n/", "", $data );
        return $data;
    }else{
        echo "File not found";
        exit;
    };
}

$pages = explode("|||", getData());
foreach($pages as $page){
    echo '<div class="page">';
    $elementsOnPage = explode(";", $page);
    foreach($elementsOnPage as $el){
        $elementContent = explode(",", $el);
        if(in_array("ml_txt", $elementContent)){
            echo '<label for="'. $elementContent[1] .'">'. $elementContent[2] .'</label><br><textarea name="'. $elementContent[1] .'" cols="100" rows="10">'. $savedData[$elementContent[1]] .'</textarea>'; 
        }elseif(in_array("txt", $elementContent)){
            echo '<label for="'. $elementContent[1] .'">'. $elementContent[2] .'</label><br><input type="text" name="'. $elementContent[1] .'" value='. $savedData[$elementContent[1]] .'>'; 
        }elseif(in_array("img", $elementContent)){
            echo '<label for="'. $elementContent[1] .'">'. $elementContent[2] .'</label><br><input type="file" name="'. $elementContent[1] .'">';
        }elseif(in_array("title", $elementContent)){
            echo '<h2>'. $elementContent[1] .'</h2>';
        }elseif(in_array("sub_title", $elementContent)){
            echo '<h3>'. $elementContent[1] .'</h3><hr>';
        }else{
            echo 'Error: <strong style="color:red">'.$elementContent[0].'</strong> invalid field type!';
        }
    }
    echo '</div>';
}
?>

<div class="save">
    <input type="password" name="password" placeholder="Password">
    <input type="submit" value="Save">
</div>

</form>