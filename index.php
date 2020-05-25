<?php  
require("functions.php");

$delete_id = isset($_GET['delete_id']) ? $_GET['delete_id'] : null;
if($delete_id){
    if(deletePost($delete_id)){
        header("Location:index.php?delete_success=true");
    }else{
        header("Location:index.php?delete_success=false");
    }
}

// sort the array by created_at in DESC
// usort($posts, function($a, $b) {
//     return $b['created_at'] <=> $a['created_at'];
// });

// search by title
 $search = isset($_GET['q']) ? $_GET['q'] : null;
 if($search != null){
//     $posts = array_filter($posts, function($item) use ($search) {
//         return  is_numeric(stripos($item['title'], $search));
//    });
  flash(["q"=>$search]);
  }

 // filter by category
 $category = isset($_GET['category']) ? $_GET['category'] : null ;
//  if($category != null){
//  $posts = array_filter($posts, function($item) use ($category) {
//         return $item['category'] == $category;
//  });
// }

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 2;
$offset = ($page - 1) * $limit;
$total_result = getPostsCount($search, $category);
$total_pages = ceil($total_result/$limit); 
$posts = getPosts($search, $category, $offset, $limit);


 // pretty_print($posts);

?>
<?php require("includes/header.php"); ?>


    <div class="container-fluid">
            
    <?php require("includes/search_form.php"); ?>

        <p class="alert alert-info">
            There are <?php echo count($posts); ?> posts in total.
        </p>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Photo</th>
                    <th>Category</th>
                    <th>CreatedAt</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach($posts as $post):  ?>

                <tr>
                    <td>
                        <?php echo $post["id"]; ?>
                    </td>
                    <td>
                        <?php echo $post['title']; ?>
                    </td>
                    <td>
                       <img  src="uploads/<?php echo $post['thumbnail']; ?>" style="width:100px;"  />
                    </td>
                    <td>
                        <?php echo $post['category']; ?>
                    </td>
                    <td>
                        <?php echo $post['created_at']; ?>
                    </td>
                    <td>
                        <a onclick="return confirm('Are you sure to delete this?');" href="?delete_id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                        <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-info">Edit</a>
                        <a href="view.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>

            <?php endforeach; ?>

            </tbody>
        </table>

        <hr/>

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php if($page>1): ?> 
                 <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a></li> 
                <?php endif; ?>

                <?php for($i=1;$i<=$total_pages;$i++): ?>
                <li class="page-item <?php echo ($page==$i) ? 'active' : '';  ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>
                <?php if($page<$total_pages): ?>
                 <li class="page-item"><a class="page-link" href="?page=<?php echo $page+1; ?>">Next</a></li> 
                <?php endif; ?>

            </ul>
        </nav>


    </div>

  <?php require("includes/footer.php"); ?>
