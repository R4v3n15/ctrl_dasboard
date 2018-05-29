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
	<script src="<?php echo Config::get('URL'); ?>assets/libs/js/jquery-331.min.js"></script>
    <script src="<?php echo Config::get('URL'); ?>assets/libs/js/popper.min.js"></script>
	<script src="<?php echo Config::get('URL'); ?>assets/libs/js/bootstrap.min.js"></script>
    <script src="<?php echo Config::get('URL'); ?>assets/libs/js/coreDataTables.min.js"></script>
    <script src="<?php echo Config::get('URL'); ?>assets/libs/js/dataTables.min.js"></script>
    <script src="<?php echo Config::get('URL'); ?>assets/libs/js/feather.min.js"></script>
    <script src="<?php echo Config::get('URL'); ?>assets/libs/js/snackbar.min.js"></script>

    <script src="<?php echo Config::get('URL'); ?>assets/js/main.js"></script>

    <script>
        $(".menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });

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
</body>
</html>