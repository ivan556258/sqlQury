<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


require_once ($_SERVER['DOCUMENT_ROOT'] . '/backEnd/controller/DB.php');

class SqlQuery {

    public function InsertText($array, $nameTable) {
        // build query...
        $sql = "INSERT INTO " . $nameTable;

        // implode keys of $array...
        $sql .= " (`" . implode("`, `", array_keys($array)) . "`)";

        // implode values of $array...
        $sql .= " VALUES ('" . implode("', '", $array) . "') ";

        $stmt = DB::run($sql);

        $e = DB::lastInsertId();

        return $e;
    }

    public function InsertTextHashtag($lastId, $array, $nameString, $nameTable, $idParent) {



   

        foreach ($array as $key) {

        // build query...
        $sql = "INSERT INTO " . $nameTable;

        // implode keys of $array...
        $sql .= " (`" . $idParent . "`, `" . $nameString . "`)";

        // implode values of $array...
        $sql .= " VALUES ('" . $lastId . "', '" . $key . "') ";

        $stmt = DB::run($sql);


        }

       
    }

    public function UpdateTextHashtag($lastId, $array, $nameString, $nameTable, $idParent) {



        $delSql = "DELETE FROM `".$nameTable."` WHERE `".$idParent."`='".$lastId."'";

        $delRes = DB::run($delSql);

        foreach ($array as $key) {

        // build query...
        $sql = "INSERT INTO " . $nameTable;

        // implode keys of $array...
        $sql .= " (`".$idParent."`, `".$nameString."`)";

        // implode values of $array...
        $sql .= " VALUES ('".$lastId."', '".$key."') ";

        $stmt = DB::run($sql);

        }
       
    }

    public function UpdateText($array, $id, $nameTable, $tableImg) {// 1 - array with data $_POST. 2 - id by object. 3 Table by data text. 4 3 Table by data image

        $search = "SELECT `name` FROM ".$tableImg." WHERE `idParent`='".$id."'";


        $searchX = DB::run($search);


        foreach ($searchX as $file) {

        if($file["name"]!=""){
           unlink($file["name"]);
        }
        }





        $delSql = "DELETE FROM ".$tableImg." WHERE `idParent`='".$id."'";

        $delRes = DB::run($delSql);
        // build query...

        $arrVal = $array;

        $arrKey = array_keys($array);


        $all = [];

        foreach ($arrKey as $key => $val) {
            $all[] = "`" . $arrKey[$key] . "` = '" . $arrVal[$val] . "'";
        }

        $allStrong = implode(",", $all);

        $sql = "UPDATE " . $nameTable . " SET";

        $sql .= $allStrong;

        $sql .= " WHERE `id`='" . $id . "'";

        $stmt = DB::run($sql);



    }


    public function UpdateOnlyText($array, $id, $nameTable) {// 1 - array with data $_POST. 2 - id by object. 3 Table by data text. 4 

        $arrVal = $array;

        $arrKey = array_keys($array);


        $all = [];

        foreach ($arrKey as $key => $val) {
            $all[] = "`" . $arrKey[$key] . "` = '" . $arrVal[$val] . "'";
        }

        $allStrong = implode(",", $all);

        $sql = "UPDATE " . $nameTable . " SET ";

        $sql .= $allStrong;

        $sql .= " WHERE `id`='" . $id . "'";

        $stmt = DB::run($sql);





    }

    public function ImageInsert($id, $theName, $tableImg){
if(!$_FILES[$theName]){
            $prewSqlImg = "INSERT INTO ".$tableImg." (`idParent`) VALUES ('".$id."')";

            $stmt3 = DB::run($prewSqlImg);

            return 0;
}
else{
$today = date("Y/m");
$path =  '/backEnd/view/cms/image/' . $today;

if (!file_exists($_SERVER['DOCUMENT_ROOT']."/".$path)) {    // проверяем если такого пути нет, то создаём новый
    mkdir($_SERVER['DOCUMENT_ROOT']."/".$path, 0755, true); // новый калог с правами 
}

$help = $_SERVER['DOCUMENT_ROOT'];

foreach ($_FILES[$theName]["error"] as $key_objectNewBuilds => $error_objectNewBuilds) {
    if ($error_objectNewBuilds == UPLOAD_ERR_OK) {
        $tmp_name_objectNewBuilds = $_FILES[$theName]["tmp_name"][$key_objectNewBuilds];
        // basename() может спасти от атак на файловую систему;
        // может понадобиться дополнительная проверка/очистка имени файла
        $original_name_objectNewBuilds = basename($_FILES[$theName]["name"][$key_objectNewBuilds]);
        //Получить расширение файла
        $extension_objectNewBuilds = pathinfo($original_name_objectNewBuilds, PATHINFO_EXTENSION);

        //Придумать новое имя файла с расширением загружаемого файла
        $new_name_objectNewBuilds = uniqid() . '.' . $extension_objectNewBuilds;
        move_uploaded_file($tmp_name_objectNewBuilds, "$help/$path/$new_name_objectNewBuilds");

        $name = $path . '/' . $new_name_objectNewBuilds;

        $prewSqlImg = "INSERT INTO ".$tableImg." (`idParent`, `name`) VALUES ('".$id."', '".$name."')";

        $stmt3 = DB::run($prewSqlImg);



            }
        

        }

   }
        return 1;

    }

    public function SelectText($array, $nameTable, $nameId, $id) {
        // build query...
        $sql = "SELECT ";

        // implode values of $array...
        $sql .= "`" . implode("`, `", $array) . "`";

        $sql .= " FROM " . $nameTable;

            
        $sql .= " WHERE `" . $nameId . "` = '" . $id . "' LIMIT 1";
       

        $stmt = DB::run($sql);

        $result = $stmt->fetch();

        return $result;
    }

    public function SelectTextAll($array, $nameTable, $nameId, $id) {
        // build query...
        $sql = "SELECT ";

        // implode values of $array...
        $sql .= "`" . implode("`, `", $array) . "`";

        $sql .= " FROM " . $nameTable;

            
        $sql .= " WHERE `" . $nameId . "` = '" . $id . "' AND `" . $nameId . "` IS NOT NULL ORDER BY id DESC";
       

        $stmt = DB::run($sql);

        $result = $stmt->fetchAll();

        return $result;
    }

    public function SelectJustTextAll($array, $nameTable) {
        // build query...
        $sql = "SELECT ";

        // implode values of $array...
        $sql .= "`" . implode("`, `", $array) . "`";

        $sql .= " FROM " . $nameTable ." ORDER BY id DESC";

        $stmt = DB::run($sql);

        $result = $stmt->fetchAll();

        return $result;
    }

    public function DeleteText($nameTable, $nameId, $id) {

        // build query...
        $sql = "DELETE FROM ";

        $sql .= $nameTable;
 
        $sql .= " WHERE `" . $nameId . "` = '" . $id . "'";
       
        $stmt = DB::run($sql);

    }

    public function DeleteImg($nameTable, $fieldName, $nameId, $id) {


        $search = "SELECT `".$fieldName."` FROM ".$nameTable." WHERE `".$nameId."`='".$id."'";


        $searchX = DB::run($search);


        foreach ($searchX as $file) {


              unlink($_SERVER['DOCUMENT_ROOT'].$file["name"]);


        
        }

        // build query...
        $sql = "DELETE FROM ";

        $sql .= $nameTable;
 
        $sql .= " WHERE `" . $nameId . "` = '" . $id . "'";
       
        $stmt = DB::run($sql);
        
    }

        public function AccessUpdateText($active, $id, $nameTable) {// 1 - array with data $_POST. 2 - id by object. 3 Table by data text. 


        // build query...

        $sql = "UPDATE " . $nameTable . " SET";

        $sql .= " `active`='".$active."'";

        $sql .= " WHERE `idUnic`='" . $id . "'";

        $stmt = DB::run($sql);

    }


}








