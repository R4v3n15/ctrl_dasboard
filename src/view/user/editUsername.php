<div class="container">
    <!-- echo out the system feedback (error and success messages) -->
    

    <div class="well card-blue">
        <?php $this->renderFeedbackMessages(); ?>
        <h3 class="text-center">Cambiar Nombre de Usuario</h3>

        <form action="<?php echo Config::get('URL'); ?>user/editUserName_action" method="post">
            <label>
                Nuevo Nombre de Usuario: <input type="text" name="user_name" required />
            </label>
			<!-- set CSRF token at the end of the form -->
			<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />
            <input type="submit" value="Cambiar" class="btn btn-second btn-raised btn-sm" />
        </form>
    </div>
</div>
