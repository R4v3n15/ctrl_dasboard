    </div> <!-- END CONTAINER -->
        <span data-toggle=snackbar
              data-content=""
              data-timeout="4200"
              data-html-allowed="true"
              id="general_snack">
        </span>
        <div class="footer"></div>
    <script>
        var _root_ = "<?php echo Config::get('URL');  ?>";
    </script>
    <script src="<?php echo Config::get('URL'); ?>assets/libs/js/modernizr.min.js"></script>
	<script src="<?php echo Config::get('URL'); ?>assets/libs/js/jquery-331.min.js"></script>
    <script src="<?php echo Config::get('URL'); ?>assets/libs/js/popper.min.js"></script>
	<script src="<?php echo Config::get('URL'); ?>assets/libs/js/bootstrap.min.js"></script>
    <script src="<?php echo Config::get('URL'); ?>assets/libs/js/coreDataTables.min.js"></script>
    <script src="<?php echo Config::get('URL'); ?>assets/libs/js/dataTables.min.js"></script>
    <script src="<?php echo Config::get('URL'); ?>assets/libs/js/feather.min.js"></script>
    <script src="<?php echo Config::get('URL'); ?>assets/libs/js/snackbar.min.js"></script>

    <script src="<?php echo Config::get('URL'); ?>assets/js/main.js"></script>

    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <script>
      feather.replace()
    </script>
	<?php //custom Js
        if(Registry::has('js')){
            Registry::get('js');
        }
    ?>
    <?php if (View::active($filename, 'mapa')): ?>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyHF2SBcSc6_csYijtpiS8tOFrI06zzPs&callback=initMap"></script>
    <?php endif ?>

    <script src="https://www.gstatic.com/firebasejs/live/3.0/firebase.js"></script>
    <script>
        // Initialize Firebase
        var config = {
            apiKey: "AIzaSyBKDQ2gr358d_GZcPVl6CeHAcBYnoXi2ek",
            authDomain: "apt-passage-173614.firebaseapp.com",
            databaseURL: "https://apt-passage-173614.firebaseio.com",
            projectId: "apt-passage-173614",
            storageBucket: "",
            messagingSenderId: "803217042956"
        };
        firebase.initializeApp(config);
    </script>
</body>
</html>