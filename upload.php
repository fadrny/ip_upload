<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Upload souboru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    </head>
<body>
<?php

error_reporting(0);

//var_dump($_POST);
//var_dump($_FILES);


if ($_FILES) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES['uploadedName']['name']);
    $fileType = strtolower( pathinfo( $targetFile, PATHINFO_EXTENSION ) );
    $kind = explode('/', $_FILES['uploadedName']['type'])[0];

    $uploadSuccess = true;

    if ($_FILES['uploadedName']['error'] != 0) {
        echo "Chyba serveru při uploadu";
        $uploadSuccess = false;

    }

    //kontrola existence
    elseif (file_exists($targetFile)) {
        echo "Soubor již existuje";
        $uploadSuccess = false;
    }

    //kontrola velikosti
    elseif ($_FILES['uploadedName']['size'] > 8000000) {
        echo "Soubor je příliš velký";
        $uploadSuccess = false;
    }


    //kontrola typu
    elseif ($kind !== "image" && $kind !== "audio" && $kind !== "video" ) {
        echo "Soubor má špatný typ";
        $uploadSuccess = false;
    }


    if ( !$uploadSuccess) {
        echo " => Došlo k chybě uploadu";
    } else {
        //vše je OK
        //přesun souboru
        if (move_uploaded_file($_FILES['uploadedName']['tmp_name'], $targetFile)) {
            echo " => Soubor '". basename($_FILES['uploadedName']['name']) . "' byl uložen.";
        } else {
            echo " => Došlo k chybě uploadu";
        }
    }

}

?>
<div class="container">
    <form method='post' action='' enctype='multipart/form-data' style="margin-top: 4em">
        <div class="mb-3">
            <label for="upl" class="form-label">Upload souboru</label>
            <input class="form-control" type="file" name="uploadedName" id="upl" accept="image/*, audio/*, video/*,"/>
            <input type="submit" class="btn btn-primary" value="Nahrát" name="submit" style="margin: 1em 0; width: 100%"/>
        </div>
    </form>
    <div class="media">

        <?php

        if($kind === "image"){
            echo "<img src='${targetFile}' style='width: 100%'>";
        }
        else if($kind === "audio"){
                echo "<audio controls loop autoplay style='width: 100%'> <source src='${targetFile}' type='audio/{$fileType}'> nepodporováno </audio>";
        }
        else if($kind === "video"){
            echo "<video controls autoplay loop muted width='100%'> <source src='${targetFile}' type='audio/{$fileType}'> nepodporováno </video>";
        }

        ?>

    </div>
</div>
</body>
</html>