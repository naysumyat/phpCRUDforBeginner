<?php 
// Start the session
session_start();

function pretty_print($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

/**
 * save into session for formdata (temporary)
 */
function flash($array){
    $_SESSION['formdata'] = $array;
}


/**
 * output form value stored in session (just one time)
 */
function old($key){
    $value =  isset($_SESSION['formdata'][$key]) ? $_SESSION['formdata'][$key] : null;
    if($value){
        unset($_SESSION['formdata'][$key]);
    }
    return $value;
}


$dbname = "phpCRUD2020";
$servername = "localhost";
$username = "root";
$password = "root";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 // echo "Connected successfully";
} catch(PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}

/**
 * SELECT Data
 */
function getPosts($search, $category, $offset=0, $limit=2, $count=false){
    global $conn;
    $query = "SELECT * FROM `posts` ";
    if($search){
        $query .= "WHERE `title` LIKE :search ";
    }
    if($category){
        $query .= $search ? " AND " : " WHERE ";
        $query .= " `category` = :category ";
    }

    $query .= "ORDER BY `created_at` DESC  LIMIT :limit OFFSET :offset";

    $stmt = $conn->prepare($query);
    
    if($search){
        $search = "%".$search."%";
        $stmt->bindParam(":search", $search);
    }
    if($category){
        $stmt->bindParam(":category", $category);
    }
    $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
    $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);

    $stmt->execute();
    // set the resulting array to associative
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetchAll();
}


/**
 * SELECT Data Count
 */
function getPostsCount($search, $category){
    global $conn;
    $query = "SELECT COUNT(*) as total FROM `posts` ";
    if($search){
        $query .= "WHERE `title` LIKE :search ";
    }
    if($category){
        $query .= $search ? " AND " : " WHERE ";
        $query .= " `category` = :category ";
    }

    $query .= "ORDER BY `created_at` DESC ";

    $stmt = $conn->prepare($query);
    
    if($search){
        $search = "%".$search."%";
        $stmt->bindParam(":search", $search);
    }
    if($category){
        $stmt->bindParam(":category", $category);
    }

    $stmt->execute();
    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetchColumn();
}



/**
* INSERT Data
*/
function insertPost($new_post){
    global $conn;
    $query = "INSERT INTO `posts`(`title`, `thumbnail`, `description`, `category`) 
              VALUES (:title, :thumbnail, :description, :category)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":title", $new_post['title']);
    $stmt->bindValue(":thumbnail", $new_post['thumbnail']);
    $stmt->bindParam(":description", $new_post['description']);
    $stmt->bindParam(":category", $new_post['category']);
    return $stmt->execute();
}



/**
* DELETE Data
*/

function deletePost($id){
    global $conn;
    $query = "DELETE FROM `posts` WHERE `id`=:id ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
}

/**
* Find single Post by Id
*/
function findPostById($id){
    global $conn;

    try{

    $query = "SELECT * FROM `posts` WHERE `id`=:id ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt->fetch();

    } catch(PDOException $e) {
     print($e->getMessage());
   }
}


/**
* UPDATE Data
*/
function updatePost($id, $update_post){
    global $conn;
    try{
    $query = "UPDATE `posts` SET `title`=:title,`description`=:description,`category`=:category ";
    if(!empty($update_post['thumbnail'])){
    $query .= " ,`thumbnail`=:thumbnail ";
    }
    $query .=" WHERE `id`=:id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":title", $update_post['title']);
    $stmt->bindParam(":description", $update_post['description']);
    $stmt->bindParam(":category", $update_post['category']);
    if(!empty($update_post['thumbnail'])){
    $stmt->bindValue(":thumbnail",$update_post['thumbnail']);
    }
    $stmt->bindParam(":id", $id);
    return $stmt->execute();

     } catch(PDOException $e) {
     print($e->getMessage());
   }

}




// $posts = [
//                 [
//                     "id"=> 1,
//                     "title"=> "Cute bunny in a busket",
//                     "thumbnail"=> "https://i.imgur.com/Jjn9zim.jpg",
//                     "description"=> "Description 1",
//                     "created_at"=> "2020-01-13 15:25:42",
//                     "category"=>"sports"
//                 ],
//                 [
//                     "id"=> 2,
//                     "title"=> "2 Rabbits lying on the grass",
//                     "thumbnail"=> "https://lh3.googleusercontent.com/proxy/WC7p9NmdzZZoQfMtC3PXEipGFJNTSADfXWg4W8UEXaLrRoBvPJahFsIHNO-DHw_zYV3_qt0FWSzGTRvzgv_pSZ6BCg7CsmO_fkAiipPfzQbBDPvXC80Imrrp5fOKmOrajnkJUiUnUWg",
//                     "description"=> "Description 2",
//                     "created_at"=> "2020-01-13 18:25:42",
//                     "category"=>"health"
//                 ],
//                 [
//                     "id"=> 3,
//                     "title"=> "Gorgerous Dutch Staring at Camera",
//                     "thumbnail"=> "https://images.unsplash.com/photo-1518796745738-41048802f99a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&w=1000&q=80",
//                     "description"=> "Description 3",
//                     "created_at"=> "2020-01-13 17:25:42",
//                     "category"=>"sports"
//                 ],
// ];


