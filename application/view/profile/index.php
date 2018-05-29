<div class="container">
    <div class="well card-green">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <?php $this->renderFeedbackMessages(); ?>

                <div class="card-title">
                    <h3 class="text-center">Usuarios de Control Escolar</h3>
                </div>

                <div>
                    <table class="table table-hover">
                        <thead>
                        <tr class="info">
                            <th class="text-center">Id</th>
                            <th class="text-center">Avatar</th>
                            <th class="text-center">Usuario</th>
                            <th class="text-center">Correo</th>
                            <th class="text-center">Activo ?</th>
                            <th class="text-center">Opción</th>
                        </tr>
                        </thead>
                        <?php foreach ($this->users as $user) { ?>
                            <tr class="<?= ($user->user_active == 0 ? 'inactive' : 'active'); ?>">
                                <td><?= $user->user_id; ?></td>
                                <td class="avatar">
                                    <?php if (isset($user->user_avatar_link)) { ?>
                                        <img class="avatar-mini" src="<?= $user->user_avatar_link; ?>" />
                                    <?php } ?>
                                </td>
                                <td><?= $user->user_name; ?></td>
                                <td><?= $user->user_email; ?></td>
                                <td><?= ($user->user_active == 0 ? 'No' : 'Si'); ?></td>
                                <td>
                                    <a href="<?= Config::get('URL') . 'profile/showProfile/' . $user->user_id; ?>" class="link">Ver Pelfil</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
