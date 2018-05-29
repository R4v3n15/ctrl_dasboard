<div class="container">
    <!-- echo out the system feedback (error and success messages) -->
    <ol class="breadcrumb">
        <li><a href="javascript:void(0)">Inicio</a></li>
        <li><a href="javascript:void(0)" class="active">Usuario</a></li>
    </ol> 
    <div class="card-success">
    <?php $this->renderFeedbackMessages(); ?>
        <h2>Change your email address</h2>

        <form action="<?php echo Config::get('URL'); ?>user/editUserEmail_action" method="post">
            <label>
                New email address: <input type="text" name="user_email" required />
            </label>
            <input type="submit" value="Submit" />
        </form>
    </div>
</div>
