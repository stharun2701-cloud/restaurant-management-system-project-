<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search | Cuisine Cloud</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

   <style>
      #loader, .loader { display: none !important; }

      /* --- SEARCH CONTAINER --- */
      .search-wrapper {
         max-width: 85rem;
         margin: 5rem auto 2rem;
         display: flex;
         align-items: center;
         gap: 2rem;
         padding: 0 2rem;
      }

      .back-btn-side {
         height: 5rem;
         width: 5rem;
         line-height: 5rem;
         background: #fff;
         color: var(--black);
         border-radius: 50%;
         text-align: center;
         font-size: 1.8rem;
         box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1);
         transition: 0.3s;
         flex-shrink: 0;
      }

      .back-btn-side:hover {
         background: var(--red);
         color: #fff;
         transform: translateX(-5px);
      }

      .search-form-container {
         position: relative;
         flex-grow: 1;
      }

      .search-form-container form {
         display: flex;
         gap: 1.5rem;
         align-items: center;
         background: #fff;
         padding: 1rem 2.5rem;
         border-radius: 5rem;
         box-shadow: 0 1rem 2.5rem rgba(0,0,0,0.05);
         border: 1px solid #eee;
      }

      .search-form-container form .box {
         width: 100%;
         border: none;
         font-size: 1.8rem;
         background: none;
         outline: none;
         height: 3.5rem;
      }

      .search-form-container form button {
         background: none;
         font-size: 2rem;
         color: var(--light-color);
         cursor: pointer;
      }

      /* --- CLEAN TEXT RECOMMENDATIONS --- */
      #suggestions {
         position: absolute;
         top: 120%;
         left: 0;
         right: 0;
         background: #fff;
         border-radius: 1.5rem;
         box-shadow: 0 1rem 3rem rgba(0,0,0,0.15);
         z-index: 1000;
         overflow: hidden;
         display: none;
         border: 1px solid #eee;
      }

      .suggestion-item {
         padding: 1.2rem 2.5rem;
         font-size: 1.6rem;
         cursor: pointer;
         display: flex;
         align-items: center;
         gap: 1.2rem;
         transition: 0.2s;
         border-bottom: 1px solid #f9f9f9;
         color: var(--black);
      }

      .suggestion-item i {
         color: var(--light-color);
         font-size: 1.4rem;
      }

      .suggestion-item:hover { 
         background: #fff5f5; 
         color: var(--red); 
      }

      .suggestion-item:hover i {
         color: var(--red);
      }
      
      .suggestion-item:last-child {
         border-bottom: none;
      }
      
      .products .box-container .box {
         background: var(--white);
         border-radius: 2rem;
         padding: 2rem;
         box-shadow: 0 1rem 2rem rgba(0,0,0,0.05);
         border: var(--border);
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<div class="search-wrapper">
   <a href="home.php" class="back-btn-side" title="Back to Home">
      <i class="fas fa-arrow-left"></i>
   </a>

   <div class="search-form-container">
      <form method="post" action="" id="search-form">
         <input type="text" name="search_box" id="search-input" placeholder="What are you craving?" class="box" maxlength="100" autocomplete="off">
         <button type="submit" name="search_btn" class="fas fa-search"></button>
      </form>
      <div id="suggestions"></div>
   </div>
</div>

<section class="products" style="min-height: 70vh; padding-top: 0;">
   <div class="box-container">
      <?php
         if(isset($_POST['search_box']) || isset($_POST['search_btn'])){
            $search_box = filter_var($_POST['search_box'], FILTER_SANITIZE_STRING);
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE ?");
            $select_products->execute(["%{$search_box}%"]);
            if($select_products->rowCount() > 0){
               while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
         <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
         <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
         <input type="hidden" name="image" value="<?= $fetch_products['image']; ?>">
         
         <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="" style="width:100%; height:20rem; object-fit:contain;">
         
         <div class="name" style="font-size: 2rem; margin:1rem 0;"><?= $fetch_products['name']; ?></div>
         
         <div class="flex" style="display:flex; justify-content:space-between; align-items:center;">
            <div class="price" style="font-size: 2rem;"><span>₹</span><?= $fetch_products['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" style="width:5rem; padding:1rem; border:var(--border);">
         </div>
         <input type="submit" value="add to cart" class="btn" name="add_to_cart">
      </form>
      <?php
               }
            } else {
               echo '<p class="empty">No matches found!</p>';
            }
         }
      ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script>
const searchInput = document.getElementById('search-input');
const suggestionsBox = document.getElementById('suggestions');
const searchForm = document.getElementById('search-form');

/**
 * TEXT-ONLY RECOMMENDATION LOGIC
 */
searchInput.addEventListener('input', function() {
    const query = this.value.trim();
    if (query.length > 1) {
        fetch(`components/get_suggestions.php?query=${query}`)
            .then(res => res.json())
            .then(data => {
                suggestionsBox.innerHTML = '';
                if (data.length > 0) {
                    suggestionsBox.style.display = 'block';
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.classList.add('suggestion-item');
                        
                        // Using a search icon instead of a broken product image
                        div.innerHTML = `<i class="fas fa-search"></i> <span>${item.name}</span>`;
                        
                        div.onclick = () => {
                            searchInput.value = item.name;
                            suggestionsBox.style.display = 'none';
                            searchForm.submit();
                        };
                        suggestionsBox.appendChild(div);
                    });
                } else { suggestionsBox.style.display = 'none'; }
            })
            .catch(err => console.error("Error fetching suggestions:", err));
    } else { suggestionsBox.style.display = 'none'; }
});

// Close suggestions on outside click
document.addEventListener('click', (e) => {
    if (!searchInput.contains(e.target)) suggestionsBox.style.display = 'none';
});
</script>

</body>
</html>