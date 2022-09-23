<?php
	include "config/base_url.php";
	include "config/db.php";
	include "common/time.php";

	$q="";
	$limit=2;
	$page=1;
	$categor="";
	$sql = "SELECT b.*, u.nickname, c.name FROM blogs b
	LEFT OUTER JOIN users u ON u.id=b.author_id
	LEFT OUTER JOIN categories c ON c.id=b.category_id";


	$sql_count="SELECT CEIL(COUNT(*)/$limit) as total
	FROM blogs b
	LEFT OUTER JOIN users u ON u.id=b.author_id
	LEFT OUTER JOIN categories c ON c.id=b.category_id";

	if(isset($_GET["category_id"])){
		$categor=$_GET["category_id"];
		$sql .= " WHERE b.category_id=$categor";
		$sql_count .= "WHERE b.category_id=$categor";
	}

	if(isset($_GET["q"])){
		$q=strtolower($_GET["q"]);
		$sql .= " WHERE LOWER(b.title) LIKE ? OR 
		LOWER(b.description) LIKE ? OR 
		LOWER(c.name) LIKE ? OR
		LOWER(u.nickname) LIKE ?";
		$sql_count .= " WHERE LOWER(b.title) LIKE ? OR 
		LOWER(b.description) LIKE ? OR 
		LOWER(c.name) LIKE ? OR
		LOWER(u.nickname) LIKE ?";
	}

	if($q){
		if($_GET["page"] && intval($_GET["page"])){
			// $page=1;
			// $skip=0;
			$page=$_GET["page"];
			$skip=($page-1) * $limit;
			$sql .= "LIMIT $skip, $limit";
		}else{
			$sql .= " LIMIT $limit";
		}

		$param="%$q%";

		$prep_count=mysqli_prepare($con,$sql_count);
		mysqli_stmt_bind_param($prep_count, "ssss", $param,  $param, $param, $param);
		mysqli_stmt_execute($prep_count);
		$query_count=mysqli_stmt_get_result($prep_count);
		$count=mysqli_fetch_assoc($query_count);


		$prep=mysqli_prepare($con, $sql);
		mysqli_stmt_bind_param($prep, "ssss", $param,  $param, $param, $param);
		mysqli_stmt_execute($prep);

		$query_blog = mysqli_stmt_get_result($prep);
	}else{
		if($_GET["page"] && intval($_GET["page"])){
			$page=$_GET["page"];
			$skip=($page-1) * $limit;
			$sql .= " LIMIT $skip, $limit";
		}else{
			$sql .= " LIMIT $limit";
		}
		$query_blog = mysqli_query($con, $sql);
		$query_count=mysqli_query($con, $sql_count);
		$count=mysqli_fetch_assoc($query_count);
	}



?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Главная</title>
    <?php include "views/head.php"; ?>
</head>
<body>
<?php include "views/header.php"; ?>


<section class="container page">
	<div class="page-content">
			<h2 class="page-title">Блоги по программированию</h2>
			<p class="page-desc">Популярные и лучшие публикации по программированию для начинающих
 и профессиональных программистов и IT-специалистов.</p>

		<div class="blogs">
		<?php
			if(mysqli_num_rows($query_blog)>0){
				while($blog = mysqli_fetch_assoc($query_blog)){
		?>
			<div class="blog-item">
				<img class="blog-item--img" src="<?=$BASE_URL?><?=$blog["img"]?>" alt="">
				<div class="blog-header">
					<h3> <a href="<?=$BASE_URL?>/blog-details.php?id=<?=$blog["id"]?>"><?=$blog["title"]?></a></h3>
				</div>
				<p class="blog-desc"><?=$blog["description"]?></p>

				<div class="blog-info">
					<span class="link">
						<img src="<?=$BASE_URL?>/images/date.svg" alt="">
						<?= to_time_ago(strtotime($blog["date"]))  ?>
					</span>
					<span class="link">
						<img src="images/visibility.svg" alt="">
						21
					</span>
					<a class="link">
						<img src="images/message.svg" alt="">
						4
					</a>
					<span class="link">
						<img src="images/forums.svg" alt="">
						<?=$blog["name"]?>
					</span>
					<a class="link" href="<?=$BASE_URL?>/profile.php?nickname=<?=$blog["nickname"]?>">
						<img src="images/person.svg" alt="">
						<?=$blog["nickname"]?>
					</a>
				</div>
			</div>
		<?php	
				}
			}else{
		?>

			<h3>0 blogs</h3>
		<?php
			}
		?>
			
		<?php
			$cat_str="";
			if($categor){
				$cat_str="&category_id=$categor";
				echo $cat_str;
			}

			$q_str="";
			if($q){
				$q_str="&q=$q";

			}
			if($page != 1){
		?>
			<a class="pagination-item" href="?page=<?=$page-1?><?=$cat_str?><?=$q_str?>">Prev</a>
		<?php
			}
			for($i=1;$i<=$count["total"]; $i++){
		?>
			<a class="pagination-item" href="?page=<?=$i?><?=$cat_str?><?=$q_str?>"><?=$i?></a>
		<?php
			}
			if($page != $count["total"]){
		?>
			<a class="pagination-item" href="?page=<?=$page+1?><?=$cat_str?><?=$q_str?>">Next</a>
		<?php
			}
		?>
		

		</div>
	</div>
	<?php
		include "views/categories.php";
	?>
</section>	
</body>
</html>