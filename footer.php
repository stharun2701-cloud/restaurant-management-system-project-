<footer class="footer">

   <section class="grid">

      <div class="box">
         <div class="icon-circle">
            <i class="fas fa-envelope"></i>
         </div>
         <h3>Our Email</h3>
         <a href="mailto:s.tharun2701@gmail.com">s.tharun2701@gmail.com</a>
         <a href="mailto:shahidbasha8050@gmail.com">shahidbasha8050@gmail.com</a>
      </div>

      <div class="box">
         <div class="icon-circle">
            <i class="fas fa-clock"></i>
         </div>
         <h3>Opening Hours</h3>
         <p>10:00am to 10:00pm</p>
         <p style="font-size: 1.2rem; color: var(--red);">Open 7 Days a Week</p>
      </div>

      <div class="box">
         <div class="icon-circle">
            <i class="fas fa-map-marker-alt"></i>
         </div>
         <h3>Our Location</h3>
         <p>Cloud Cuisine Restaurant</p>
         <a href="https://www.google.com/maps" target="_blank">Bangalore, India - 560001</a>
      </div>

      <div class="box">
         <div class="icon-circle">
            <i class="fas fa-phone"></i>
         </div>
         <h3>Contact Us</h3>
         <a href="tel:8123446136">+91 812-344-6136</a>
         <a href="tel:8050931691">+91 805-093-1691</a>
      </div>

   </section>

   <div class="credit">
      &copy; <?= date('Y'); ?> <span>Cuisine Cloud - ST</span> | Crafted for Excellence
      <br>
      <a href="admin/admin_login.php" style="font-size: 1.2rem; color: #999; margin-top: 10px; display: inline-block;">Staff Login</a>
   </div>

</footer>

<div class="loader" id="main-preloader">
   <img src="images/loader.gif" alt="Loading...">
</div>

<style>
   /* --- MODERN FOOTER VISUALS --- */
   .footer {
      background-color: #f9f9f9;
      border-top: 1px solid #eee;
      padding-top: 2rem;
   }

   .footer .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(25rem, 1fr));
      gap: 2rem;
      max-width: 1200px;
      margin: 0 auto;
      padding: 2rem;
   }

   .footer .grid .box {
      text-align: center;
      padding: 2rem;
      background: #fff;
      border-radius: 1.5rem;
      box-shadow: 0 5px 15px rgba(0,0,0,0.03);
      transition: 0.3s;
   }

   .footer .grid .box:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.07);
   }

   .footer .grid .box .icon-circle {
      height: 6rem;
      width: 6rem;
      line-height: 6rem;
      background: var(--red);
      color: #fff;
      border-radius: 50%;
      font-size: 2.5rem;
      margin: 0 auto 1.5rem;
   }

   .footer .grid .box h3 {
      font-size: 2rem;
      margin: 1rem 0;
      color: var(--black);
      text-transform: capitalize;
   }

   .footer .grid .box a, 
   .footer .grid .box p {
      font-size: 1.5rem;
      color: var(--light-color);
      display: block;
      margin-top: 0.5rem;
   }

   .footer .grid .box a:hover {
      color: var(--red);
      text-decoration: underline;
   }

   .footer .credit {
      padding: 3rem 2rem;
      text-align: center;
      background-color: var(--black);
      color: #fff;
      font-size: 1.8rem;
   }

   .footer .credit span {
      color: var(--red);
   }

   /* --- LOADER KILL SWITCH --- */
   .loader {
      position: fixed;
      top: 0; left: 0;
      height: 100%; width: 100%;
      z-index: 10000;
      background-color: var(--white);
      display: flex;
      align-items: center;
      justify-content: center;
   }

   /* This class is applied via JS to fade the loader out */
   .loader.fade-out {
      opacity: 0;
      pointer-events: none;
      transition: 0.5s ease;
   }
</style>

<script>
   // Safety Switch: If the loader hasn't disappeared in 3 seconds, force it to hide
   setTimeout(() => {
      const loader = document.getElementById('main-preloader');
      if(loader) loader.classList.add('fade-out');
   }, 3000);

   // Standard Hide
   window.onload = () => {
      const loader = document.getElementById('main-preloader');
      if(loader) loader.classList.add('fade-out');
   };
</script>