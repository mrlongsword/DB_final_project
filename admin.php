<?php  
session_start();

# If the admin is logged in
if (isset($_SESSION['user_id']) &&
    isset($_SESSION['user_email'])) {

	# Database Connection File
	include "db_conn.php";
	# Book helper function
	include "php/func-book.php";
    $books = get_books_by_admin($conn, $_SESSION["user_id"]);

    # author helper function
	include "php/func-author.php";
    $authors = get_all_author($conn);

    # Category helper function
	include "php/func-category.php";
    $categories = get_all_categories($conn);
	include "php/func-admin.php";
    $user = get_admin($conn, $_SESSION["user_id"]);

	$result = $conn->query('select * from admin;');


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>個人管理</title>

    <!-- bootstrap 5 CDN-->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

    <!-- bootstrap 5 Js bundle CDN-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>

</head>
<body>
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
		  <div class="container-fluid">
		    <a class="navbar-brand" href="admin.php">個人管理頁面</a>
		    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		      <span class="navbar-toggler-icon"></span>
		    </button>
		    <div class="collapse navbar-collapse" 
		         id="navbarSupportedContent">
		      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
		        <li class="nav-item">
		          <a class="nav-link" 
		             aria-current="page" 
		             href="index.php">進入商店</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-book.php">增加商品</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-category.php">增加作品</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="add-author.php">增加角色</a>
		        </li>
		        <li class="nav-item">
		          <a class="nav-link" 
		             href="logout.php">登出</a>
		        </li>
		      </ul>
		    </div>
		  </div>
		</nav>
       <div class="mt-5"></div>
        <?php if (isset($_GET['error'])) { ?>
          <div class="alert alert-danger" role="alert">
			  <?=htmlspecialchars($_GET['error']); ?>
		  </div>
		<?php } ?>
		<?php if (isset($_GET['success'])) { ?>
          <div class="alert alert-success" role="alert">
			  <?=htmlspecialchars($_GET['success']); ?>
		  </div>
		<?php } ?>


        <?php  if ($books == 0) { ?>
        	<div class="alert alert-warning 
        	            text-center p-5" 
        	     role="alert">
        	     <img src="img/empty.png" 
        	          width="100">
        	     <br>
			  現在沒有商品，快來新增一個吧!
		  </div>
        <?php }else {?>
		<!-- 個人資料 -->
		<h4>個人資料</h4>
		<table class="table table-bordered shadow">
			<thead>
				<tr>
					<th>姓名</th>
					<th>購買方式</th>
					<th>寄送方式</th>
					<th>個人FB</th>
					<th>編輯資料</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th><?= $user['full_name'] ?></th>
					<th><?= $user['購買方式'] ?></th>
					<th><?= $user['寄送方式'] ?></th>
					<td>
					<a href="<?=$user['fb網址']?>" 
					   class="btn btn-success">
					   連到FB網址</a>
				</td>
					<td>
					<a href="edit-admin.php?id=<?=$user['id']?>" 
					   class="btn btn-warning">
					   編輯個人資料</a>
				</td>
				</tr>
			</tbody>
		</table>

        <!-- List of all books -->
		<h4>所有商品</h4>
		<table class="table table-bordered shadow">
			<thead>
				<tr>
					<th>#</th>
					<th>品名</th>
					<th>價格</th>
					<th>尺寸</th>
					<th>廠牌</th>
					<th>角色名稱</th>
					<th>作品名稱</th>
					<th>詳細介紹</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
			  <?php 
			  $i = 0;
			  foreach ($books as $book) {
			    $i++;
			  ?>
			  <tr>
				<td><?=$i?></td>
				<td>
					<img width="100"
					     src="uploads/cover/<?=$book['cover']?>" >
					<a  class="link-dark d-block
					           text-center"
					    href="uploads/files/<?=$book['file']?>">
					   <?=$book['title']?>	
					</a>
						
				</td>
				
				<td><?=$book['price']?></td>
				<td><?=$book['size']?></td>
				<td><?=$book['廠牌']?></td>
				<td>
					<?php if ($authors == 0) {
						echo "Undefined";}else{ 

					    foreach ($authors as $author) {
					    	if ($author['id'] == $book['author_id']) {
					    		echo $author['name'];
					    	}
					    }
					}
					?>

				</td>
				<td>
					<?php if ($categories == 0) {
						echo "Undefined";}else{ 

					    foreach ($categories as $category) {
					    	if ($category['id'] == $book['category_id']) {
					    		echo $category['name'];
					    	}
					    }
					}
					?>
				</td>
				<td><?=$book['description']?></td>
				
				<td>
					<a href="edit-book.php?id=<?=$book['id']?>" 
					   class="btn btn-warning">
					   編輯商品資料</a>

					<a href="php/delete-book.php?id=<?=$book['id']?>" 
					   class="btn btn-danger">
				       刪除此商品</a>
				</td>
			  </tr>
			  <?php } ?>
			</tbody>
		</table>
	   <?php }?>

        <?php  if ($categories == 0) { ?>
        	<div class="alert alert-warning 
        	            text-center p-5" 
        	     role="alert">
        	     <img src="img/empty.png" 
        	          width="100">
        	     <br>
				現在沒有角色，快來新增你要販售的角色吧!
		    </div>
        <?php }else {?>
	    <!-- List of all categories -->
		<h4 class="mt-5">所有作品</h4>
		<table class="table table-bordered shadow">
			<thead>
				<tr>
					<th>#</th>
					<th>作品名稱</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$j = 0;
				foreach ($categories as $category ) {
				$j++;	
				?>
				<tr>
					<td><?=$j?></td>
					<td><?=$category['name']?></td>
					<td>
						<a href="edit-category.php?id=<?=$category['id']?>" 
						   class="btn btn-warning">
						   編輯作品名稱</a>

						<a href="php/delete-category.php?id=<?=$category['id']?>" 
						   class="btn btn-danger">
					       刪除此作品</a>
					</td>
				</tr>
			    <?php } ?>
			</tbody>
		</table>
	    <?php } ?>

	    <?php  if ($authors == 0) { ?>
        	<div class="alert alert-warning 
        	            text-center p-5" 
        	     role="alert">
        	     <img src="img/empty.png" 
        	          width="100">
        	     <br>
				 現在沒有作品，快來新增你要販售的作品吧!
		    </div>
        <?php }else {?>
	    <!-- List of all Authors -->
		<h4 class="mt-5">所有角色</h4>
         <table class="table table-bordered shadow">
			<thead>
				<tr>
					<th>#</th>
					<th>角色名稱</th>
					<th>操作</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$k = 0;
				foreach ($authors as $author ) {
				$k++;	
				?>
				<tr>
					<td><?=$k?></td>
					<td><?=$author['name']?></td>
					<td>
						<a href="edit-author.php?id=<?=$author['id']?>" 
						   class="btn btn-warning">
						   編輯角色名稱</a>

						<a href="php/delete-author.php?id=<?=$author['id']?>" 
						   class="btn btn-danger">
					       刪除此角色</a>
					</td>
				</tr>
			    <?php } ?>
			</tbody>
		</table> 
		<?php } ?>
	</div>
</body>
</html>

<?php }else{
  header("Location: login.php");
  exit;
} ?>