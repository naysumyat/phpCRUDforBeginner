<?php 
require("functions.php");
$errors   = array();

if(isset($_POST['submit'])){
    $new_post = array();
    //save in session
     flash($_POST);

    //validate title 
    $pattern = '/[^a-zA-Z0-9\s]+/';
    preg_match($pattern, $_POST['title'], $matches);
    if(empty($_POST['title']) || strlen($_POST['title']) < 5 || count($matches)> 0 ){
        $errors['title'] = "Title must be at least 5 characters in length and no symbols allowed!";
    }else{
       $new_post['title'] = $_POST['title']; 
    }

    //validate category
    if(empty($_POST['category']) || strlen($_POST['category']) < 4 ){
        $errors['category'] = "Category must be at least 4 characters in length!";
    }else{
       $new_post['category'] = $_POST['category']; 
    }

    //validate description
    if(empty($_POST['description']) || strlen($_POST['description']) < 15 ){
        $errors['description'] = "Description must be at least 15 characters in length!";
    }else{
       $new_post['description'] = $_POST['description']; 
    }

    if(!empty($_FILES['thumbnail']['name'])){
        $thumbnail = $_FILES['thumbnail'];
        $allowed_types = ["image/jpg","image/png","image/jpeg"];
        if(!in_array($thumbnail['type'], $allowed_types) || $thumbnail['size'] > 2000000 ){
        $errors['thumbnail'] = "File type must be jpeg or jpg or png image and must not be larger than 2MB.";
        }else{
        //file
         $filename = time().'_'.$thumbnail['name'];
         move_uploaded_file($thumbnail['tmp_name'], "uploads/".$filename);
         $new_post['thumbnail'] = $filename;
        }
    }
    

    if(count($errors)==0){
       // pretty_print($new_post);
        // insert into db
        if(insertPost($new_post)){
            header("Location:index.php");
        }else{
            $errors['save'] = "Something went wrong while saving the post.Please try again.";
        }

    }


}

?>
<?php require("includes/header.php"); ?>


    <div class="container">

        <h2 class="py-3">Create a New Blog Post</h2>

        <?php if(isset($errors['save'])): ?>
            <p class="alert alert-danger"><?php echo $errors['save']; ?></p>
        <?php endif; ?>
        
        <form action="" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control"  value="<?php echo old('title'); ?>" >
                <?php if(isset($errors['title'])): ?>
                    <p class="text-danger"><?php echo $errors['title']; ?></p>
                <?php endif; ?>
            </div> 
            <div class="form-group">
                <label>Photo</label>
                <input type="file" name="thumbnail" class="form-control" >
                <?php if(isset($errors['thumbnail'])): ?>
                    <p class="text-danger"><?php echo $errors['thumbnail']; ?></p>
                <?php endif; ?>
            </div> 
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" class="form-control" value="<?php echo old('category'); ?>">
                <?php if(isset($errors['category'])): ?>
                    <p class="text-danger"><?php echo $errors['category']; ?></p>
                <?php endif; ?>
            </div>  
            <div class="form-group">
                <label>Description</label>
                <?php if(isset($errors['description'])): ?>
                    <p class="text-danger"><?php echo $errors['description']; ?></p>
                <?php endif; ?>
                <textarea name="description" class="form-control"><?php echo old('description'); ?></textarea>
            </div>  
            <div class="form-group">
                <button name="submit" class="btn btn-primary">Submit</button>
                <a href="index.php" class="btn btn-default">Cancel</a>
            </div>
            
        </form>

    </div>

  <?php require("includes/footer.php"); ?>

