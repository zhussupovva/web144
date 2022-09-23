<div class="page-info">
    <div class="page-header">
        <h2>Категории</h2>
    </div>
    <?php
        $categories = mysqli_query($con, 
        "SELECT * FROM categories");
        
        while($categ = mysqli_fetch_assoc($categories)){
    ?>
        <a href="?category_id=<?=$categ["id"]?>&page=1" class="list-item"><?=$categ["name"]?></a>
    <?php
        }
    ?>
    
</div>