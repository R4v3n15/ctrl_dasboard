<div class="container">
    <div class="well">

        <?php $this->renderFeedbackMessages(); ?>
        <div class="row">
            <div class="col-lg-12">
            <?php if ($this->user) { ?>
                <h3 class="text-center text-info">Perfil del Usuario: <?= $this->user->user_id; ?></h3>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="well card-blue">
                            <div class="card-avatar">
                                <?php if (isset($this->user->user_avatar_link)) { ?>
                                    <img src="<?= $this->user->user_avatar_link; ?>" />
                                <?php } else { ?>
                                <img src="<?php echo Config::get('URL').Config::get('PATH_AVATARS_PUBLIC'); ?>avatar.png" alt="avatar">
                                <?php } ?>
                            </div>
                            <div class="card-title"><h3 class="text-center"><?= $this->user->user_name; ?></h3></div>
                            <div class="card-body"><p>Correo Electronico: <?= $this->user->user_email; ?></p></div>
                            <div class="card-footer"><label> Activo?: <?= ($this->user->user_active == 0 ? 'No' : 'Yes'); ?></label></div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="bs-component">
                            <ul class="nav nav-tabs well line-head" style="margin-bottom: 15px;">
                                <li class="active">
                                    <a href="#club" data-toggle="tab">ENGLISH CLUB</a>
                                </li>
                                <li>
                                    <a href="#primary" data-toggle="tab">PRIMARY</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content well line-body">
                                <?php $this->renderFeedbackMessages(); ?>
                                <div class="tab-pane fade active in" id="club">
                                    <h4>Titulo parte 1</h4>
                                  <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
                                </div>
                                <div class="tab-pane fade" id="primary">
                                  <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>
        </div>
    </div>
</div>
