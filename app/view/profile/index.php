<div class="row" id="page-content-wrapper">
    <main role="main" class="col-md-12 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-0 pb-2 mb-3 border-bottom">
            <h5 class="text-info">Usuarios de Control Escolar</h5>
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <button class="btn btn-sm btn-outline-secondary">Share</button>
                    <button class="btn btn-sm btn-outline-secondary">Export</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
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
    </main>
</div>
